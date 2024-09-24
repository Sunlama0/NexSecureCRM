@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-center">Créer une nouvelle société</h2>

            <!-- Formulaire multi-étapes -->
            <form id="company-form" action="{{ route('companies.store') }}" method="POST">
                @csrf

                <!-- Étape 1 : Informations générales de la société -->
                <div id="step1">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la société</label>
                        <input type="text" id="name" name="name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('name') }}" required>
                    </div>

                    <!-- Champ Structure avec select -->
                    <div class="mb-4">
                        <label for="structure" class="block text-sm font-medium text-gray-700">Structure (Type
                            d'entreprise)</label>
                        <select id="structure" name="structure"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">Sélectionnez la structure</option>
                            <option value="SARL" {{ old('structure') == 'SARL' ? 'selected' : '' }}>SARL</option>
                            <option value="SAS" {{ old('structure') == 'SAS' ? 'selected' : '' }}>SAS</option>
                            <option value="Auto-entrepreneur"
                                {{ old('structure') == 'Auto-entrepreneur' ? 'selected' : '' }}>Auto-entrepreneur</option>
                            <option value="EURL" {{ old('structure') == 'EURL' ? 'selected' : '' }}>EURL</option>
                            <option value="SA" {{ old('structure') == 'SA' ? 'selected' : '' }}>SA</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" id="address" name="address"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('address') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="sector" class="block text-sm font-medium text-gray-700">Secteur d'activité</label>
                        <input type="text" id="sector" name="sector"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('sector') }}" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="button"
                            class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            id="nextStep">
                            Suivant
                        </button>
                    </div>
                </div>

                <!-- Étape 2 : Contact principal -->
                <div id="step2" class="hidden">
                    <div class="mb-4">
                        <label for="contact_name" class="block text-sm font-medium text-gray-700">Nom du contact
                            principal</label>
                        <input type="text" id="contact_name" name="contact_name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('contact_name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email du contact
                            principal</label>
                        <input type="email" id="contact_email" name="contact_email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('contact_email') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Téléphone du contact
                            principal</label>
                        <input type="text" id="contact_phone" name="contact_phone"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('contact_phone') }}" required>
                    </div>

                    <div class="flex justify-between">
                        <button type="button"
                            class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            id="prevStep">
                            Retour
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Créer la société
                        </button>
                    </div>
                </div>
            </form>

            <!-- Barre de progression améliorée -->
            <div class="mt-8">
                <div class="flex justify-center items-center">
                    <!-- Étape 1 -->
                    <div class="flex items-center">
                        <div id="progress-step-1"
                            class="h-12 w-12 flex items-center justify-center rounded-full bg-indigo-600 text-white text-lg font-bold">
                            01
                        </div>
                        <div class="ml-2 text-gray-900 font-medium">Informations générales</div>
                    </div>

                    <!-- Ligne entre les étapes -->
                    <div class="w-32 h-1 bg-gray-300 mx-4"></div>

                    <!-- Étape 2 -->
                    <div class="flex items-center">
                        <div id="progress-step-2"
                            class="h-12 w-12 flex items-center justify-center rounded-full bg-gray-300 text-gray-500 text-lg font-bold">
                            02
                        </div>
                        <div class="ml-2 text-gray-500 font-medium">Contact principal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextButton = document.getElementById('nextStep');
            const prevButton = document.getElementById('prevStep');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const progressStep1 = document.getElementById('progress-step-1');
            const progressStep2 = document.getElementById('progress-step-2');

            // Fonction pour valider les champs du formulaire avant de passer à l'étape suivante
            function validateStep1() {
                let valid = true;
                document.querySelectorAll('#step1 input[required], #step1 select[required]').forEach(function(
                input) {
                    if (!input.value) {
                        valid = false;
                        input.classList.add('border-red-500');
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });
                return valid;
            }

            // Gestion de l'événement du bouton "Suivant"
            nextButton.addEventListener('click', function() {
                if (validateStep1()) {
                    // Masquer l'étape 1 et afficher l'étape 2
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');

                    // Mettre à jour la barre de progression
                    progressStep1.classList.remove('bg-indigo-600');
                    progressStep1.classList.add('bg-indigo-300');
                    progressStep2.classList.remove('bg-gray-300');
                    progressStep2.classList.add('bg-indigo-600');
                    progressStep2.classList.remove('text-gray-500');
                    progressStep2.classList.add('text-white');
                }
            });

            // Gestion de l'événement du bouton "Retour"
            prevButton.addEventListener('click', function() {
                // Masquer l'étape 2 et afficher l'étape 1
                step2.classList.add('hidden');
                step1.classList.remove('hidden');

                // Mettre à jour la barre de progression
                progressStep1.classList.add('bg-indigo-600');
                progressStep1.classList.remove('bg-indigo-300');
                progressStep2.classList.add('bg-gray-300');
                progressStep2.classList.remove('bg-indigo-600');
                progressStep2.classList.remove('text-white');
                progressStep2.classList.add('text-gray-500');
            });
        });
    </script>
@endsection
