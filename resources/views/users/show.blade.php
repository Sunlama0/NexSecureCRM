@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Détails de l'utilisateur</h2>

        <!-- Détails de l'utilisateur -->
        <div class="mb-4">
            <strong>Nom :</strong>
            <p>{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <strong>Email :</strong>
            <p>{{ $user->email }}</p>
        </div>

        <div class="mb-4">
            <strong>Société :</strong>
            <p>{{ $user->company ? $user->company->name : 'Non assigné' }}</p>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Retour à la liste</a>
            <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 ml-2">Modifier</a>
        </div>
    </div>
</div>
@endsection
