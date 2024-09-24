@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Créer une nouvelle facture</h2>

            <!-- Message d'erreur pour les champs manquants -->
            <div id="errorMessage"
                class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">Veuillez remplir tous les champs obligatoires avant de passer à l'étape
                    suivante.</span>
            </div>

            <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST">
                @csrf

                <!-- Étape 1: Informations Générales -->
                <div id="step1" class="step">
                    <h3 class="text-xl font-semibold mb-6">Étape 1 : Informations Générales</h3>

                    <!-- Client -->
                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="client_id" id="client_id"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">-- Sélectionnez un client --</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Numéro de facture (généré automatiquement) -->
                    <div class="mb-4">
                        <label for="invoice_number" class="block text-sm font-medium text-gray-700">Numéro de facture</label>
                        <input type="text" name="invoice_number" id="invoice_number" value="{{ $invoiceNumber }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            readonly>
                    </div>

                    <!-- Date de la facture -->
                    <div class="mb-4">
                        <label for="invoice_date" class="block text-sm font-medium text-gray-700">Date de la facture</label>
                        <input type="date" name="invoice_date" id="invoice_date"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Date d'échéance -->
                    <div class="mb-4">
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                        <input type="date" name="due_date" id="due_date"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Objet -->
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Objet</label>
                        <input type="text" name="subject" id="subject"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Statut -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut de la Facture</label>
                        <select name="status" id="status"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="Brouillon">Brouillon</option>
                            <option value="Envoyé">Envoyé</option>
                            <option value="Expiré">Expiré</option>
                            <option value="Payé">Payé</option>
                        </select>
                    </div>

                    <!-- Bouton suivant -->
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="nextStep"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Suivant
                        </button>
                    </div>
                </div>

                <!-- Étape 2: Détails des Articles -->
                <div id="step2" class="step hidden">
                    <h3 class="text-xl font-semibold mb-6">Étape 2 : Détails des Articles</h3>

                    <!-- Tableau des articles -->
                    <table class="min-w-full table-auto mb-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Quantité</th>
                                <th class="px-4 py-2">Taux (€)</th>
                                <th class="px-4 py-2">Taxe (%)</th>
                                <th class="px-4 py-2">Remise (€)</th>
                                <th class="px-4 py-2">Total Article (€)</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsWrapper">
                            <tr class="article-row">
                                <td class="px-4 py-2">
                                    <input type="text" name="items[0][description]"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" name="items[0][quantity]" min="1"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm quantity"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="items[0][rate]" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rate"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="items[0][tax]" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm tax"
                                        required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="items[0][discount]" min="0"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm discount">
                                </td>
                                <td class="px-4 py-2 total-article">0.00 €</td>
                                <td class="px-4 py-2">
                                    <button type="button"
                                        class="text-red-600 hover:text-red-900 remove-article">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Bouton ajouter un article -->
                    <button type="button" id="addArticle" class="mb-4 text-indigo-600 hover:text-indigo-900">Ajouter un
                        article</button>

                    <!-- Résumé des montants -->
                    <div class="flex justify-end space-x-4">
                        <div class="text-right">
                            <p class="text-sm">Sous-total (Total HT) : <span id="subtotal">0.00 €</span></p>
                            <p class="text-sm">Taxe (€) : <span id="totalTax">0.00 €</span></p>
                            <p class="text-sm">Remise (€) : <span id="totalDiscount">0.00 €</span></p>
                            <p class="text-lg font-bold">Total (TTC) : <span id="totalTTC">0.00 €</span></p>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="mt-6 flex justify-between">
                        <button type="button" id="prevStep"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Retour
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Créer la facture
                        </button>
                    </div>
                </div>

                <!-- Barre de progression en bas -->
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <!-- Étape 1 -->
                        <div class="flex-1">
                            <div class="text-center">
                                <span id="step1Label" class="text-sm font-medium text-indigo-600">Étape 1</span>
                                <div id="step1Line" class="w-full border-t-4 border-indigo-600 mt-1"></div>
                                <p class="text-xs mt-1 text-indigo-600">Informations Générales</p>
                            </div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="flex-1">
                            <div class="text-center">
                                <span id="step2Label" class="text-sm font-medium text-gray-400">Étape 2</span>
                                <div id="step2Line" class="w-full border-t-4 border-gray-300 mt-1"></div>
                                <p class="text-xs mt-1 text-gray-400">Détails des Articles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour gérer les étapes et la validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des étapes
            const nextStep = document.getElementById('nextStep');
            const prevStep = document.getElementById('prevStep');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step1Label = document.getElementById('step1Label');
            const step1Line = document.getElementById('step1Line');
            const step2Label = document.getElementById('step2Label');
            const step2Line = document.getElementById('step2Line');
            const errorMessage = document.getElementById('errorMessage');
            const requiredFields = ['client_id', 'invoice_number', 'invoice_date', 'due_date', 'subject'];

            // Gérer le passage à l'étape 2
            nextStep.addEventListener('click', function() {
                let valid = true;
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value) {
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                if (!valid) {
                    errorMessage.classList.remove('hidden');
                } else {
                    errorMessage.classList.add('hidden');
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    step1Label.classList.remove('text-indigo-600');
                    step1Label.classList.add('text-gray-400');
                    step1Line.classList.remove('border-indigo-600');
                    step1Line.classList.add('border-gray-300');
                    step2Label.classList.remove('text-gray-400');
                    step2Label.classList.add('text-indigo-600');
                    step2Line.classList.remove('border-gray-300');
                    step2Line.classList.add('border-indigo-600');
                }
            });

            // Retour à l'étape 1
            prevStep.addEventListener('click', function() {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                step2Label.classList.remove('text-indigo-600');
                step2Label.classList.add('text-gray-400');
                step2Line.classList.remove('border-indigo-600');
                step2Line.classList.add('border-gray-300');
                step1Label.classList.remove('text-gray-400');
                step1Label.classList.add('text-indigo-600');
                step1Line.classList.remove('border-gray-300');
                step1Line.classList.add('border-indigo-600');
            });

            // Gestion des articles
            let articleCount = 1;
            const itemsWrapper = document.getElementById('itemsWrapper');
            const addArticle = document.getElementById('addArticle');

            addArticle.addEventListener('click', function() {
                const newRow = `
                    <tr class="article-row">
                        <td class="px-4 py-2">
                            <input type="text" name="items[${articleCount}][description]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${articleCount}][quantity]" min="1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm quantity" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${articleCount}][rate]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rate" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${articleCount}][tax]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm tax" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[${articleCount}][discount]" step="0.01" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm discount">
                        </td>
                        <td class="px-4 py-2 total-article">0.00 €</td>
                        <td class="px-4 py-2">
                            <button type="button" class="text-red-600 hover:text-red-900 remove-article">Supprimer</button>
                        </td>
                    </tr>
                `;
                itemsWrapper.insertAdjacentHTML('beforeend', newRow);
                articleCount++;
            });

            itemsWrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-article')) {
                    e.target.closest('.article-row').remove();
                    calculateTotals();
                }
            });

            // Calcul des totaux
            function calculateTotals() {
                let subtotal = 0;
                let totalTax = 0;
                let totalDiscount = 0;

                const rows = document.querySelectorAll('.article-row');
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

            // Écouter les changements dans les champs d'articles pour recalculer les totaux
            itemsWrapper.addEventListener('input', function(e) {
                if (['quantity', 'rate', 'tax', 'discount'].includes(e.target.className.split(' ').find(
                        cls => ['quantity', 'rate', 'tax', 'discount'].includes(cls)))) {
                    calculateTotals();
                }
            });
        });
    </script>
@endsection
