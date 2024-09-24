@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Liste des sociétés</h2>

        <!-- Lien pour créer une nouvelle société -->
        <div class="mb-4">
            <a href="{{ route('companies.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Créer une société</a>
        </div>

        <!-- Tableau des sociétés -->
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Secteur d'activité</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact principal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($companies as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company->structure }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company->address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company->sector }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $company->contact_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- Bouton Modifier -->
                            <a href="{{ route('companies.edit', $company->id) }}" class="text-indigo-600 hover:text-indigo-900 px-4 py-2 border border-indigo-600 rounded-md hover:bg-indigo-100">
                                Modifier
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
