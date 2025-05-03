@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $objectif->titre }}</h1>
    <p>{{ $objectif->description }}</p>

    {{-- Affichage du statut --}}
    <div class="mb-3">
    <p><strong>Statut :</strong>
    @switch($objectif->status)
        @case('en_cours')
            En cours
            @break
        @case('termine')
            Terminé
            @break
        @case('abandonne')
            Abandonné
            @break
        @default
            Inconnu
    @endswitch
</p>

    </div>

    {{-- Emplacement --}}
    <div class="mb-3">
        <strong>Emplacement :</strong><br>
        @if($objectif->latitude && $objectif->longitude)
            <div id="map" style="width: 100%; height: 400px; border-radius: 10px; overflow: hidden;"></div>
            
        @else
            <p class="text-danger mt-2">Coordonnées non disponibles pour cet objectif.</p>
        @endif
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('objectifs.edit', $objectif->id) }}" class="btn btn-warning">Modifier</a>
        <form method="POST" action="{{ route('objectifs.destroy', $objectif->id) }}" onsubmit="return confirm('Êtes-vous sûre de vouloir supprimer cet objectif ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
</div>

{{-- Leaflet uniquement si les coordonnées sont disponibles --}}
@if($objectif->latitude && $objectif->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialiser la carte avec les coordonnées de l'objectif
        const latitude = {{ $objectif->latitude ?? '36.8065' }};  // Utiliser la latitude de l'objectif ou la valeur par défaut si elle n'existe pas
        const longitude = {{ $objectif->longitude ?? '10.1815' }};  // Utiliser la longitude de l'objectif ou la valeur par défaut

        const map = L.map('map').setView([latitude, longitude], 13);  // Centre la carte sur la latitude et la longitude de l'objectif

        // Ajouter les tuiles de la carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Placer un marqueur sur l'emplacement de l'objectif
        L.marker([latitude, longitude]).addTo(map)
            .bindPopup("Emplacement de l'objectif: " + latitude + ", " + longitude) // Affiche les coordonnées à côté du marqueur
            .openPopup();

        // Désactiver l'interaction avec la carte (inmodifiable)
        map.dragging.disable();  // Désactiver le déplacement de la carte
        map.touchZoom.disable();  // Désactiver le zoom tactile
        map.scrollWheelZoom.disable();  // Désactiver le zoom avec la molette de la souris
        map.doubleClickZoom.disable();  // Désactiver le zoom au double-clic
    });
</script>


@endif
@endsection
