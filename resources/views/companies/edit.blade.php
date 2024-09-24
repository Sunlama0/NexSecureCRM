@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Modifier la société</h2>

        <!-- Formulaire de modification de la société -->
        <form action="{{ route('companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nom de la société -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la société</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', $company->name) }}" required>
            </div>

            <!-- Structure de la société -->
            <div class="mb-4">
                <label for="structure" class="block text-sm font-medium text-gray-700">Structure (Type d'entreprise)</label>
                <select id="structure" name="structure" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="SARL" {{ old('structure', $company->structure) == 'SARL' ? 'selected' : '' }}>SARL</option>
                    <option value="SAS" {{ old('structure', $company->structure) == 'SAS' ? 'selected' : '' }}>SAS</option>
                    <option value="Auto-entrepreneur" {{ old('structure', $company->structure) == 'Auto-entrepreneur' ? 'selected' : '' }}>Auto-entrepreneur</option>
                    <option value="EURL" {{ old('structure', $company->structure) == 'EURL' ? 'selected' : '' }}>EURL</option>
                    <option value="SA" {{ old('structure', $company->structure) == 'SA' ? 'selected' : '' }}>SA</option>
                </select>
            </div>

            <!-- Adresse de la société -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" id="address" name="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address', $company->address) }}" required>
            </div>

            <!-- Secteur d'activité -->
            <div class="mb-4">
                <label for="sector" class="block text-sm font-medium text-gray-700">Secteur d'activité</label>
                <input type="text" id="sector" name="sector" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('sector', $company->sector) }}" required>
            </div>

            <!-- Contact principal : Nom -->
            <div class="mb-4">
                <label for="contact_name" class="block text-sm font-medium text-gray-700">Nom du contact principal</label>
                <input type="text" id="contact_name" name="contact_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('contact_name', $company->contact_name) }}" required>
            </div>

            <!-- Contact principal : Email -->
            <div class="mb-4">
                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email du contact principal</label>
                <input type="email" id="contact_email" name="contact_email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('contact_email', $company->contact_email) }}" required>
            </div>

            <!-- Contact principal : Téléphone -->
            <div class="mb-4">
                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Téléphone du contact principal</label>
                <input type="text" id="contact_phone" name="contact_phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('contact_phone', $company->contact_phone) }}" required>
            </div>

            <!-- Bouton pour enregistrer les modifications -->
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
