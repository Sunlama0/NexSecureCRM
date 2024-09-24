<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Affichage de la liste des factures
    public function index()
    {
        $user = auth()->user();

        // Récupérer uniquement les factures de la société de l'utilisateur
        $invoices = Invoice::where('company_id', $user->company_id)
            ->with('client')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    // Formulaire de création d'une facture
    public function create()
    {
        $user = auth()->user();

        // Récupérer les clients de la société de l'utilisateur
        $clients = Client::where('company_id', $user->company_id)->get();
        $invoiceNumber = $this->generateInvoiceNumber();

        return view('invoices.create', compact('clients', 'invoiceNumber'));
    }

    // Générer un numéro de facture unique
    private function generateInvoiceNumber()
    {
        $user = auth()->user();

        $lastInvoice = Invoice::where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice) {
            return 'INV-000001';
        }

        $lastNumber = intval(substr($lastInvoice->invoice_number, 4));
        $newNumber = $lastNumber + 1;

        return 'INV-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Stockage d'une nouvelle facture
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255|unique:invoices',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Brouillon,Envoyé,Expiré,Payé',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax' => 'required|numeric|min:0',
        ]);

        $invoiceDate = Carbon::parse($request->invoice_date);
        $dueDate = Carbon::parse($request->due_date);

        // Créer la facture
        $invoice = new Invoice();
        $invoice->client_id = $request->client_id;
        $invoice->company_id = $user->company_id; // Associer la facture à la société
        $invoice->invoice_number = $this->generateInvoiceNumber();
        $invoice->invoice_date = $invoiceDate;
        $invoice->due_date = $dueDate;
        $invoice->subject = $request->subject;
        $invoice->status = $request->status;
        $invoice->subtotal = 0;
        $invoice->total_tax = 0;
        $invoice->total_discount = 0;
        $invoice->total = 0;
        $invoice->user_id = $user->id;

        $invoice->save();

        // Traiter les articles de la facture
        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        foreach ($request->items as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->description = $item['description'];
            $invoiceItem->quantity = $item['quantity'];
            $invoiceItem->rate = $item['rate'];
            $invoiceItem->tax = $item['tax'];
            $invoiceItem->discount = $item['discount'] ?? 0;

            $totalArticle = ($item['quantity'] * $item['rate']) + (($item['quantity'] * $item['rate']) * ($item['tax'] / 100)) - $invoiceItem->discount;
            $subtotal += $item['quantity'] * $item['rate'];
            $totalTax += ($item['quantity'] * $item['rate']) * ($item['tax'] / 100);
            $totalDiscount += $invoiceItem->discount;

            $invoiceItem->total = $totalArticle;
            $invoiceItem->save();
        }

        // Mettre à jour les totaux de la facture
        $invoice->subtotal = $subtotal;
        $invoice->total_tax = $totalTax;
        $invoice->total_discount = $totalDiscount;
        $invoice->total = $subtotal + $totalTax - $totalDiscount;
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès.');
    }

    // Affichage d'une facture spécifique
    public function show($id)
    {
        $user = auth()->user();

        $invoice = Invoice::where('company_id', $user->company_id)
            ->with('items', 'client')
            ->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    // Formulaire d'édition d'une facture
    public function edit($id)
    {
        $user = auth()->user();

        $invoice = Invoice::where('company_id', $user->company_id)
            ->with('items')
            ->findOrFail($id);

        // Récupérer les clients de la société de l'utilisateur
        $clients = Client::where('company_id', $user->company_id)->get();

        // Empêcher la modification si la facture est payée
        if ($invoice->status == 'Payé') {
            return redirect()->route('invoices.index')->with('error', 'Cette facture ne peut pas être modifiée car elle est déjà payée.');
        }

        return view('invoices.edit', compact('invoice', 'clients'));
    }

    // Mise à jour d'une facture
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $invoice = Invoice::where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Brouillon,Envoyé,Expiré,Payé',
            'payment_method' => $request->status == 'Payé' ? 'required|string|max:255' : 'nullable|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax' => 'required|numeric|min:0',
        ], [
            'payment_method.required' => 'Le moyen de paiement est requis lorsque le statut est "Payé".',
        ]);

        // Mettre à jour la facture
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = Carbon::parse($request->invoice_date);
        $invoice->due_date = Carbon::parse($request->due_date);
        $invoice->subject = $request->subject;

        // Si le statut passe à "Payé", vérifier qu'un moyen de paiement est fourni
        if ($request->status == 'Payé') {
            if (!$request->payment_method) {
                return redirect()->back()->withErrors(['payment_method' => 'Le moyen de paiement est obligatoire pour une facture payée.']);
            }

            // Enregistrer le paiement
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'company_id' => auth()->user()->company_id,
            ]);
        }

        $invoice->status = $request->status;
        $invoice->payment_method = $request->payment_method;
        $invoice->save();

        // Supprimer les anciens articles et ajouter les nouveaux
        $invoice->items()->delete();

        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        foreach ($request->items as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->description = $item['description'];
            $invoiceItem->quantity = $item['quantity'];
            $invoiceItem->rate = $item['rate'];
            $invoiceItem->tax = $item['tax'];
            $invoiceItem->discount = $item['discount'] ?? 0;

            $totalArticle = ($item['quantity'] * $item['rate']) + (($item['quantity'] * $item['rate']) * ($item['tax'] / 100)) - $invoiceItem->discount;
            $subtotal += $item['quantity'] * $item['rate'];
            $totalTax += ($item['quantity'] * $item['rate']) * ($item['tax'] / 100);
            $totalDiscount += $invoiceItem->discount;

            $invoiceItem->total = $totalArticle;
            $invoiceItem->save();
        }

        // Mettre à jour les totaux de la facture
        $invoice->subtotal = $subtotal;
        $invoice->total_tax = $totalTax;
        $invoice->total_discount = $totalDiscount;
        $invoice->total = $subtotal + $totalTax - $totalDiscount;
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Facture mise à jour avec succès.');
    }

    // Suppression d'une facture
    public function destroy($id)
    {
        $user = auth()->user();

        $invoice = Invoice::where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Facture supprimée avec succès.');
    }
}
