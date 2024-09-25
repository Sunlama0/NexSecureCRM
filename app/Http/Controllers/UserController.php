<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Affiche la page de paramètres du profil de l'utilisateur.
     */
    public function showProfile()
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Retourner la vue avec les informations de l'utilisateur
        return view('profile.show', compact('user'));
    }

    /**
     * Met à jour les informations du profil de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation des champs du formulaire
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Validation du mot de passe si fourni
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Gestion de l'image de profil
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                \Storage::delete('public/' . $user->profile_image);
            }
            $path = $request->file('profile_image')->store('logos', 'public');
            $validatedData['profile_image'] = $path;
        }

        // Mise à jour des informations utilisateur
        $user->update($validatedData);

        // Mise à jour du mot de passe uniquement si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
            $user->save();
        }

        Log::info('Mot de passe mis à jour pour l\'utilisateur : ' . $user->id);

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }

}
