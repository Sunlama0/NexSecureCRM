<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientController extends Controller
{
    use AuthorizesRequests;

    // Affichage de la liste des clients appartenant à la société de l'utilisateur connecté
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        // Récupérer la société de l'utilisateur connecté
        $companyId = Auth::user()->company_id;

        // Récupérer tous les clients appartenant à cette société
        $clients = Client::where('company_id', $companyId)->get();

        return view('clients.index', compact('clients'));
    }

    // Afficher le formulaire de création d'un nouveau client
    public function create()
    {
        return view('clients.create');
    }

    // Enregistrer un nouveau client dans la base de données
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
        ]);

        // Ajouter l'ID de la société à laquelle appartient l'utilisateur connecté
        $validated['company_id'] = Auth::user()->company_id;

        // Créer un nouveau client
        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client créé avec succès.');
    }

    // Afficher les détails d'un client spécifique
    public function show(Client $client)
    {
        // Vérification que le client appartient à la société de l'utilisateur connecté
        $this->authorize('view', $client);

        return view('clients.show', compact('client'));
    }

    // Afficher le formulaire de modification d'un client
    public function edit(Client $client)
    {
        // Vérification que le client appartient à la société de l'utilisateur connecté
        $this->authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    // Mettre à jour un client existant dans la base de données
    public function update(Request $request, Client $client)
    {
        // Validation des données
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
        ]);

        // Vérification que le client appartient à la société de l'utilisateur connecté
        $this->authorize('update', $client);

        // Mise à jour du client
        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    // Supprimer un client de la base de données
    public function destroy(Client $client)
    {
        // Vérification que le client appartient à la société de l'utilisateur connecté
        $this->authorize('delete', $client);

        // Suppression du client
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
