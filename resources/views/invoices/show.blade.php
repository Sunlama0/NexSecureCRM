@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Détails de la Facture #{{ $invoice->invoice_number }}</h2>

                <!-- Boutons d'action -->
                <div class="flex space-x-4">
                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                        class="flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l4-4m0 0l-4-4m4 4H7"></path>
                        </svg>
                        Modifier
                    </a>

                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form>

                    {{-- <a href="{{ route('invoices.download', $invoice->id) }}"
                        class="flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4"></path>
                        </svg>
                        Télécharger PDF
                    </a> --}}

                    <a href="{{ route('invoices.index') }}"
                        class="flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-gray-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux factures
                    </a>
                </div>
            </div>

            <!-- Détails du client -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Client</h3>
                <p class="text-gray-600 mt-1">{{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
                <p class="text-gray-600">{{ $invoice->client->email }}</p>
                <p class="text-gray-600">{{ $invoice->client->phone }}</p>
            </div>

            <!-- Informations sur la facture -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Informations sur la facture</h3>
                <p class="text-gray-600 mt-1">Date de la facture : {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                <p class="text-gray-600">Date d'échéance : {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</p>
                <p class="text-gray-600">Objet : {{ $invoice->subject }}</p>
                <p class="text-gray-600">Moyen de paiement : {{ $invoice->payment_method }}</p>
                <p class="text-gray-600">Statut :
                    @if($invoice->status == 'Brouillon')
                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">Brouillon</span>
                    @elseif($invoice->status == 'Envoyé')
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">Envoyé</span>
                    @elseif($invoice->status == 'Expiré')
                        <span class="inline-block px-2 py-1 bg-red-100 text-red-600 rounded-full text-sm">Expiré</span>
                    @elseif($invoice->status == 'Payé')
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-600 rounded-full text-sm">Payé</span>
                    @endif
                </p>
            </div>

            <!-- Détails des articles -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Détails des articles</h3>
                <table class="min-w-full mt-4 bg-white rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600">Description</th>
                            <th class="px-4 py-2 text-left text-gray-600">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-600">Taux</th>
                            <th class="px-4 py-2 text-left text-gray-600">Taxe (%)</th>
                            <th class="px-4 py-2 text-left text-gray-600">Remise (€)</th>
                            <th class="px-4 py-2 text-left text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-2 text-gray-700">{{ $item->description }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format($item->rate, 2) }} €</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format($item->tax, 2) }} %</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format($item->discount, 2) }} €</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Résumé des montants -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Résumé des montants</h3>
                <div class="mt-2 space-y-2 text-right">
                    <p class="text-gray-600">Sous-total : {{ number_format($invoice->subtotal, 2) }} €</p>
                    <p class="text-gray-600">Taxe : {{ number_format($invoice->total_tax, 2) }} €</p>
                    <p class="text-gray-600">Remise : {{ number_format($invoice->total_discount, 2) }} €</p>
                    <p class="text-xl font-bold text-gray-800">Total : {{ number_format($invoice->total, 2) }} €</p>
                </div>
            </div>
        </div>
    </div>
@endsection
