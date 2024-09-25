<?php

namespace App\Http\Controllers;

use App\Models\DeviceIdentifier;
use Illuminate\Http\Request;

class DeviceIdentifierController extends Controller
{
    public function index()
    {
         // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        $deviceIdentifiers = DeviceIdentifier::where('company_id', auth()->user()->company_id)->get();
        return view('device_identifiers.index', compact('deviceIdentifiers'));
    }

    public function create()
    {
        return view('device_identifiers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        DeviceIdentifier::create([
            'name' => $request->name,
            'company_id' => auth()->user()->company_id,
        ]);

        return redirect()->route('device_identifiers.index')->with('success', 'Identifiant d\'appareil créé avec succès.');
    }

    public function edit($id)
    {
        $deviceIdentifier = DeviceIdentifier::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('device_identifiers.edit', compact('deviceIdentifier'));
    }

    public function update(Request $request, $id)
    {
        $deviceIdentifier = DeviceIdentifier::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);
        $deviceIdentifier->update($request->all());

        return redirect()->route('device_identifiers.index')->with('success', 'Identifiant d\'appareil mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $deviceIdentifier = DeviceIdentifier::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $deviceIdentifier->delete();

        return redirect()->route('device_identifiers.index')->with('success', 'Identifiant d\'appareil supprimé avec succès.');
    }

    public function stockOverview()
{
    // Vérifier que l'utilisateur est connecté et a un company_id valide
    if (!auth()->check() || auth()->user()->company_id === null) {
        // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
        return redirect()->route('no-company');
    }

    // Récupérer le company_id de l'utilisateur connecté
    $companyId = auth()->user()->company_id;

    // Récupérer les identifiants d'appareil et compter les matériels en stock pour cette société
    $deviceIdentifiers = DeviceIdentifier::where('company_id', $companyId) // Filtrer par l'entreprise de l'utilisateur
        ->withCount(['materials' => function ($query) use ($companyId) {
            $query->where('company_id', $companyId)
                  ->where('status', 'En stock'); // Filtrer les matériels "En stock"
        }])
        ->get();

    return view('stocks.index', compact('deviceIdentifiers'));
}

}
