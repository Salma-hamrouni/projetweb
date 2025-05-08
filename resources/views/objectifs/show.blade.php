@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $objectif->title }}</h1>
    <p>{{ $objectif->description }}</p>
    <p><strong>Deadline:</strong> 
        {{ $objectif->deadline ? \Carbon\Carbon::parse($objectif->deadline)->format('d M Y') : 'Pas de date' }}
    </p>

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

    <h4 class="mt-4">Étapes</h4>
    @if($objectif->etapes->isEmpty())
        <p>Aucune étape définie pour cet objectif.</p>
    @else
        <ul class="list-group">
            @foreach($objectif->etapes as $etape)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $etape->titre }}</strong><br>
                        <small>{{ $etape->description }}</small>
                    </div>
                    <span class="badge bg-{{ $etape->status == 'termine' ? 'success' : 'secondary' }}">
                        {{ ucfirst($etape->status) }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif

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
        const latitude = {{ $objectif->latitude ?? '36.8065' }};  // Utiliser la latitude de l'objectif ou une valeur par défaut
        const longitude = {{ $objectif->longitude ?? '10.1815' }};  // Utiliser la longitude de l'objectif ou une valeur par défaut

        // Initialiser la carte
        const map = L.map('map').setView([latitude, longitude], 13);

        // Ajouter les tuiles de la carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Placer un marqueur sur l'emplacement de l'objectif
        L.marker([latitude, longitude]).addTo(map)
            .bindPopup("Emplacement de l'objectif: " + latitude + ", " + longitude) // Afficher les coordonnées
            .openPopup();

        // Désactiver les interactions avec la carte
        map.dragging.disable();
        map.touchZoom.disable();
        map.scrollWheelZoom.disable();
        map.doubleClickZoom.disable();
    });
    </script>
@endif
@endsection
