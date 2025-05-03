@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Affichage du message de succès si la photo est enregistrée --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👤 Mon Profil</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">✏️ Modifier</a>
                </div>

                <div class="card-body">
                    {{-- Informations utilisateur --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom :</label>
                        <p class="form-control-plaintext">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email :</label>
                        <p class="form-control-plaintext">{{ Auth::user()->email }}</p>
                    </div>

                  {{-- Affichage de la photo actuelle --}}
<div class="mb-4 text-center">
    <label class="form-label fw-bold">Photo de profil actuelle :</label><br>
    @if(Auth::user()->photo)
        <div class="profile-photo-container">
        <img src="{{ asset('storage/' . $user->photo) }}" class="img-thumbnail" style="max-width: 300px;" alt="Photo de profil">

            
        </div>
    @else
        <p>Aucune photo de profil définie.</p>
    @endif
</div>



                    {{-- Retour au tableau de bord --}}
                    <div class="text-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">⬅️ Retour au tableau de bord</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Styles personnalisés --}}
@push('styles')
<style>
    .profile-photo-container {
        display: inline-block;
        border-radius: 50%;
        border: 4px solid #007bff;
        padding: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 150px; /* Limite la largeur du conteneur */
        height: 150px; /* Limite la hauteur du conteneur */
        overflow: hidden; /* Cache l'excédent de l'image */
    }

    .profile-photo {
        border-radius: 50%;
        width: 100%; /* L'image prendra 100% de la largeur du conteneur */
        height: 100%; /* L'image prendra 100% de la hauteur du conteneur */
        object-fit: cover; /* Garantit que l'image couvre le conteneur sans déformation */
        transition: transform 0.3s ease;
    }

    .profile-photo-container:hover .profile-photo {
        transform: scale(1.1);
    }

    .profile-photo-container:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>

@endpush
