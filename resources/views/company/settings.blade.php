@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Paramètres de la société</h2>

        <!-- Section Informations Générales -->
        <div class="border-b border-gray-200 pb-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-6">Informations Générales</h3>
            <form action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Logo de la société avec prévisualisation -->
                <div class="flex items-center mb-8">
                    <div class="mr-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo de la société</label>
                        <div class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300 shadow-sm">
                            <img id="logo-preview" src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/nexsecure.png') }}" alt="Logo de la société" class="h-full w-full object-cover">
                        </div>
                    </div>
                    <div class="flex flex-col justify-between">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Changer le logo</label>
                        <input type="file" name="logo" id="logo-upload" accept="image/*" class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none">
                        <small class="text-gray-500 mt-2">Formats acceptés: JPEG, PNG, JPG (Max: 2MB)</small>
                    </div>
                </div>

                <!-- Nom de la société -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Nom de la société</label>
                    <input type="text" name="name" value="{{ $company->name }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Structure -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Structure</label>
                    <input type="text" name="structure" value="{{ $company->structure }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Numéro de TVA -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Numéro de TVA</label>
                    <input type="text" name="tva_number" value="{{ $company->tva_number }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- SIRET -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">SIRET</label>
                    <input type="text" name="siret" value="{{ $company->siret }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- SIREN -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">SIREN</label>
                    <input type="text" name="siren" value="{{ $company->siren }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Adresse -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input type="text" name="address" value="{{ $company->address }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Bouton de mise à jour -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 transition ease-in-out duration-200">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour prévisualiser l'image lors de l'upload -->
<script>
    document.getElementById('logo-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
