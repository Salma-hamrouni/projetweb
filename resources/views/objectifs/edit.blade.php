@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'Objectif</h1>
    <form action="{{ route('objectifs.update', $objectif->id) }}" method="POST" onsubmit="return confirmUpdate()">
    @csrf
    @method('PUT')

        <!-- Titre de l'objectif -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $objectif->title) }}" required>
        </div>

        <!-- Description de l'objectif -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description" required>{{ old('description', $objectif->description) }}</textarea>
        </div>

        <!-- Statut de l'objectif -->
        <div class="mb-3">
    <label for="status" class="form-label">Statut</label>
    <select name="status" class="form-select" id="status" required>
        <option value="en_cours" {{ (old('status') ?? $objectif->status) === 'en_cours' ? 'selected' : '' }}>En cours</option>
        <option value="termine" {{ (old('status') ?? $objectif->status) === 'termine' ? 'selected' : '' }}>Terminé</option>
        <option value="abandonne" {{ (old('status') ?? $objectif->status) === 'abandonne' ? 'selected' : '' }}>Abandonné</option>
    </select>
</div>


        <!-- Carte pour choisir l'emplacement -->
        <div class="mb-3">
            <label for="lieu" class="form-label">Emplacement</label>
            <div id="map" style="width: 100%; height: 400px;"></div>
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $objectif->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $objectif->longitude) }}">
            <input type="text" id="lieu" class="form-control" name="lieu" value="{{ old('lieu', $objectif->lieu) }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Récupérer les coordonnées de l'objectif depuis la base de données
        const latitude = {{ $objectif->latitude ?? '36.8065' }};  // Utiliser la latitude de l'objectif
        const longitude = {{ $objectif->longitude ?? '10.1815' }};  // Utiliser la longitude de l'objectif

        // Initialiser la carte avec les coordonnées de l'objectif
        const map = L.map('map').setView([latitude, longitude], 13);  // Centre la carte sur la latitude et longitude de l'objectif

        // Ajouter les tuiles de la carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Placer un marqueur sur l'emplacement de l'objectif
        const marker = L.marker([latitude, longitude], { draggable: true }).addTo(map)
            .bindPopup("Emplacement de l'objectif: " + latitude + ", " + longitude) // Affiche les coordonnées à côté du marqueur
            .openPopup();

        // Fonction de mise à jour des champs lorsque le marqueur est déplacé
        marker.on('dragend', function(e) {
            const lat = e.target.getLatLng().lat.toFixed(5);
            const lng = e.target.getLatLng().lng.toFixed(5);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('lieu').value = `Lat: ${lat}, Lng: ${lng}`;
        });

        // Ajouter un événement pour le clic sur la carte pour positionner le marqueur
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(5);
            const lng = e.latlng.lng.toFixed(5);

            // Placer le marqueur ou déplacer l'existant
            marker.setLatLng(e.latlng);

            // Mettre à jour les champs cachés
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('lieu').value = `Lat: ${lat}, Lng: ${lng}`;
        });
    });

    // Fonction de confirmation avant de modifier l'objectif
    function confirmUpdate() {
        return confirm('Êtes-vous sûre de vouloir modifier cet objectif ?');
    }
</script>

@endsection
