@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Paramètres de la société</h2>

        <!-- Section Informations Générales -->
        <div class="border-gray-200 pb-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Informations Générales</h3>
            <form action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Logo de la société -->
                <div class="flex items-center mb-4">
                    <label class="block text-sm font-medium text-gray-700 mr-4">Logo de la société</label>
                    <div class="flex items-center">
                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo de la société" class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-400">Aucun logo</span>
                            @endif
                        </span>
                        <input type="file" name="logo" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none">
                    </div>
                </div>

                <!-- Nom de la société -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nom de la société</label>
                    <input type="text" name="name" value="{{ $company->name }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Structure -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Structure</label>
                    <input type="text" name="structure" value="{{ $company->structure }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Numéro de TVA -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Numéro de TVA</label>
                    <input type="text" name="tva_number" value="{{ $company->tva_number }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- SIRET -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">SIRET</label>
                    <input type="text" name="siret" value="{{ $company->siret }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- SIREN -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">SIREN</label>
                    <input type="text" name="siren" value="{{ $company->siren }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Adresse -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input type="text" name="address" value="{{ $company->address }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Bouton de mise à jour -->
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
