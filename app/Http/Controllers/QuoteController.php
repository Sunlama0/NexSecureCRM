<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuoteController extends Controller
{
    // Affichage de la liste des devis
    public function index()
    {
        $user = auth()->user();

        // Récupérer uniquement les devis de la société de l'utilisateur
        $quotes = Quote::where('company_id', $user->company_id)
            ->with('client')
            ->get();

        return view('quotes.index', compact('quotes'));
    }

    // Formulaire de création d'un devis
    public function create()
    {
        $user = auth()->user();

        // Récupérer les clients de la société de l'utilisateur
        $clients = Client::where('company_id', $user->company_id)->get();
        $quoteNumber = $this->generateQuoteNumber();

        return view('quotes.create', compact('clients', 'quoteNumber'));
    }

    // Générer un numéro de devis unique
    private function generateQuoteNumber()
    {
        $user = auth()->user();

        $lastQuote = Quote::where('company_id', $user->company_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastQuote) {
            return 'QUO-000001';
        }

        $lastNumber = intval(substr($lastQuote->quote_number, 4));
        $newNumber = $lastNumber + 1;

        return 'QUO-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Stockage d'un nouveau devis
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quote_number' => 'required|string|max:255',
            'quote_date' => 'required|date',
            'expiration_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax' => 'required|numeric|min:0',
        ]);

        // Vérifier que le client appartient à la même société
        $client = Client::where('id', $request->client_id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        // Conversion des dates en instances Carbon
        $quoteDate = Carbon::parse($request->quote_date);
        $expirationDate = Carbon::parse($request->expiration_date);

        // Générer le numéro de devis automatiquement
        $quoteNumber = $this->generateQuoteNumber();

        // Créer le devis
        $quote = new Quote();
        $quote->client_id = $request->client_id;
        $quote->company_id = $user->company_id; // Associer le devis à la société
        $quote->quote_number = $quoteNumber;
        $quote->quote_date = $quoteDate;
        $quote->expiration_date = $expirationDate;
        $quote->subject = $request->subject;
        $quote->status = 'Brouillon'; // Statut par défaut
        $quote->subtotal = 0;
        $quote->total_tax = 0;
        $quote->total_discount = 0;
        $quote->total = 0;
        $quote->user_id = $user->id;

        $quote->save();

        // Traiter les articles du devis
        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        foreach ($request->items as $item) {
            $quoteItem = new QuoteItem();
            $quoteItem->quote_id = $quote->id;
            $quoteItem->description = $item['description'];
            $quoteItem->quantity = $item['quantity'];
            $quoteItem->rate = $item['rate'];
            $quoteItem->tax = $item['tax'];
            $quoteItem->discount = $item['discount'] ?? 0;

            $totalArticle = ($item['quantity'] * $item['rate'])
                + (($item['quantity'] * $item['rate']) * ($item['tax'] / 100))
                - $quoteItem->discount;
            $subtotal += $item['quantity'] * $item['rate'];
            $totalTax += ($item['quantity'] * $item['rate']) * ($item['tax'] / 100);
            $totalDiscount += $quoteItem->discount;

            $quoteItem->total = $totalArticle;
            $quoteItem->save();
        }

        // Mettre à jour le devis avec les totaux
        $quote->subtotal = $subtotal;
        $quote->total_tax = $totalTax;
        $quote->total_discount = $totalDiscount;
        $quote->total = $subtotal + $totalTax - $totalDiscount;
        $quote->save();

        return redirect()->route('quotes.index')->with('success', 'Devis créé avec succès.');
    }

    // Affichage d'un devis spécifique
    public function show($id)
    {
        $user = auth()->user();

        $quote = Quote::with('items', 'client')
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        // Vérification automatique du statut : Expiré
        if (Carbon::now()->gt(Carbon::parse($quote->expiration_date)) && $quote->status != 'Expiré') {
            $quote->status = 'Expiré';
            $quote->save();
        }

        return view('quotes.show', compact('quote'));
    }

    // Fonction pour convertir un devis en facture
    public function convertToInvoice($id)
    {
        $user = auth()->user();

        $quote = Quote::where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        // Créer une nouvelle facture en se basant sur les informations du devis
        $invoice = new Invoice();
        $invoice->client_id = $quote->client_id;
        $invoice->company_id = $user->company_id;  // Associer la facture à la société
        $invoice->invoice_number = $this->generateInvoiceNumber();
        $invoice->invoice_date = Carbon::now();
        $invoice->due_date = Carbon::now()->addDays(30);
        $invoice->subject = $quote->subject;
        $invoice->subtotal = $quote->subtotal;
        $invoice->total_tax = $quote->total_tax;
        $invoice->total_discount = $quote->total_discount;
        $invoice->total = $quote->total;
        $invoice->status = 'Brouillon'; // Statut par défaut
        $invoice->user_id = $user->id;
        $invoice->save();

        // Copier les articles du devis dans la facture
        foreach ($quote->items as $quoteItem) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->description = $quoteItem->description;
            $invoiceItem->quantity = $quoteItem->quantity;
            $invoiceItem->rate = $quoteItem->rate;
            $invoiceItem->tax = $quoteItem->tax;
            $invoiceItem->discount = $quoteItem->discount;
            $invoiceItem->total = $quoteItem->total;
            $invoiceItem->save();
        }

        // Mettre à jour le statut du devis en "Facturé"
        $quote->status = 'Facturé';
        $quote->save();

        // Rediriger vers la facture nouvellement créée
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Le devis a été converti en facture avec succès.');
    }

    // Générer un numéro de facture unique
    public function generateInvoiceNumber()
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

    // Formulaire d'édition d'un devis
    public function edit($id)
    {
        $user = auth()->user();

        $quote = Quote::with('items')
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        // Récupérer les clients de la société de l'utilisateur
        $clients = Client::where('company_id', $user->company_id)->get();

        return view('quotes.edit', compact('quote', 'clients'));
    }

    // Mise à jour d'un devis
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quote_number' => 'required|string|max:255',
            'quote_date' => 'required|date',
            'expiration_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Brouillon,Envoyé,Facturé,Expiré',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax' => 'required|numeric|min:0',
        ]);

        // Vérifier que le client appartient à la même société
        $client = Client::where('id', $request->client_id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        // Conversion des dates en instances Carbon
        $quoteDate = Carbon::parse($request->quote_date);
        $expirationDate = Carbon::parse($request->expiration_date);

        // Mettre à jour le devis
        $quote = Quote::where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $quote->client_id = $request->client_id;
        $quote->quote_number = $request->quote_number;
        $quote->quote_date = $quoteDate;
        $quote->expiration_date = $expirationDate;
        $quote->subject = $request->subject;
        $quote->status = $request->status;
        $quote->save();

        // Supprimer les anciens articles
        $quote->items()->delete();

        // Ajouter les nouveaux articles
        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        foreach ($request->items as $item) {
            $quoteItem = new QuoteItem();
            $quoteItem->quote_id = $quote->id;
            $quoteItem->description = $item['description'];
            $quoteItem->quantity = $item['quantity'];
            $quoteItem->rate = $item['rate'];
            $quoteItem->tax = $item['tax'];
            $quoteItem->discount = $item['discount'] ?? 0;

            $totalArticle = ($item['quantity'] * $item['rate'])
                + (($item['quantity'] * $item['rate']) * ($item['tax'] / 100))
                - $quoteItem->discount;
            $subtotal += $item['quantity'] * $item['rate'];
            $totalTax += ($item['quantity'] * $item['rate']) * ($item['tax'] / 100);
            $totalDiscount += $quoteItem->discount;

            $quoteItem->total = $totalArticle;
            $quoteItem->save();
        }

        // Mettre à jour le devis avec les totaux
        $quote->subtotal = $subtotal;
        $quote->total_tax = $totalTax;
        $quote->total_discount = $totalDiscount;
        $quote->total = $subtotal + $totalTax - $totalDiscount;
        $quote->save();

        return redirect()->route('quotes.index')->with('success', 'Devis mis à jour avec succès.');
    }

    // Suppression d'un devis
    public function destroy($id)
    {
        $user = auth()->user();

        $quote = Quote::where('id', $id)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Devis supprimé avec succès.');
    }
}
