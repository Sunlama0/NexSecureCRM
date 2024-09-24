@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Liste des Factures</h2>

            <a href="{{ route('invoices.create') }}"
                class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Créer une nouvelle facture
            </a>

            <!-- Alignement du champ de recherche à droite et collé au tableau -->
            <div class="flex justify-end mt-4 mb-4">
                <input type="text" id="searchInput2" placeholder="Rechercher..."
                    class="block w-1/3 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            @if ($invoices->isEmpty())
                <p class="mt-6">Aucune facture trouvée.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200 mt-6" id="InvoicesTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Numéro
                                de facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                de la facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                d'échéance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">{{ $invoice->client->first_name }} {{ $invoice->client->last_name }}
                                </td>
                                <td class="px-6 py-4">{{ number_format($invoice->total, 2) }} €</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($invoice->status == 'Brouillon')
                                        <span class="text-gray-500">{{ $invoice->status }}</span>
                                    @elseif($invoice->status == 'Envoyé')
                                        <span class="text-blue-600">{{ $invoice->status }}</span>
                                    @elseif($invoice->status == 'Expiré')
                                        <span class="text-red-600">{{ $invoice->status }}</span>
                                    @elseif($invoice->status == 'Payé')
                                        <span class="text-green-600">{{ $invoice->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput2 = document.getElementById('searchInput2');
            const tableBody = document.getElementById('InvoicesTable').getElementsByTagName('tbody')[0];
            const rows = tableBody.getElementsByTagName('tr');

            // Ajout d'un écouteur d'événement pour détecter chaque frappe dans la barre de recherche
            searchInput2.addEventListener('keyup', function() {
                const filter = searchInput2.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');

                    let match = false;
                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent || cells[j].innerText;

                        if (cellText.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }

                    if (match) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection
