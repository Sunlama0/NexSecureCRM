@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Liste des Matériels</h2>

            <div class="mb-4 flex justify-between items-center">

                <!-- Lien vers la création d'un nouveau matériel -->
                <a href="{{ route('materials.create') }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Ajouter un matériel
                </a>

                <div class="flex items-center">
                    <input type="text" id="search" placeholder="Rechercher par nom, numéro de série ou référence..."
                        class="block w-80 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

            </div>

            @if ($materials->isEmpty())
                <p>Aucun matériel trouvé.</p>
            @else
                <!-- Tableau des matériels -->
                <table class="min-w-full divide-y divide-gray-200" id="materialsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                                de l'appareil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Identifiant de l'appareil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Numéro de série</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="materialsBody">
                        @foreach ($materials as $material)
                            <tr>
                                <td class="px-6 py-4">{{ $material->device_name }}</td>
                                <td class="px-6 py-4">{{ $material->deviceIdentifier->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $material->serial_number }}</td>
                                <td class="px-6 py-4">{{ $material->category->name ?? 'Aucune catégorie' }}</td>
                                <td class="px-6 py-4">{{ $material->status }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('materials.edit', $material->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Script pour le filtrage côté client -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('materialsBody');
            const rows = tableBody.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const deviceName = row.cells[0].textContent.toLowerCase();
                    const identifiant = row.cells[1].textContent.toLowerCase();
                    const serialNumber = row.cells[2].textContent.toLowerCase();
                    const reference = row.cells[3].textContent.toLowerCase();
                    const category = row.cells[3].textContent.toLowerCase();
                    const statut = row.cells[4].textContent.toLowerCase();

                    if (deviceName.includes(searchTerm) || identifiant.includes(searchTerm) || serialNumber
                        .includes(searchTerm) || category.includes(searchTerm) || statut.includes(
                            searchTerm) || reference.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection
