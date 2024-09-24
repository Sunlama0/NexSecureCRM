@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Modifier le Devis #{{ $quote->quote_number }}</h2>

            <form action="{{ route('quotes.update', $quote->id) }}" method="POST" id="quoteForm">
                @csrf
                @method('PUT')

                <!-- Sélection du client -->
                <div class="mb-4">
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" id="client_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $quote->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Numéro de devis -->
                <div class="mb-4">
                    <label for="quote_number" class="block text-sm font-medium text-gray-700">Numéro du devis</label>
                    <input type="text" name="quote_number" id="quote_number" value="{{ $quote->quote_number }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <!-- Date du devis -->
                <div class="mb-4">
                    <label for="quote_date" class="block text-sm font-medium text-gray-700">Date du devis</label>
                    <input type="date" name="quote_date" id="quote_date"
                        value="{{ \Carbon\Carbon::parse($quote->quote_date)->format('Y-m-d') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <!-- Date d'expiration -->
                <div class="mb-4">
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                    <input type="date" name="expiration_date" id="expiration_date"
                        value="{{ \Carbon\Carbon::parse($quote->expiration_date)->format('Y-m-d') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <!-- Objet du devis -->
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Objet</label>
                    <input type="text" name="subject" id="subject" value="{{ $quote->subject }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <!-- Statut du devis -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut du devis</label>
                    <select name="status" id="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Brouillon" {{ $quote->status == 'Brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="Envoyé" {{ $quote->status == 'Envoyé' ? 'selected' : '' }}>Envoyé</option>
                        <option value="Facturé" {{ $quote->status == 'Facturé' ? 'selected' : '' }}>Facturé</option>
                        <option value="Expiré" {{ $quote->status == 'Expiré' ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>

                <!-- Section de gestion des articles -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Articles du devis</h3>

                    <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taxe (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remise (€)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($quote->items as $index => $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="1" required>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" name="items[{{ $index }}][rate]" value="{{ $item->rate }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" required>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" name="items[{{ $index }}][tax]" value="{{ $item->tax }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" required>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" name="items[{{ $index }}][discount]" value="{{ $item->discount }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0">
                                    </td>
                                    <td class="px-6 py-4 total">
                                        {{ number_format($item->total, 2) }} €
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button" class="text-red-600 hover:text-red-900" onclick="removeRow(this)">Supprimer</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" id="addRow">
                        Ajouter un article
                    </button>
                </div>

                <!-- Résumé financier -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold">Résumé :</h3>
                    <p>Sous-total : <span id="subtotal">{{ number_format($quote->subtotal, 2) }} €</span></p>
                    <p>Taxe : <span id="total_tax">{{ number_format($quote->total_tax, 2) }} €</span></p>
                    <p>Remise : <span id="total_discount">{{ number_format($quote->total_discount, 2) }} €</span></p>
                    <p class="text-lg font-bold">Total : <span id="total">{{ number_format($quote->total, 2) }} €</span></p>
                </div>

                <!-- Bouton de soumission -->
                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Mettre à jour le devis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts pour la gestion dynamique des articles et le calcul des totaux -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ajouter un nouvel article
            document.getElementById('addRow').addEventListener('click', function () {
                const tableBody = document.querySelector('#itemsTable tbody');
                const index = tableBody.rows.length;

                const newRow = `
                    <tr>
                        <td class="px-6 py-4">
                            <input type="text" name="items[${index}][description]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" name="items[${index}][quantity]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="1" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" name="items[${index}][rate]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" name="items[${index}][tax]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" name="items[${index}][discount]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0">
                        </td>
                        <td class="px-6 py-4 total">0.00 €</td>
                        <td class="px-6 py-4">
                            <button type="button" class="text-red-600 hover:text-red-900" onclick="removeRow(this)">Supprimer</button>
                        </td>
                    </tr>
                `;

                tableBody.insertAdjacentHTML('beforeend', newRow);
            });

            // Supprimer une ligne
            window.removeRow = function (btn) {
                btn.closest('tr').remove();
                calculateTotals();
            };

            // Calculer les totaux automatiquement
            document.getElementById('quoteForm').addEventListener('input', function () {
                calculateTotals();
            });

            function calculateTotals() {
                let subtotal = 0, totalTax = 0, totalDiscount = 0, total = 0;

                document.querySelectorAll('#itemsTable tbody tr').forEach(function (row) {
                    const quantity = parseFloat(row.querySelector('[name*="[quantity]"]').value) || 0;
                    const rate = parseFloat(row.querySelector('[name*="[rate]"]').value) || 0;
                    const tax = parseFloat(row.querySelector('[name*="[tax]"]').value) || 0;
                    const discount = parseFloat(row.querySelector('[name*="[discount]"]').value) || 0;

                    const itemTotal = (quantity * rate) + ((quantity * rate) * (tax / 100)) - discount;
                    subtotal += quantity * rate;
                    totalTax += (quantity * rate) * (tax / 100);
                    totalDiscount += discount;

                    row.querySelector('.total').innerText = itemTotal.toFixed(2) + ' €';
                });

                total = subtotal + totalTax - totalDiscount;

                // Mettre à jour le résumé
                document.getElementById('subtotal').innerText = subtotal.toFixed(2) + ' €';
                document.getElementById('total_tax').innerText = totalTax.toFixed(2) + ' €';
                document.getElementById('total_discount').innerText = totalDiscount.toFixed(2) + ' €';
                document.getElementById('total').innerText = total.toFixed(2) + ' €';
            }
        });
    </script>
@endsection
