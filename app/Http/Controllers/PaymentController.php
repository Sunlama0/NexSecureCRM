<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Afficher la liste des paiements pour la société de l'utilisateur connecté
    public function index()
    {
        // Filtrer les paiements par la société de l'utilisateur connecté
        $companyId = auth()->user()->company_id; // Assurez-vous que le champ company_id est défini pour l'utilisateur

        $payments = Payment::with('invoice')
            ->where('company_id', $companyId) // Filtrer les paiements par la société
            ->get();

        return view('payments.index', compact('payments'));
    }
}
