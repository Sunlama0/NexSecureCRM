<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    /**
     * Afficher la liste des catégories de matériels pour la société de l'utilisateur authentifié.
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        // Récupérer les catégories de matériels appartenant à la société de l'utilisateur
        $categories = MaterialCategory::where('company_id', auth()->user()->company_id)->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle catégorie.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie de matériels.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Créer la catégorie et l'associer à la société de l'utilisateur authentifié
        MaterialCategory::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'company_id' => auth()->user()->company_id, // Associer à la société
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'une catégorie.
     */
    public function edit($id)
    {
        // Vérifier que la catégorie appartient à la société de l'utilisateur authentifié
        $category = MaterialCategory::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        return view('categories.edit', compact('category'));
    }

    /**
     * Mettre à jour une catégorie de matériels.
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Vérifier que la catégorie appartient à la société de l'utilisateur
        $category = MaterialCategory::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        // Mise à jour des informations
        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie de matériels.
     */
    public function destroy($id)
    {
        // Vérifier que la catégorie appartient à la société de l'utilisateur authentifié
        $category = MaterialCategory::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        // Suppression de la catégorie
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}
