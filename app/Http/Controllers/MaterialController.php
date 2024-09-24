<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialHistory;
use App\Models\Supplier;
use App\Models\DeviceIdentifier;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Affiche la liste des matériels pour la société de l'utilisateur authentifié.
     */
    public function index()
    {
        // Récupérer les matériels pour la société de l'utilisateur connecté
        $materials = Material::where('company_id', auth()->user()->company_id)->get();

        return view('materials.index', compact('materials'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau matériel.
     */
    public function create()
    {
        // Récupérer les catégories, fournisseurs et identifiants d'appareil
        $categories = MaterialCategory::where('company_id', auth()->user()->company_id)->get();
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)->get();
        $deviceIdentifiers = DeviceIdentifier::where('company_id', auth()->user()->company_id)->get();

        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')->with('error', 'Aucune catégorie trouvée, veuillez en créer une.');
        }

        return view('materials.create', compact('categories', 'suppliers', 'deviceIdentifiers'));
    }

    /**
     * Enregistre un nouveau matériel dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'reference' => 'required|string|max:255',
            'device_identifier_id' => 'required|exists:device_identifiers,id',
            'device_name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:materials',
            'description' => 'nullable|string',
            'acquisition_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'cost' => 'nullable|numeric',
            'category_id' => 'required|exists:material_categories,id',
        ]);

        // Créer le matériel et l'associer à la société de l'utilisateur authentifié
        Material::create([
            'reference' => $validatedData['reference'],
            'device_identifier_id' => $validatedData['device_identifier_id'],
            'device_name' => $validatedData['device_name'],
            'serial_number' => $validatedData['serial_number'],
            'description' => $validatedData['description'],
            'acquisition_date' => $validatedData['acquisition_date'],
            'supplier_id' => $validatedData['supplier_id'],
            'cost' => $validatedData['cost'],
            'category_id' => $validatedData['category_id'],
            'company_id' => auth()->user()->company_id,
        ]);

        return redirect()->route('materials.index')->with('success', 'Matériel créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un matériel existant.
     */
    public function edit($id, Request $request)
    {
        // Vérifier que le matériel appartient à la société de l'utilisateur authentifié
        $material = Material::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        // Récupérer les catégories, fournisseurs et identifiants de l'appareil
        $categories = MaterialCategory::where('company_id', auth()->user()->company_id)->get();
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)->get();
        $deviceIdentifiers = DeviceIdentifier::where('company_id', auth()->user()->company_id)->get();

        // Récupérer l'historique avec filtres et pagination
        $historiesQuery = $material->histories()->orderBy('created_at', 'desc');

        // Appliquer le filtre textuel si présent
        if ($request->search) {
            $search = $request->search;

            // Rechercher dans les champs date, utilisateur ou statut
            $historiesQuery->where(function ($query) use ($search) {
                $query->where('created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('changed_by', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginer les résultats
        $histories = $historiesQuery->paginate(10);

        return view('materials.edit', compact('material', 'categories', 'suppliers', 'deviceIdentifiers', 'histories'));
    }

    /**
     * Met à jour un matériel existant dans la base de données.
     */
    public function update(Request $request, $id)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'reference' => 'required|string|max:255',
            'device_identifier_id' => 'required|exists:device_identifiers,id',
            'device_name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:materials,serial_number,' . $id,
            'description' => 'nullable|string',
            'acquisition_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'cost' => 'nullable|numeric',
            'category_id' => 'required|exists:material_categories,id',
            'collaborator_firstname' => 'nullable|string|max:255',
            'collaborator_lastname' => 'nullable|string|max:255',
            'collaborator_position' => 'nullable|string|max:255',
            'assigned_date' => 'nullable|date',
            'out_of_stock' => 'required|string|in:oui,non',
            'out_of_stock_notes' => 'nullable|string',
            'return_to_stock' => 'nullable|boolean',
        ]);

        // Récupérer le matériel
        $material = Material::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        // Convertir 'oui' en 1 et 'non' en 0 pour la colonne out_of_stock
        $validatedData['out_of_stock'] = $validatedData['out_of_stock'] === 'oui' ? 1 : 0;

        // Mettre à jour les informations du matériel
        $material->update($validatedData);

        // Si "Remettre en stock" est coché
        if ($request->has('return_to_stock') && $request->return_to_stock) {
            $material->status = 'En stock';
            $material->collaborator_firstname = null;
            $material->collaborator_lastname = null;
            $material->collaborator_position = null;
            $material->assigned_date = null;
            $material->assigned_to = null;
            $material->out_of_stock = 0; // Indiquer que le matériel est en stock
            $material->save();

            // Enregistrer dans l'historique
            MaterialHistory::create([
                'material_id' => $material->id,
                'status' => 'Remis en stock',
                'notes' => 'Le matériel a été remis en stock.',
                'changed_by' => auth()->user()->name,
            ]);

            return redirect()->route('materials.index')->with('success', 'Matériel remis en stock avec succès.');
        }

        // Si le matériel est sorti du stock
        if ($validatedData['out_of_stock'] === 1) {
            $material->status = 'Sorti';
            $material->save();

            // Enregistrer l'historique si ce n'est pas déjà marqué comme sorti
            if (!$material->histories()->where('status', 'Sorti du stock')->exists()) {
                MaterialHistory::create([
                    'material_id' => $material->id,
                    'status' => 'Sorti du stock',
                    'notes' => $validatedData['out_of_stock_notes'],
                    'changed_by' => auth()->user()->name,
                ]);
            }
        }

        // Vérifier les changements dans l'attribution
        if (!empty($validatedData['collaborator_firstname']) && !empty($validatedData['collaborator_lastname'])) {
            $material->assigned_to = $validatedData['collaborator_firstname'] . ' ' . $validatedData['collaborator_lastname'];
            $material->collaborator_position = $validatedData['collaborator_position'];
            $material->assigned_date = $validatedData['assigned_date'];
            $material->status = 'Attribué';
            $material->save();

            // Enregistrement dans l'historique si une nouvelle attribution est faite
            if (!$material->histories()->where('status', 'Attribué à ' . $material->assigned_to)->exists()) {
                MaterialHistory::create([
                    'material_id' => $material->id,
                    'status' => 'Attribué à ' . $material->assigned_to,
                    'notes' => 'Attribué à ' . $material->assigned_to . ' (Poste: ' . $material->collaborator_position . ')',
                    'changed_by' => auth()->user()->name,
                ]);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Matériel mis à jour avec succès.');
    }

    /**
     * Supprime un matériel de la base de données (remplacé par la sortie de stock).
     */
    public function removeFromStock(Request $request, $id)
    {
        // Vérifier que le matériel appartient à la société de l'utilisateur authentifié
        $material = Material::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        // Mettre à jour le statut à "Sorti"
        $material->status = 'Sorti';
        $material->save();

        // Enregistrer l'historique de la sortie
        MaterialHistory::create([
            'material_id' => $material->id,
            'status' => 'Sorti',
            'notes' => $request->notes,
            'changed_by' => auth()->user()->name,
        ]);

        return redirect()->route('materials.index')->with('success', 'Matériel retiré du stock.');
    }

    /**
     * Historique d'un matériel
     */
    public function show($id)
    {
        // Récupérer le matériel et son historique
        $material = Material::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->with('histories')
            ->firstOrFail();

        return view('materials.show', compact('material'));
    }
}
