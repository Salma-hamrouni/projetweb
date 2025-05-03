@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center text-primary mb-4">🗺️ Carte interactive</h1>
    <div id="map" style="height: 500px; border: 2px solid red;"></div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([36.8065, 10.1815], 13);  // Centré sur Tunis
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Ajouter un marqueur pour chaque objectif
        @foreach($objectifs as $objectif)
            @if($objectif->latitude && $objectif->longitude)
                L.marker([{{ $objectif->latitude }}, {{ $objectif->longitude }}])
                    .addTo(map)
                    .bindPopup("<b>{{ $objectif->title }}</b><br>Status: {{ $objectif->status }}");
            @endif
        @endforeach
    });
</script>
@endsection
