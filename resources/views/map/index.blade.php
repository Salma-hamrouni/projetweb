@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0 text-center">🗺️ Carte Interactive des Objectifs</h1>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">Cliquez sur les marqueurs pour voir les détails des objectifs</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    #map {
        border-radius: 0 0 0.25rem 0.25rem;
    }
    .leaflet-popup-content {
        min-width: 200px;
    }
    .leaflet-popup-content b {
        color: #6f42c1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([36.8065, 10.1815], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Custom icon
        const customIcon = L.icon({
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        // Add markers for each objective
        @foreach($objectifs as $objectif)
            @if($objectif->latitude && $objectif->longitude)
                L.marker([{{ $objectif->latitude }}, {{ $objectif->longitude }}], {icon: customIcon})
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-2">
                            <h5 class="text-primary">{{ $objectif->title }}</h5>
                            <p class="mb-1"><strong>Statut:</strong> 
                                <span class="badge 
                                    {{ $objectif->status === 'Terminé' ? 'bg-success' : 
                                       ($objectif->status === 'En cours' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $objectif->status }}
                                </span>
                            </p>
                            <p class="mb-0"><small>Coordonnées: {{ $objectif->latitude }}, {{ $objectif->longitude }}</small></p>
                        </div>
                    `);
            @endif
        @endforeach
    });
</script>
@endsection