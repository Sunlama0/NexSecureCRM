@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <!-- Afficher les messages de succès -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Bouton pour ouvrir la fenêtre modale de création de rendez-vous -->
        <div class="flex justify-end mb-4">
            <button id="openModal"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-500 transition ease-in-out duration-300">
                + Nouveau rendez-vous
            </button>
        </div>

        <!-- Le calendrier -->
        <div id="calendar" class="bg-white shadow-lg rounded-lg p-4 mb-8"></div>

        <!-- Fenêtre modale pour créer un nouveau rendez-vous -->
        <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-800 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

                <div
                    class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-6">Créer un nouveau rendez-vous</h3>

                        <!-- Formulaire de création de rendez-vous -->
                        <form action="{{ route('appointments.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                                <input type="text" name="title" id="title"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="participants" class="block text-sm font-medium text-gray-700">Participants
                                    (séparés par des virgules)</label>
                                <input type="text" name="participants[]" id="participants"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date" id="date"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>

                                <div class="mb-4">
                                    <label for="start_time" class="block text-sm font-medium text-gray-700">Heure de
                                        début</label>
                                    <input type="time" name="start_time" id="start_time"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>

                                <div class="mb-4">
                                    <label for="end_time" class="block text-sm font-medium text-gray-700">Heure de
                                        fin</label>
                                    <input type="time" name="end_time" id="end_time"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>

                                <div class="mb-4">
                                    <label for="location" class="block text-sm font-medium text-gray-700">Lieu
                                        (optionnel)</label>
                                    <input type="text" name="location" id="location"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description
                                    (optionnel)</label>
                                <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <select name="category" id="category"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="orange">Orange</option>
                                    <option value="jaune">Jaune</option>
                                    <option value="vert">Vert</option>
                                    <option value="bleu">Bleu</option>
                                    <option value="violet">Violet</option>
                                    <option value="rouge">Rouge</option>
                                </select>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" id="closeModal"
                                    class="mr-4 px-4 py-2 rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">Annuler</button>
                                <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-500 transition">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Configuration du calendrier avec les vues jour, semaine, mois
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['dayGrid', 'timeGrid', 'interaction'],
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    initialView: 'dayGridMonth', // Vue par défaut (mois)
                    editable: true,
                    selectable: true,
                    events: [
                        // Exemple d'événements factices
                        {
                            title: 'Réunion',
                            start: '2023-09-20',
                            end: '2023-09-22',
                        },
                        {
                            title: 'Formation',
                            start: '2023-09-25T10:30:00',
                            end: '2023-09-25T12:30:00',
                        }
                    ]
                });
                calendar.render();
            } else {
                console.log('Calendrier non trouvé');
            }
        });

        // Fonction pour gérer les couleurs des catégories
        function getColorFromCategory(category) {
            switch (category) {
                case 'orange':
                    return '#FFA500';
                case 'jaune':
                    return '#FFFF00';
                case 'vert':
                    return '#008000';
                case 'bleu':
                    return '#0000FF';
                case 'violet':
                    return '#800080';
                case 'rouge':
                    return '#FF0000';
                default:
                    return '#000000'; // Couleur par défaut si la catégorie n'est pas reconnue
            }
        }

        // Gérer l'ouverture et la fermeture de la modale
        const openModalButton = document.getElementById('openModal');
        const closeModalButton = document.getElementById('closeModal');
        const modal = document.getElementById('modal');

        openModalButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    </script>
@endsection
