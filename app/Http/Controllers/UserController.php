<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur authentifié.
     *
     * @return \Illuminate\Http\Response
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation des champs
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',  // Validation du mot de passe avec confirmation
        ]);

        // Mise à jour des informations de l'utilisateur
        $user->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'job_title' => $validatedData['job_title'],
            'country' => $validatedData['country'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'linkedin' => $validatedData['linkedin'],
            'github' => $validatedData['github'],
            'instagram' => $validatedData['instagram'],
            'bio' => $validatedData['bio'],
        ]);

        // Si le mot de passe est rempli, on le met à jour
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validatedData['password']),
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }
}
