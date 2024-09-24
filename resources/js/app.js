import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Importer la bibliothèque principale de FullCalendar avant les plugins
import { Calendar } from '@fullcalendar/core';  // Import principal

// Importer les plugins après la bibliothèque principale
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

// Importer les fichiers CSS spécifiques aux plugins si tu ne les as pas mis dans app.css
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';

// Initialiser le calendrier
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            events: []  // Ajouter des événements si nécessaire
        });
        calendar.render();
    } else {
        console.error("L'élément avec l'ID 'calendar' n'a pas été trouvé.");
    }
});
