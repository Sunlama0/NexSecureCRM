@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier la Facture #{{ $invoice->invoice_number }}</h2>

            <!-- Affichage des messages d'erreur -->
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Formulaire de modification de la facture -->
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Client -->
                <div class="mb-4">
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" id="client_id" required
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Numéro de facture (lecture seule) -->
                <div class="mb-4">
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Numéro de facture</label>
                    <input type="text" name="invoice_number" id="invoice_number" value="{{ $invoice->invoice_number }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        readonly>
                </div>

                <!-- Date de la facture -->
                <div class="mb-4">
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Date de la facture</label>
                    <input type="date" name="invoice_date" id="invoice_date"
                        value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Date d'échéance -->
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                    <input type="date" name="due_date" id="due_date"
                        value="{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Objet -->
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Objet</label>
                    <input type="text" name="subject" id="subject" value="{{ $invoice->subject }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Méthode de paiement -->
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                    <input type="text" name="payment_method" id="payment_method"
                        value="{{ old('payment_method', $invoice->payment_method ?? '') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Carte, Virement, etc.">
                </div>

                <!-- Statut -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut de la facture</label>
                    <select name="status" id="status"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Brouillon" {{ $invoice->status == 'Brouillon' ? 'selected' : '' }}>Brouillon
                        </option>
                        <option value="Envoyé" {{ $invoice->status == 'Envoyé' ? 'selected' : '' }}>Envoyé</option>
                        <option value="Expiré" {{ $invoice->status == 'Expiré' ? 'selected' : '' }}>Expiré</option>
                        <option value="Payé" {{ $invoice->status == 'Payé' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>

                <!-- Articles de la facture -->
                <h3 class="text-xl font-semibold mb-4">Articles de la facture</h3>
                <table class="min-w-full mb-4 bg-white rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600">Description</th>
                            <th class="px-4 py-2 text-left text-gray-600">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-600">Taux</th>
                            <th class="px-4 py-2 text-left text-gray-600">Taxe (%)</th>
                            <th class="px-4 py-2 text-left text-gray-600">Remise (€)</th>
                            <th class="px-4 py-2 text-left text-gray-600">Total</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="invoice-items">
                        @foreach ($invoice->items as $index => $item)
                            <tr class="item-row">
                                <td class="px-4 py-2">
                                    <input type="text" name="items[{{ $index }}][description]"
                                        value="{{ $item->description }}"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="items[{{ $index }}][quantity]"
                                        value="{{ $item->quantity }}" min="1"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm quantity"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="items[{{ $index }}][rate]" step="0.01"
                                        value="{{ $item->rate }}" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rate"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="items[{{ $index }}][tax]" step="0.01"
                                        value="{{ $item->tax }}" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm tax"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="items[{{ $index }}][discount]" step="0.01"
                                        value="{{ $item->discount }}" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm discount">
                                </td>
                                <td class="px-4 py-2 total-article">
                                    {{ number_format($item->total, 2) }} €
                                </td>
                                <td class="px-4 py-2">
                                    <button type="button"
                                        class="text-red-600 hover:text-red-900 remove-item">Supprimer</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Bouton pour ajouter un nouvel article -->
                <button type="button" id="addItem" class="mb-4 text-indigo-600 hover:text-indigo-900">Ajouter un
                    article</button>

                <!-- Résumé des montants -->
                <div class="flex justify-end space-x-4">
                    <div class="text-right">
                        <p class="text-sm">Sous-total (Total HT) : <span
                                id="subtotal">{{ number_format($invoice->subtotal, 2) }} €</span></p>
                        <p class="text-sm">Taxe (€) : <span id="totalTax">{{ number_format($invoice->total_tax, 2) }}
                                €</span></p>
                        <p class="text-sm">Remise (€) : <span
                                id="totalDiscount">{{ number_format($invoice->total_discount, 2) }} €</span></p>
                        <p class="text-lg font-bold">Total (TTC) : <span
                                id="totalTTC">{{ number_format($invoice->total, 2) }} €</span></p>
                    </div>
                </div>

                <!-- Boutons de validation -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Mettre à jour la
                        facture</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour ajouter/supprimer des articles et actualiser les totaux en temps réel -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = {{ $invoice->items->count() }};
            const itemsWrapper = document.getElementById('invoice-items');
            const addItem = document.getElementById('addItem');

            // Ajouter un nouvel article
            addItem.addEventListener('click', function() {
                const newRow = `
                    <tr class="item-row">
                        <td class="px-4 py-2">
                            <input type="text" name="items[${itemCount}][description]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${itemCount}][quantity]" min="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm quantity" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${itemCount}][rate]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rate" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${itemCount}][tax]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm tax" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${itemCount}][discount]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm discount">
                        </td>
                        <td class="px-4 py-2 total-article">0.00 €</td>
                        <td class="px-4 py-2">
                            <button type="button" class="text-red-600 hover:text-red-900 remove-item">Supprimer</button>
                        </td>
                    </tr>
                `;
                itemsWrapper.insertAdjacentHTML('beforeend', newRow);
                itemCount++;
                updateTotals();
            });

            // Supprimer un article
            itemsWrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.item-row').remove();
                    updateTotals();
                }
            });

            // Actualiser les totaux lorsque les articles sont modifiés
            itemsWrapper.addEventListener('input', function(e) {
                if (['quantity', 'rate', 'tax', 'discount'].includes(e.target.classList[0])) {
                    updateTotals();
                }
            });

            // Fonction pour actualiser les totaux en temps réel
            function updateTotals() {
                let subtotal = 0;
                let totalTax = 0;
                let totalDiscount = 0;

                const rows = document.querySelectorAll('.item-row');
                rows.forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const rate = parseFloat(row.querySelector('.rate').value) || 0;
                    const tax = parseFloat(row.querySelector('.tax').value) || 0;
                    const discount = parseFloat(row.querySelector('.discount').value) || 0;

                    const totalArticle = (quantity * rate) + ((quantity * rate) * (tax / 100)) - discount;
                    row.querySelector('.total-article').textContent = totalArticle.toFixed(2) + ' €';

                    subtotal += quantity * rate;
                    totalTax += (quantity * rate) * (tax / 100);
                    totalDiscount += discount;
                });

                document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' €';
                document.getElementById('totalTax').textContent = totalTax.toFixed(2) + ' €';
                document.getElementById('totalDiscount').textContent = totalDiscount.toFixed(2) + ' €';
                document.getElementById('totalTTC').textContent = (subtotal + totalTax - totalDiscount).toFixed(2) +
                    ' €';
            }

            // Appeler la fonction pour initialiser les totaux
            updateTotals();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const statusField = document.getElementById('status');
            const paymentMethodField = document.getElementById('payment_method');

            // Ajouter une validation si le statut est "Payé"
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (statusField.value === 'Payé' && !paymentMethodField.value) {
                    event.preventDefault();
                    alert('Veuillez fournir une méthode de paiement pour les factures payées.');
                }
            });
        });
    </script>
@endsection
