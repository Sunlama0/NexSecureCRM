@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md mt-4"> <!-- Ajout de mt-12 pour l'espace -->
        <!-- Titre principal du Dashboard -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center space-x-2">
            <i class="fas fa-chart-line text-indigo-600"></i>
            <span>Tableau de bord</span>
        </h2>

        <!-- Section des statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Stocks disponibles -->
            <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Stocks disponibles</h3>
                <span class="text-3xl font-bold">{{ $stocksDisponibles }}</span>
                <p class="text-sm text-gray-500 mt-2">Matériels en stock</p>
            </div>

            <!-- Matériels attribués -->
            <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Matériels attribués</h3>
                <span class="text-3xl font-bold">{{ $materielsAttribues }}</span>
                <p class="text-sm text-gray-500 mt-2">Matériels attribués aux utilisateurs</p>
            </div>

            <!-- Matériels en attente de sortie -->
            <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Matériels sorti</h3>
                <span class="text-3xl font-bold">{{ $materielsSortis }}</span>
                <p class="text-sm text-gray-500 mt-2">Matériels sorti</p>
            </div>
        </div>

        <!-- Section du graphique des coûts d'attribution -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Coût des attributions de matériels</h3>
            <canvas class="p-6" id="combinedChart"></canvas>
        </div>

        <!-- Sélecteur de courbe -->
        <div class="flex justify-center mt-8 space-x-4">
            <!-- Bouton pour afficher le coût des matériels attribués -->
            <button id="showCostChart"
                class="px-8 py-3 flex items-center bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition ease-in-out duration-300">
                <i class="fas fa-euro-sign mr-3"></i> <!-- Icône Euro -->
                <span>Coût des matériels attribués (€)</span>
            </button>

            <!-- Bouton pour afficher le nombre de matériels attribués -->
            <button id="showCountChart"
                class="px-8 py-3 flex items-center bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
                <i class="fas fa-chart-line mr-3"></i> <!-- Icône Graphique -->
                <span>Nombre de matériels attribués</span>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('combinedChart').getContext('2d');

            // Initialisation des données pour les graphiques
            const months = @json($months);
            const costs = @json($costs);
            const pcAssigned = @json($pcAssigned);

            // Titre du graphique
            const chartTitle = document.getElementById('chartTitle');

            // Création du graphique combiné avec des données initiales (Coût)
            const combinedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Coût des matériels attribués (€)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        data: costs,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y1',
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y1: {
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return '€' + value; // Ajoute le symbole € pour les coûts
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                        }
                    }
                }
            });

            // Fonction pour mettre à jour le graphique avec les données de coût
            function showCostChart() {
                combinedChart.data.datasets[0].label = 'Coût des matériels attribués (€)';
                combinedChart.data.datasets[0].data = costs;
                combinedChart.options.scales.y1.ticks.callback = function(value) {
                    return '€' + value; // Format en euros pour les coûts
                };
                combinedChart.update();
                chartTitle.innerHTML = 'Historique des matériels attribués - Coût';
            }

            // Fonction pour mettre à jour le graphique avec le nombre de matériels attribués
            function showCountChart() {
                combinedChart.data.datasets[0].label = 'Nombre de matériels attribués';
                combinedChart.data.datasets[0].data = pcAssigned;
                combinedChart.options.scales.y1.ticks.callback = function(value) {
                    return value; // Format sans symbole pour le nombre
                };
                combinedChart.update();
                chartTitle.innerHTML = 'Historique des matériels attribués - Nombre';
            }

            // Ajout d'événements aux boutons
            document.getElementById('showCostChart').addEventListener('click', showCostChart);
            document.getElementById('showCountChart').addEventListener('click', showCountChart);
        });
    </script>
@endsection
