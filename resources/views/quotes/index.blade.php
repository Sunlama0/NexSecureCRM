@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Liste des Devis</h2>

            <!-- Lien pour créer un nouveau devis -->
            <a href="{{ route('quotes.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Créer un nouveau devis
            </a>

            <!-- Alignement du champ de recherche à droite et collé au tableau -->
            <div class="flex justify-end mt-4 mb-4">
                <input type="text" id="searchInput" placeholder="Rechercher..."
                    class="block w-1/3 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            @if ($quotes->isEmpty())
                <p class="mt-6">Aucun devis trouvé.</p>
            @else
            <table class="min-w-full divide-y divide-gray-200 mt-6" id="quotesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro de devis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date du devis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($quotes as $quote)
                        <tr>
                            <td class="px-6 py-4">{{ $quote->quote_number }}</td>
                            <td class="px-6 py-4">{{ $quote->client->first_name }} {{ $quote->client->last_name }}</td>
                            <td class="px-6 py-4">{{ number_format($quote->total, 2) }} €</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($quote->quote_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($quote->expiration_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($quote->status == 'Brouillon')
                                    <span class="text-gray-500">{{ $quote->status }}</span>
                                @elseif($quote->status == 'Envoyé')
                                    <span class="text-blue-600">{{ $quote->status }}</span>
                                @elseif($quote->status == 'Facturé')
                                    <span class="text-green-600">{{ $quote->status }}</span>
                                @elseif($quote->status == 'Expiré')
                                    <span class="text-red-600">{{ $quote->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('quotes.show', $quote) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @endif
        </div>
    </div>

    <!-- JavaScript pour filtrer les devis dans le tableau -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const quotesTable = document.getElementById('quotesTable').getElementsByTagName('tbody')[0];
            const rows = quotesTable.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');

                    let rowText = '';
                    for (let j = 0; j < cells.length; j++) {
                        rowText += cells[j].innerText.toLowerCase() + ' ';
                    }

                    if (rowText.indexOf(filter) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection
