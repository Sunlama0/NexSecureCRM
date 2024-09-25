<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Afficher la liste des paiements pour la société de l'utilisateur connecté
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et a un company_id valide
        if (!auth()->check() || auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        // Filtrer les paiements par la société de l'utilisateur connecté
        $companyId = auth()->user()->company_id; // Assurez-vous que le champ company_id est défini pour l'utilisateur

        $payments = Payment::with('invoice')
            ->where('company_id', $companyId) // Filtrer les paiements par la société
            ->get();

        return view('payments.index', compact('payments'));
    }
}
