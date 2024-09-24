@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Créer un nouveau matériel</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <strong>Erreur !</strong> Veuillez corriger les erreurs ci-dessous :
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('materials.store') }}" method="POST">
            @csrf

            <!-- Référence -->
            <div class="mb-4">
                <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                <input type="text" name="reference" id="reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('reference') }}" required>
            </div>

            <!-- Identifiant de l'appareil -->
            <div class="mb-4">
                <label for="device_identifier_id" class="block text-sm font-medium text-gray-700">Identifiant de l'appareil</label>
                <select name="device_identifier_id" id="device_identifier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Sélectionnez un identifiant</option>
                    @foreach($deviceIdentifiers as $identifier)
                        <option value="{{ $identifier->id }}" {{ old('device_identifier_id') == $identifier->id ? 'selected' : '' }}>
                            {{ $identifier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nom de l'appareil -->
            <div class="mb-4">
                <label for="device_name" class="block text-sm font-medium text-gray-700">Nom de l'appareil</label>
                <input type="text" name="device_name" id="device_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('device_name') }}" required>
            </div>

            <!-- Numéro de série -->
            <div class="mb-4">
                <label for="serial_number" class="block text-sm font-medium text-gray-700">Numéro de série</label>
                <input type="text" name="serial_number" id="serial_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('serial_number') }}" required>
            </div>

            <!-- Catégorie -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date d'acquisition -->
            <div class="mb-4">
                <label for="acquisition_date" class="block text-sm font-medium text-gray-700">Date d'acquisition</label>
                <input type="date" name="acquisition_date" id="acquisition_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('acquisition_date') }}" required>
            </div>

            <!-- Fournisseur -->
            <div class="mb-4">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Sélectionnez un fournisseur</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Coût -->
            <div class="mb-4">
                <label for="cost" class="block text-sm font-medium text-gray-700">Coût</label>
                <input type="number" step="0.01" name="cost" id="cost" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('cost') }}">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm">
                    Créer le matériel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
