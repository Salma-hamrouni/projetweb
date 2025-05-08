require('./bootstrap');
import Chart from 'chart.js/auto';
import 'bootstrap/dist/css/bootstrap.min.css'; 
import '../css/app.css';  // Assure-toi que le chemin est correct vers ton fichier CSS
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
import FullCalendar from 'fullcalendar';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        events: '/events', // Charger les événements via une route Laravel
        locale: 'fr', // Pour la langue française
        // Vous pouvez ajouter d'autres options selon vos besoins
    });
    calendar.render();
});
