@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Liste des références</h2>

        <!-- Lien vers la création d'une nouvelle catégorie -->
        <div class="mb-4 flex justify-end">
            <a href="{{ route('device_identifiers.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Créer une références</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tableau des références -->
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($deviceIdentifiers as $deviceIdentifier)
                    <tr>
                        <td class="px-6 py-4">{{ $deviceIdentifier->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('device_identifiers.edit', $deviceIdentifier->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <form action="{{ route('device_identifiers.destroy', $deviceIdentifier->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette références ?');">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
