<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Affiche la page de paramètres de la société (côté utilisateur).
     */
    public function settings()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        // Récupérer la société de l'utilisateur connecté
        $company = auth()->user()->company;

        // Retourner la vue avec les informations de la société
        return view('company.settings', compact('company'));
    }

    /**
     * Met à jour les informations de la société par la société elle-même (côté utilisateur).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateByCompany(Request $request)
    {
        // Récupérer la société de l'utilisateur connecté
        $company = auth()->user()->company;

        // Valider les données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'nullable|string|max:255',
            'tva_number' => 'nullable|string|max:50',
            'siret' => 'nullable|string|max:50',
            'siren' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'mail' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider le fichier du logo
        ]);

        // Si un nouveau logo est uploadé
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($company->logo) {
                \Storage::delete('public/' . $company->logo);
            }

            // Stocker le nouveau logo dans storage/app/public/logos
            $path = $request->file('logo')->store('logos', 'public'); // Ceci enregistrera le fichier dans storage/app/public/logos
            $validatedData['logo'] = $path; // Ajouter le chemin du logo dans les données validées
        }

        // Mettre à jour les informations de la société
        $company->update($validatedData); // Mettre à jour les champs validés

        // Rediriger avec un message de succès
        return redirect()->route('company.settings')->with('success', 'Les informations de la société ont été mises à jour avec succès.');
    }

    // Voici les méthodes déjà présentes pour la gestion admin que tu m'avais fournie :

    /**
     * Afficher la liste des sociétés (pour l'administration).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle société (pour l'administration).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Enregistrer une nouvelle société (pour l'administration).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|string',
            'address' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'tva_number' => 'nullable|string|max:50',
            'siret' => 'nullable|string|max:50',
            'siren' => 'nullable|string|max:50',
        ]);

        Company::create($validatedData);

        return redirect()->route('companies.index')->with('success', 'Société créée avec succès.');
    }

    /**
     * Afficher les détails d'une société spécifique (pour l'administration).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    /**
     * Afficher le formulaire d'édition d'une société (pour l'administration).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    /**
     * Mettre à jour une société en base de données (pour l'administration).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|string|max:255',
            'tva_number' => 'nullable|string|max:50',
            'siret' => 'nullable|string|max:50',
            'siren' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'mail' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour le logo
        ]);

        // Récupérer la société
        $company = Company::findOrFail($id);

        // Gestion du logo si un fichier est uploadé
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($company->logo) {
                \Storage::delete('public/' . $company->logo);
            }

            // Stocker le nouveau logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        // Mise à jour des données de la société
        $company->update($validatedData);

        return redirect()->route('company.settings')->with('success', 'Société mise à jour avec succès.');
    }

    /**
     * Supprimer une société (pour l'administration).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Société supprimée avec succès.');
    }
}
