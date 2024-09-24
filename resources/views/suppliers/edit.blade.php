@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-semibold mb-4">Modifier le fournisseur</h1>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom du fournisseur</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $supplier->name }}" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700">
                        Mettre à jour
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="ml-4 px-4 py-2 bg-gray-600 text-white rounded-lg shadow-sm hover:bg-gray-700">
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
