@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg p-8">
        <!-- Titre de la page -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vue d'ensemble des stocks</h2>
            <div class="text-gray-600">
                <i class="fas fa-warehouse fa-lg mr-2"></i>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="mb-8 flex justify-between items-center">
            <input type="text" id="search" placeholder="Rechercher une référence..."
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 w-full sm:w-1/3">
            {{-- <button class="ml-4 px-4 py-2 bg-gray-700 text-white rounded-md shadow-sm hover:bg-gray-800 transition duration-300">
                <i class="fas fa-search"></i> Rechercher
            </button> --}}
        </div>

        <!-- Tableau des stocks restants -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Référence des appareils</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Stock restant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="device-table">
                    @foreach ($deviceIdentifiers as $deviceIdentifier)
                        <tr class="hover:bg-gray-50 transition duration-300">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold">{{ $deviceIdentifier->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $deviceIdentifier->materials_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script pour filtrer les appareils -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('device-table');
        const rows = tableBody.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const deviceName = row.cells[0].textContent.toLowerCase();

                if (deviceName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection
