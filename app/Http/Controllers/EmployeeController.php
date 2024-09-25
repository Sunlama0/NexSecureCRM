<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Affiche la liste des employés de la société de l'utilisateur connecté.
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        // Récupérer les employés associés à la société de l'utilisateur connecté
        $employees = User::where('company_id', auth()->user()->company_id)->get();

        // Retourner la vue avec la liste des employés
        return view('employees.index', compact('employees'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel employé.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Enregistre un nouvel employé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'job_title' => 'nullable|string|max:255',
        ]);

        // Créer un nouvel utilisateur (employé) pour la société de l'utilisateur authentifié
        $employee = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'job_title' => $validatedData['job_title'],
            'company_id' => auth()->user()->company_id, // Associer l'utilisateur à la société actuelle
            'password' => Hash::make(Str::random(10)), // Générer un mot de passe temporaire
        ]);

        // Envoyer un email de réinitialisation de mot de passe
        $token = Password::createToken($employee);
        $employee->sendPasswordResetNotification($token);

        return redirect()->route('employees.index')->with('success', 'Employé ajouté avec succès. Un email a été envoyé pour créer son mot de passe.');
    }

    /**
     * Affiche le formulaire d'édition d'un employé.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = User::findOrFail($id);

        return view('employees.edit', compact('employee'));
    }

    /**
     * Met à jour les informations d'un employé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $employee = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($employee->id),
            ],
            'job_title' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les informations de l'employé
        $employee->update($validatedData);

        return redirect()->route('employees.index')->with('success', 'Informations de l\'employé mises à jour.');
    }

    /**
     * Supprime un employé.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Récupérer l'employé
        $employee = User::findOrFail($id);

        // Supprimer l'employé
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employé supprimé avec succès.');
    }
}
