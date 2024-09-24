<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Afficher le calendrier avec les rendez-vous.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        // Récupère tous les rendez-vous depuis la base de données
        $appointments = Appointment::all();

        // Renvoie la vue du calendrier avec les rendez-vous
        return view('calendar.index', compact('appointments'));
    }

    /**
     * Créer un nouveau rendez-vous et l'enregistrer dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Valider les données du formulaire
        $request->validate([
            'title' => 'required|string|max:255',
            'participants' => 'required|array', // array of participants
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
        ]);

        // Créer un nouveau rendez-vous avec les données validées
        Appointment::create([
            'title' => $request->title,
            'participants' => json_encode($request->participants), // On encode les participants en JSON
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location, // facultatif
            'description' => $request->description, // facultatif
            'category' => $request->category, // catégorie pour la couleur
        ]);

        // Rediriger vers la page du calendrier après la création
        return redirect()->route('calendar.index')->with('success', 'Rendez-vous créé avec succès');
    }

    /**
     * Met à jour un rendez-vous existant dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Appointment $appointment): \Illuminate\Http\RedirectResponse
    {
        // Valider les données mises à jour
        $request->validate([
            'title' => 'required|string|max:255',
            'participants' => 'required|array',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
        ]);

        // Mettre à jour les champs du rendez-vous
        $appointment->update([
            'title' => $request->title,
            'participants' => json_encode($request->participants),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        // Rediriger vers le calendrier après la mise à jour
        return redirect()->route('calendar.index')->with('success', 'Rendez-vous mis à jour avec succès');
    }

    /**
     * Supprimer un rendez-vous de la base de données.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment): \Illuminate\Http\RedirectResponse
    {
        // Supprimer le rendez-vous
        $appointment->delete();

        // Rediriger vers le calendrier après la suppression
        return redirect()->route('calendar.index')->with('success', 'Rendez-vous supprimé avec succès');
    }
}
