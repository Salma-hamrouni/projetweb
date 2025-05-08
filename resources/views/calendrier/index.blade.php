@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary fw-bold">📅 Calendrier des Objectifs</h1>

    <div id="calendar"></div>
</div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: @json($events), // Passer les événements dans le calendrier
                eventClick: function (info) {
                    alert(info.event.title + "\n" + info.event.extendedProps.description);
                }
            });

            calendar.render();
        });
    </script>
@endpush
