@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary">Modifier l'Objectif - Faites évoluer vos projets !</h2>

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

    <form action="{{ route('objectifs.update', $objectif->id) }}" method="POST" class="form-create">
        @csrf
        @method('PUT')

        <!-- Titre avec effet de focus -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title', $objectif->title) }}" class="form-control" required 
                onfocus="this.style.transition = 'all 0.5s ease'; this.style.borderColor = '#007bff';">
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description avec animation -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $objectif->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
    <label for="deadline" class="form-label">Deadline</label>
    <input type="date" id="deadline" name="deadline" 
           value="{{ old('deadline', $objectif->deadline ? \Carbon\Carbon::parse($objectif->deadline)->format('Y-m-d') : '') }}" 
           class="form-control" required>
    @error('deadline')
        <div style="color: red;">{{ $message }}</div>
    @enderror
</div>


        <!-- Statut avec changements de couleur -->
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-select" required>
                <option value="en_cours" {{ (old('status', $objectif->status) === 'en_cours') ? 'selected' : '' }}>En_cours</option>
                <option value="termine" {{ (old('status', $objectif->status) === 'termine') ? 'selected' : '' }}>Termine</option>
                <option value="abandonne" {{ (old('status', $objectif->status) === 'abandonne') ? 'selected' : '' }}>Abandonne</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-3">
    <label for="shared_with_user_id">Partager avec un autre utilisateur :</label>
    <select name="shared_with_user_id" class="form-control">
        <option value="">-- Ne pas partager --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ isset($objectif) && $objectif->shared_with_user_id == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
</div>
<!-- Dans create.blade.php et edit.blade.php -->
<div class="form-group">
    <label for="file">Fichier (facultatif)</label>
    <input type="file" name="file" id="file" class="form-control">
</div>


        <!-- Lieu avec carte interactive -->
        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $objectif->lieu) }}" class="form-control" readonly>
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $objectif->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $objectif->longitude) }}">
        </div>

        <!-- Carte interactive -->
        <div id="map" style="height: 400px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);" class="mb-3"></div>

        <!-- Animation de la progression de l'objectif -->
        <div class="progress mb-4" style="height: 20px;">
            <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <!-- Étapes avec effets créatifs -->
        <h4 class="mt-4">Étapes - Ajoutez vos actions !</h4>
        <div id="etapes-container">
            @foreach ($objectif->etapes as $index => $etape)
                <div class="card mb-2 p-3 etape-item">
                    <input type="hidden" name="etapes[{{ $index }}][id]" value="{{ $etape->id }}">

                    <div class="mb-2">
                        <label for="etapes[{{ $index }}][titre]" class="form-label">Titre</label>
                        <input type="text" name="etapes[{{ $index }}][titre]" class="form-control" value="{{ $etape->titre }}" required>
                    </div>

                    <div class="mb-2">
                        <label for="etapes[{{ $index }}][description]" class="form-label">Description</label>
                        <textarea name="etapes[{{ $index }}][description]" class="form-control" rows="2" required>{{ $etape->description }}</textarea>
                    </div>

                    <div class="mb-2">
                        <label for="etapes[{{ $index }}][status]" class="form-label">Statut</label>
                        <select name="etapes[{{ $index }}][status]" class="form-select" required>
                            <option value="en_cours" {{ $etape->status === 'en_cours' ? 'selected' : '' }}>En_cours</option>
                            <option value="termine" {{ $etape->status === 'termine' ? 'selected' : '' }}>Termine</option>
                            <option value="abandonne" {{ $etape->status === 'abandonne' ? 'selected' : '' }}>Abandonne</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-danger btn-sm remove-etape">Supprimer cette étape</button>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary mt-2" id="add-etape">
            <i class="bi bi-plus-circle"></i> Ajouter une étape
        </button>

        <button type="submit" class="btn btn-primary mt-4">Mettre à jour</button>
        <a href="{{ route('objectifs.index') }}" class="btn btn-secondary mt-4">
            <i class="bi bi-arrow-left-circle"></i> Annuler
        </a>
    </form>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = parseFloat(document.getElementById('latitude').value) || 36.8065;
        const lng = parseFloat(document.getElementById('longitude').value) || 10.1815;

        const map = L.map('map').setView([lat, lng], 13);
        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        document.getElementById('lieu').value = `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            const newLat = position.lat.toFixed(5);
            const newLng = position.lng.toFixed(5);

            document.getElementById('latitude').value = newLat;
            document.getElementById('longitude').value = newLng;
            document.getElementById('lieu').value = `Lat: ${newLat}, Lng: ${newLng}`;
        });

        map.on('click', function (e) {
            const newLat = e.latlng.lat.toFixed(5);
            const newLng = e.latlng.lng.toFixed(5);

            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = newLat;
            document.getElementById('longitude').value = newLng;
            document.getElementById('lieu').value = `Lat: ${newLat}, Lng: ${newLng}`;
        });

        // Mise à jour dynamique de la barre de progression
        const totalSteps = {{ count($objectif->etapes) }};
        let completedSteps = {{ count($objectif->etapes->where('status', 'termine')) }};
        const progressBar = document.getElementById('progress-bar');

        const progress = (completedSteps / totalSteps) * 100;
        progressBar.style.width = `${progress}%`;

        // Ajout d'étapes avec effet
        let etapeIndex = {{ count($objectif->etapes) }};
        document.getElementById('add-etape').addEventListener('click', function () {
            const container = document.getElementById('etapes-container');
            const newEtape = document.createElement('div');
            newEtape.classList.add('card', 'mb-2', 'p-3', 'etape-item');
            newEtape.classList.add('slide-up'); // Animation créative
            newEtape.innerHTML = ` 
                <div class="mb-2">
                    <label>Titre</label>
                    <input type="text" name="etapes[${etapeIndex}][titre]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Description</label>
                    <textarea name="etapes[${etapeIndex}][description]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Statut</label>
                    <select name="etapes[${etapeIndex}][status]" class="form-select" required>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="abandonne">Abandonné</option>
                    </select>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-etape">Supprimer cette étape</button>
            `;
            container.appendChild(newEtape);
            etapeIndex++;

            newEtape.querySelector('.remove-etape').addEventListener('click', function () {
                newEtape.remove();
            });
        });
    });
</script>
@endsection
