@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">

            <!-- Formulaire principal -->
            <form id="material-edit-form" action="{{ route('materials.update', $material->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Étape 1 : Informations Matériels -->
                <div id="step1" class="step">
                    <h3 class="text-2xl font-semibold mb-4">Informations Matériels</h3>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Référence du matériel -->
                        <!-- Référence -->
                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                            <input type="text" name="reference" id="reference"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $material->reference }}" readonly>
                        </div>

                        <!-- Identifiant de l'appareil (Device Identifier) -->
                        <div class="mb-4">
                            <label for="device_identifier_id" class="block text-sm font-medium text-gray-700">Référence produit</label>
                            <select name="device_identifier_id" id="device_identifier_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="" disabled>Choisissez une référence produit</option>
                                @foreach ($deviceIdentifiers as $deviceIdentifier)
                                    <option value="{{ $deviceIdentifier->id }}"
                                        {{ $material->device_identifier_id == $deviceIdentifier->id ? 'selected' : '' }}>
                                        {{ $deviceIdentifier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nom de l'appareil -->
                        <div class="mb-4">
                            <label for="device_name" class="block text-sm font-medium text-gray-700">Nom de
                                l'appareil</label>
                            <input type="text" name="device_name" id="device_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $material->device_name }}" required>
                        </div>

                        <!-- Numéro de série -->
                        <div class="mb-4">
                            <label for="serial_number" class="block text-sm font-medium text-gray-700">Numéro de
                                série</label>
                            <input type="text" name="serial_number" id="serial_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $material->serial_number }}" required>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                            <select name="category_id" id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $material->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date d'acquisition -->
                        <div class="mb-4">
                            <label for="acquisition_date" class="block text-sm font-medium text-gray-700">Date
                                d'acquisition</label>
                            <input type="date" name="acquisition_date" id="acquisition_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $material->acquisition_date }}" required>
                        </div>

                        <!-- Fournisseur -->
                        <div class="mb-4">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                            <select name="supplier_id" id="supplier_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="" disabled>Choisissez un fournisseur</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $material->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Coût -->
                        <div class="mb-4">
                            <label for="cost" class="block text-sm font-medium text-gray-700">Coût</label>
                            <input type="number" step="0.01" name="cost" id="cost"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $material->cost }}">
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $material->description }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            id="next1">
                            Suivant<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 2 : Gestion du Matériels (incluant la sortie de stock) -->
                <div id="step2" class="step hidden">
                    <h3 class="text-2xl font-semibold mb-4">Gestion du Matériels</h3>

                    <h4 class="text-xl font-semibold mt-8 mb-4">Attribution du matériels</h4>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Prénom du collaborateur -->
                        <div class="mb-6">
                            <label for="collaborator_firstname" class="block text-sm font-medium text-gray-700">Prénom du
                                collaborateur</label>
                            <input type="text" name="collaborator_firstname" id="collaborator_firstname"
                                class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $material->collaborator_firstname }}">
                        </div>

                        <!-- Nom du collaborateur -->
                        <div class="mb-6">
                            <label for="collaborator_lastname" class="block text-sm font-medium text-gray-700">Nom du
                                collaborateur</label>
                            <input type="text" name="collaborator_lastname" id="collaborator_lastname"
                                class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $material->collaborator_lastname }}">
                        </div>

                        <!-- Poste du collaborateur -->
                        <div class="mb-6">
                            <label for="collaborator_position"
                                class="block text-sm font-medium text-gray-700">Poste</label>
                            <input type="text" name="collaborator_position" id="collaborator_position"
                                class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $material->collaborator_position }}">
                        </div>

                        <!-- Date d'attribution -->
                        <div class="mb-6">
                            <label for="assigned_date" class="block text-sm font-medium text-gray-700">Date
                                d'attribution</label>
                            <input type="date" name="assigned_date" id="assigned_date"
                                class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $material->assigned_date }}">
                        </div>

                        <!-- Case à cocher pour remettre en stock -->
                        <div class="mb-6 flex items-center">
                            <input type="checkbox" name="return_to_stock" id="return_to_stock" value="1"
                                class="h-6 w-6 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="return_to_stock" class="ml-2 block text-sm font-medium text-gray-700">
                                Remettre ce matériel en stock
                            </label>
                        </div>
                    </div>

                    <!-- Gestion de la sortie de stock -->
                    <h4 class="text-xl font-semibold mt-8 mb-4">Sortie de stock</h4>

                    <div class="mb-6">
                        <label for="out_of_stock" class="block text-sm font-medium text-gray-700">Sortir le matériel du
                            stock</label>
                        <select name="out_of_stock" id="out_of_stock"
                            class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="non" {{ !$material->out_of_stock ? 'selected' : '' }}>Non</option>
                            <option value="oui" {{ $material->out_of_stock ? 'selected' : '' }}>Oui</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="out_of_stock_notes" class="block text-sm font-medium text-gray-700">Notes pour la
                            sortie de stock</label>
                        <textarea name="out_of_stock_notes" id="out_of_stock_notes" rows="4"
                            class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $material->out_of_stock_notes }}</textarea>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            id="prev2">
                            <i class="fas fa-arrow-left mr-2"></i>Retour
                        </button>

                        <button type="button"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            id="next2">
                            Suivant<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Historique du Matériel -->
                <div id="step3" class="step hidden">
                    <h3 class="text-2xl font-semibold mb-4">Historique du Matériels</h3>

                    <!-- Champ de recherche pour filtrer l'historique -->
                    <div class="mb-4 flex justify-between">
                        <h4 class="text-lg font-semibold mb-2"></h4>
                        <div class="flex space-x-4">
                            <!-- Recherche par texte -->
                            <input type="text" name="search" id="search" placeholder="Rechercher..."
                                value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-lg shadow-sm border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Tableau de l'historique -->
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-4 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                                    Date</th>
                                <th
                                    class="px-4 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                                    Statut</th>
                                <th
                                    class="px-4 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                                    Modifié par</th>
                                <th
                                    class="px-4 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                                    Notes</th>
                                {{-- <th
                                    class="px-4 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                                    Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if ($histories->count() > 0)
                                @foreach ($histories as $history)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $history->created_at }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $history->status }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $history->changed_by }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $history->notes }}</td>
                                        {{-- <td class="px-4 py-3 text-sm">
                                            <form action="{{ route('history.destroy', $history->id) }}" method="POST"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet historique ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr> --}}
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-600">Aucun
                                        historique trouvé pour ce matériel.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>

                    <!-- Boutons Retour et Suivant -->
                    <div class="flex justify-between mt-6">
                        <!-- Bouton Retour -->
                        <button type="button"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            id="prev3">
                            <i class="fas fa-arrow-left mr-2"></i>Retour
                        </button>

                        <!-- Bouton Suivant -->
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center">
                            Mettre à jour <i class="fas fa-save ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Barre de progression sous le formulaire -->
            <div class="mt-8">
                <div class="flex justify-between items-center">
                    <!-- Étape 1 -->
                    <div class="flex-1">
                        <div class="text-center">
                            <span id="step1Label" class="text-sm font-medium text-gray-400">Étape 1</span>
                            <div id="step1Line" class="w-full border-t-4 border-gray-300 mt-1"></div>
                            <p class="text-xs mt-1">Informations Matériels</p>
                        </div>
                    </div>

                    <!-- Étape 2 -->
                    <div class="flex-1">
                        <div class="text-center">
                            <span id="step2Label" class="text-sm font-medium text-gray-400">Étape 2</span>
                            <div id="step2Line" class="w-full border-t-4 border-gray-300 mt-1"></div>
                            <p class="text-xs mt-1">Gestion du matériels</p>
                        </div>
                    </div>

                    <!-- Étape 3 -->
                    <div class="flex-1">
                        <div class="text-center">
                            <span id="step3Label" class="text-sm font-medium text-gray-400">Étape 3</span>
                            <div id="step3Line" class="w-full border-t-4 border-gray-300 mt-1"></div>
                            <p class="text-xs mt-1">Historique du matériels</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');

            const next1 = document.getElementById('next1');
            const next2 = document.getElementById('next2');
            const prev2 = document.getElementById('prev2');
            const prev3 = document.getElementById('prev3');

            // Elements pour mettre à jour la barre de progression
            const step1Label = document.getElementById('step1Label');
            const step2Label = document.getElementById('step2Label');
            const step3Label = document.getElementById('step3Label');
            const step1Line = document.getElementById('step1Line');
            const step2Line = document.getElementById('step2Line');
            const step3Line = document.getElementById('step3Line');

            // Gestion des étapes
            next1.addEventListener('click', function() {
                switchStep(2);
            });

            next2.addEventListener('click', function() {
                switchStep(3);
            });

            prev2.addEventListener('click', function() {
                switchStep(1);
            });

            prev3.addEventListener('click', function() {
                switchStep(2);
            });

            // Gestion des clics sur la barre de progression
            step1Label.addEventListener('click', function() {
                switchStep(1);
            });

            step2Label.addEventListener('click', function() {
                switchStep(2);
            });

            step3Label.addEventListener('click', function() {
                switchStep(3);
            });

            // Fonction pour basculer entre les étapes
            function switchStep(step) {
                if (step === 1) {
                    step1.classList.remove('hidden');
                    step2.classList.add('hidden');
                    step3.classList.add('hidden');
                    updateProgress(1);
                } else if (step === 2) {
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    step3.classList.add('hidden');
                    updateProgress(2);
                } else if (step === 3) {
                    step1.classList.add('hidden');
                    step2.classList.add('hidden');
                    step3.classList.remove('hidden');
                    updateProgress(3);
                }

                // Sauvegarder l'étape actuelle dans l'URL
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('step', step);
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
            }

            // Mise à jour de la barre de progression
            function updateProgress(step) {
                // Reset all steps
                step1Label.classList.replace('text-indigo-600', 'text-gray-400');
                step2Label.classList.replace('text-indigo-600', 'text-gray-400');
                step3Label.classList.replace('text-indigo-600', 'text-gray-400');
                step1Line.classList.replace('border-indigo-600', 'border-gray-300');
                step2Line.classList.replace('border-indigo-600', 'border-gray-300');
                step3Line.classList.replace('border-indigo-600', 'border-gray-300');

                // Highlight current step
                if (step === 1) {
                    step1Label.classList.replace('text-gray-400', 'text-indigo-600');
                    step1Line.classList.replace('border-gray-300', 'border-indigo-600');
                } else if (step === 2) {
                    step2Label.classList.replace('text-gray-400', 'text-indigo-600');
                    step2Line.classList.replace('border-gray-300', 'border-indigo-600');
                } else if (step === 3) {
                    step3Label.classList.replace('text-gray-400', 'text-indigo-600');
                    step3Line.classList.replace('border-gray-300', 'border-indigo-600');
                }
            }

            // Restaurer l'étape actuelle à partir de l'URL lors du chargement de la page
            const urlParams = new URLSearchParams(window.location.search);
            const currentStep = urlParams.get('step') || 1;
            switchStep(parseInt(currentStep));

            // Gestion du filtrage
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    const searchValue = searchInput.value;
                    applyFilter(searchValue);
                }
            });

            function applyFilter(filterValue) {
                const urlParams = new URLSearchParams(window.location.search);
                if (filterValue) {
                    urlParams.set('search', filterValue);
                } else {
                    urlParams.delete('search');
                }
                urlParams.set('step', 3); // Maintenir l'étape 3
                window.location.search = urlParams.toString();
            }

            // Gestion de la suppression des éléments d'historique
            const deleteButtons = document.querySelectorAll('.delete-history');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir supprimer cet historique ?')) {
                        const form = button.closest('form');
                        form.submit();
                    }
                });
            });
        });
    </script>

@endsection
