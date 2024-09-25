<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        $suppliers = Supplier::where('company_id', auth()->user()->company_id)->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Supplier::create([
            'name' => $request->name,
            'company_id' => auth()->user()->company_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur créé avec succès.');
    }

    public function edit($id)
    {
        $supplier = Supplier::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);
        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé avec succès.');
    }
}
