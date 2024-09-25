<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            width: 40%;
        }

        .header-right {
            text-align: right;
        }

        .header-right h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .header-right p {
            margin: 5px 0;
            font-size: 14px;
        }

        .company-logo {
            max-width: 120px;
            height: auto;
        }

        .status {
            font-size: 14px;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 4px;
            display: inline-block;
            color: #fff;
        }

        .status-draft {
            background-color: #6c757d;
            /* Gris pour Brouillon */
        }

        .status-sent {
            background-color: #007bff;
            /* Bleu pour Envoyé */
        }

        .status-paid {
            background-color: #28a745;
            /* Vert pour Payé */
        }

        .status-expired {
            background-color: #dc3545;
            /* Rouge pour Expiré */
        }

        .facture-details {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .facture-details p {
            margin: 2px 0;
            line-height: 1.2;
        }

        .client-details {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .financial-summary {
            width: 50%;
            margin-top: 20px;
            float: right;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-align: right;
        }

        .financial-summary p {
            margin: 6px 0;
            font-size: 14px;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            position: fixed;
            bottom: 20px;
            width: 100%;
        }

        .date {
            margin-bottom: 50px;
        }
    </style>
</head>

<body>

    <!-- Container -->
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="header-left">
                @if ($invoice->company->logo)
                    <img src="{{ public_path('storage/' . $invoice->company->logo) }}" alt="Logo de la société"
                        class="company-logo">
                @else
                    <img src="{{ public_path('images/nexsecure-logo.png') }}" alt="NexSecure Logo" class="company-logo">
                @endif
            </div>
            <div class="header-right">
                <h1>FACTURE</h1>
                <p><strong>N° de la facture :</strong> {{ $invoice->invoice_number }}</p>
                <p
                    class="status
                    {{ $invoice->status == 'Brouillon' ? 'status-draft' : '' }}
                    {{ $invoice->status == 'Envoyé' ? 'status-sent' : '' }}
                    {{ $invoice->status == 'Payé' ? 'status-paid' : '' }}
                    {{ $invoice->status == 'Expiré' ? 'status-expired' : '' }}">
                    État du devis : {{ $invoice->status }}
                </p>
            </div>
        </div>

        <!-- Facture Details (Company and Client Information) -->
        <div class="facture-details">
            <div class="company-details">
                <strong>{{ $invoice->company->name }}</strong>
                <p><strong>Généré par :</strong> {{ Auth::user()->name }}</p>
                <p>{{ $invoice->company->address }}</p>
                <p>{{ $invoice->company->telephone }}</p>
                <p>{{ $invoice->company->mail }}</p>
                <p><strong>Siret :</strong> {{ $invoice->company->siret }}</p>
                <p><strong>Siren :</strong> {{ $invoice->company->siren }}</p>
            </div>
            <div class="client-details">
                <strong>Facturé à :</strong>
                <p>{{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                <p>{{ $invoice->client->company }}</p>
                <p>{{ $invoice->client->address }}</p>
                <p>{{ $invoice->client->email }}</p>
                <p>{{ $invoice->client->phone }}</p>
            </div>
        </div>

        <!-- Invoice Date and Payment Terms -->
        <div class="date">
            <p><strong>Date de la facture :</strong>
                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
            <p><strong>Date d'échéance :</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</p>
            <p><strong>Conditions :</strong> Payable à réception</p>
        </div>

        <!-- Items Table -->
        <h3>Détails des articles :</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Article & Description</th>
                    <th>Quantité</th>
                    <th>Taux</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->rate, 2) }} €</td>
                        <td>{{ number_format($item->total, 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Financial Summary (Aligned to the right) -->
        <div class="financial-summary">
            <p><strong>Total HT :</strong> {{ number_format($invoice->subtotal, 2) }} €</p>
            <p><strong>Taxe ({{ $invoice->tax_percentage }}%) :</strong> {{ number_format($invoice->total_tax, 2) }} €
            </p>
            <p><strong>Remise :</strong> {{ number_format($invoice->total_discount, 2) }} €</p>
            <p class="total"><strong>Total TTC :</strong> {{ number_format($invoice->total, 2) }} €</p>
        </div>
    </div>

    <!-- Footer Section -->
    {{-- <div class="footer">
        <p>{{ $invoice->company->name }} | {{ $invoice->company->address }} | SIRET : {{ $invoice->company->siret }}
        </p>
    </div> --}}

</body>

</html>
