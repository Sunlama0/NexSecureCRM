<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'password' => 'required|string|min:8|confirmed',
            'position' => 'nullable|string|max:255',
        ]);

        // Créer un nouvel utilisateur (employé) pour la société de l'utilisateur authentifié
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'position' => $validatedData['position'],
            'company_id' => auth()->user()->company_id, // Associer l'utilisateur à la société actuelle
        ]);

        return redirect()->route('employees.index');    }

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
            'password' => 'nullable|string|min:8|confirmed',
            'position' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les informations de l'employé
        $employee->name = $validatedData['name'];
        $employee->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $employee->password = Hash::make($validatedData['password']);
        }
        $employee->position = $validatedData['position'];
        $employee->save();

        // return redirect()->route('company.settings')->with('success', 'Informations de l\'employé mises à jour.');
        return redirect()->route('employees.index');
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

        // return redirect()->route('company.settings')->with('success', 'Employé supprimé avec succès.');
        return redirect()->route('employees.index');
    }
}
