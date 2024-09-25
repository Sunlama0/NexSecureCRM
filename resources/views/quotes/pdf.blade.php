<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis #{{ $quote->quote_number }}</title>
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
            /* width: 50%; */
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
            float: right;
            color: #fff;
            max-width: 300px;
        }

        .status-facture {
            background-color: green;
        }

        .status-expired {
            background-color: red;
        }

        .status-draft {
            background-color: gray;
        }

        .status-sent {
            background-color: orange;
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
                @if($quote->company->logo)
                <img src="{{ public_path('storage/' . $quote->company->logo) }}" alt="Logo de la société"
                    class="company-logo">
                @else
                <img src="{{ public_path('images/nexsecure-logo.png') }}" alt="NexSecure Logo" class="company-logo">
                @endif
            </div>
            <div class="header-right">
                <h1>DEVIS</h1>
                <p><strong>N° du devis :</strong> {{ $quote->quote_number }}</p>
                <p class="status {{ $quote->status == 'Facturé' ? 'status-facture' : ($quote->status == 'Expiré' ? 'status-expired' : ($quote->status == 'Brouillon' ? 'status-draft' : 'status-sent')) }}">
                    État du devis : {{ $quote->status }}
                </p>
            </div>
        </div>

        <!-- Facture Details (Company and Client Information) -->
        <div class="facture-details">
            <div class="company-details">
                <strong>{{ $quote->company->name }}</strong>
                <p><strong>Généré par :</strong> {{ Auth::user()->name }}</p>
                <p>{{ $quote->company->address }}</p>
                <p>{{ $invoice->company->telephone }}</p>
                <p>{{ $invoice->company->mail }}</p>
                <p><strong>Siret :</strong> {{ $quote->company->siret }}</p>
                <p><strong>Siren :</strong> {{ $quote->company->siren }}</p>
            </div>
            <div class="client-details">
                <strong>Facturé à :</strong>
                <p>{{ $quote->client->first_name }} {{ $quote->client->last_name }}</p>
                <p>{{ $quote->client->company }}</p>
                <p>{{ $quote->client->address }}</p>
                <p>{{ $quote->client->email }}</p>
                <p>{{ $quote->client->phone }}</p>
            </div>
        </div>

        <!-- Quote and Expiration Date -->
        <div class="date">
            <p><strong>Date du devis :</strong> {{ \Carbon\Carbon::parse($quote->quote_date)->format('d/m/Y') }}</p>
            <p><strong>Date d'expiration :</strong> {{ \Carbon\Carbon::parse($quote->expiration_date)->format('d/m/Y') }}</p>
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
                @foreach ($quote->items as $index => $item)
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
            <p><strong>Total HT :</strong> {{ number_format($quote->subtotal, 2) }} €</p>
            <p><strong>Taxe ({{ $quote->tax_percentage }}%) :</strong> {{ number_format($quote->total_tax, 2) }} €</p>
            <p><strong>Remise :</strong> {{ number_format($quote->total_discount, 2) }} €</p>
            <p class="total"><strong>Total TTC :</strong> {{ number_format($quote->total, 2) }} €</p>
        </div>
    </div>

    <!-- Footer Section -->
    {{-- <div class="footer">
        <p>{{ $quote->company->name }} | {{ $quote->company->address }} | {{ $quote->company->siret }}</p>
    </div> --}}

</body>

</html>
