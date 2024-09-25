@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Liste des Matériels</h2>

            <div class="mb-4 flex justify-between items-center">
                <!-- Lien vers la création d'un nouveau matériel -->
                <a href="{{ route('materials.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Ajouter un matériel
                </a>

                <div class="flex items-center space-x-4 relative">
                    <!-- Barre de recherche -->
                    <input type="text" id="search" placeholder="Rechercher par nom, numéro de série ou référence..." class="block w-80 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                    <!-- Bouton pour ouvrir le menu de filtrage par date -->
                    <button id="filterButton" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Filtrer par date
                    </button>

                    <!-- Menu de filtrage par date -->
                    <div id="dateFilterMenu" class="hidden bg-white p-4 rounded-lg shadow-md absolute top-full mt-2 right-0 w-64 z-10">
                        <div class="flex flex-col space-y-4">
                            <!-- Date de début -->
                            <div>
                                <label for="start-date" class="block text-sm font-medium text-gray-700">De</label>
                                <input type="date" id="start-date" class="block w-full rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <!-- Date de fin -->
                            <div>
                                <label for="end-date" class="block text-sm font-medium text-gray-700">à</label>
                                <input type="date" id="end-date" class="block w-full rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <!-- Bouton pour appliquer le filtrage -->
                            <button id="applyDateFilter" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Appliquer le filtre
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($materials->isEmpty())
                <p>Aucun matériel trouvé.</p>
            @else
                <!-- Tableau des matériels -->
                <table class="min-w-full divide-y divide-gray-200" id="materialsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom de l'appareil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro de série</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'acquisition
                                <span id="sort-arrow" class="cursor-pointer">↓</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="materialsBody">
                        @foreach ($materials as $material)
                            <tr>
                                <td class="px-6 py-4">{{ $material->device_name }}</td>
                                <td class="px-6 py-4">{{ $material->deviceIdentifier->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $material->serial_number }}</td>
                                <td class="px-6 py-4">{{ $material->category->name ?? 'Aucune catégorie' }}</td>
                                <td class="px-6 py-4">{{ $material->acquisition_date }}</td>
                                <td class="px-6 py-4">{{ $material->status }}</td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('materials.edit', $material->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $materials->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Script pour le filtrage côté client et tri par date d'acquisition -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            const tableBody = document.getElementById('materialsBody');
            const rows = tableBody.getElementsByTagName('tr');
            const filterButton = document.getElementById('filterButton');
            const dateFilterMenu = document.getElementById('dateFilterMenu');
            const applyDateFilterButton = document.getElementById('applyDateFilter');
            const sortArrow = document.getElementById('sort-arrow');
            let isAscending = true;

            // Afficher/Masquer le menu de filtrage
            filterButton.addEventListener('click', function() {
                dateFilterMenu.classList.toggle('hidden');
            });

            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase();
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const deviceName = row.cells[0].textContent.toLowerCase();
                    const identifiant = row.cells[1].textContent.toLowerCase();
                    const serialNumber = row.cells[2].textContent.toLowerCase();
                    const category = row.cells[3].textContent.toLowerCase();
                    const acquisitionDate = new Date(row.cells[4].textContent);
                    const statut = row.cells[5].textContent.toLowerCase();

                    let matchesSearch = deviceName.includes(searchTerm) || identifiant.includes(searchTerm) || serialNumber.includes(searchTerm) || category.includes(searchTerm) || statut.includes(searchTerm);

                    let matchesDateRange = (!isNaN(startDate.getTime()) ? acquisitionDate >= startDate : true) &&
                                           (!isNaN(endDate.getTime()) ? acquisitionDate <= endDate : true);

                    if (matchesSearch && matchesDateRange) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }

            applyDateFilterButton.addEventListener('click', filterRows);
            searchInput.addEventListener('keyup', filterRows);

            // Sorting by date
            sortArrow.addEventListener('click', function() {
                const rowsArray = Array.from(rows);
                rowsArray.sort(function(a, b) {
                    const dateA = new Date(a.cells[4].textContent);
                    const dateB = new Date(b.cells[4].textContent);
                    return isAscending ? dateA - dateB : dateB - dateA;
                });

                isAscending = !isAscending;
                sortArrow.textContent = isAscending ? '↑' : '↓';

                // Réorganiser les lignes dans le tableau
                rowsArray.forEach(function(row) {
                    tableBody.appendChild(row);
                });
            });
        });
    </script>
@endsection
