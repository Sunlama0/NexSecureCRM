@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Titre principal -->
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold mb-6">Détails du Devis #{{ $quote->quote_number }}</h2>

                <!-- Boutons d'action -->
                <div class="flex space-x-4">

                    <!-- Bouton Modifier -->
                    <a href="{{ route('quotes.edit', $quote->id) }}"
                        class="flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l4-4m0 0l-4-4m4 4H7"></path>
                        </svg>
                        Modifier
                    </a>

                    <!-- Bouton Supprimer -->
                    <form action="{{ route('quotes.destroy', $quote->id) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                            Supprimer
                        </button>
                    </form>

                    <!-- Bouton Télécharger en PDF -->
                    <a href="{{ route('quotes.downloadPDF', $quote->id) }}"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Télécharger PDF
                    </a>

                    <!-- Bouton Retour -->
                    <a href="{{ route('quotes.index') }}"
                        class="flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-gray-700 transition ease-in-out duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux devis
                    </a>
                </div>
            </div>

            <!-- Détails du client -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Client :</h3>
                <div class="bg-gray-100 p-4 rounded-md shadow-sm">
                    <p class="text-lg"><strong>{{ $quote->client->first_name }} {{ $quote->client->last_name }}</strong></p>
                    <p>Email : <span class="text-gray-600">{{ $quote->client->email }}</span></p>
                    <p>Téléphone : <span class="text-gray-600">{{ $quote->client->phone }}</span></p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold">Informations sur le devis :</h3>
                <div class="bg-gray-100 p-4 rounded-md shadow-sm">
                    <p>Date du devis : <strong>{{ \Carbon\Carbon::parse($quote->quote_date)->format('d/m/Y') }}</strong></p>
                    <p>Date d'expiration :
                        <strong>{{ \Carbon\Carbon::parse($quote->expiration_date)->format('d/m/Y') }}</strong>
                    </p>
                    <p>Objet : <span class="text-gray-600">{{ $quote->subject }}</span></p>
                    <p>Statut :
                        @if ($quote->status == 'Expiré')
                            <span class="text-red-600 font-bold">{{ $quote->status }}</span>
                        @else
                            <span class="text-green-600 font-bold">{{ $quote->status }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Détails des articles -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Détails des articles :</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow-sm rounded-md">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Taux</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($quote->items as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $item->description }}</td>
                                    <td class="px-6 py-4">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4">{{ number_format($item->rate, 2) }} €</td>
                                    <td class="px-6 py-4">{{ number_format($item->total, 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Résumé financier du devis -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Résumé :</h3>
                <div class="bg-gray-100 p-4 rounded-md shadow-sm">
                    <p>Sous-total : <strong>{{ number_format($quote->subtotal, 2) }} €</strong></p>
                    <p>Taxe : <strong>{{ number_format($quote->total_tax, 2) }} €</strong></p>
                    <p>Remise : <strong>{{ number_format($quote->total_discount, 2) }} €</strong></p>
                    <p class="text-lg font-bold">Total : {{ number_format($quote->total, 2) }} €</p>
                </div>
            </div>

            <!-- Bouton Convertir en facture -->
            <div class="mt-4">
                <form action="{{ route('quotes.convertToInvoice', $quote->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Convertir en Facture
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
