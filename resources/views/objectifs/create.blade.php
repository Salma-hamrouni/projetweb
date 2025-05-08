@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Créer un Objectif</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oups !</strong> Il y a eu un problème avec vos données.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('objectifs.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" required>
            @error('title')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description" rows="4" required>{{ old('description') }}</textarea>
            @error('description')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Statut -->
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-select" required>
                <option value="en_cours" {{ old('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine" {{ old('status') == 'termine' ? 'selected' : '' }}>Terminé</option>
                <option value="abandonne" {{ old('status') == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
            </select>
            @error('status')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Deadline -->
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" class="form-control" required>
            @error('deadline')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Partage -->
        <div class="form-group mt-3">
            <label for="shared_with_user_id">Partager avec un autre utilisateur :</label>
            <select name="shared_with_user_id" class="form-control">
                <option value="">-- Ne pas partager --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" 
                        {{ old('shared_with_user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('shared_with_user_id')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Fichier -->
        <div class="form-group">
            <label for="file">Fichier (facultatif)</label>
            <input type="file" name="file" id="file" class="form-control">
            @error('file')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Étapes -->
        <h5 class="mt-4">Étapes</h5>
        <div id="etapes-container">
            <div class="row mb-2 etape-item">
                <div class="col-md-5">
                    <input type="text" name="etapes[0][titre]" class="form-control" placeholder="Titre de l'étape" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="etapes[0][description]" class="form-control" placeholder="Description de l'étape">
                </div>
                <div class="col-md-2">
                    <select name="etapes[0][status]" class="form-select">
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="abandonne">Abandonné</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" id="add-etape" class="btn btn-outline-secondary btn-sm">+ Ajouter une étape</button>

        <!-- Lieu -->
        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" id="lieu" name="lieu" class="form-control" readonly placeholder="Cliquez sur la carte">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            @error('lieu')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Carte -->
        <div id="map" style="height: 400px; border-radius: 12px;" class="mb-3"></div>

        <button type="submit" class="btn btn-success">Créer</button>
        <a href="{{ route('objectifs.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([36.8065, 10.1815], 13);  // Centre initial à Tunis
    let marker;

    // Ajouter les tuiles de la carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const latitude = {{ old('latitude', 36.8065) }};
    const longitude = {{ old('longitude', 10.1815) }};
    const initialLatLng = [latitude, longitude];

    marker = L.marker(initialLatLng, { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const lat = e.target.getLatLng().lat.toFixed(5);
        const lng = e.target.getLatLng().lng.toFixed(5);

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('lieu').value = `Lat: ${lat}, Lng: ${lng}`;
    });

    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(5);
        const lng = e.latlng.lng.toFixed(5);

        marker.setLatLng(e.latlng);

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('lieu').value = `Lat: ${lat}, Lng: ${lng}`;
    });
});
</script>
@endsection