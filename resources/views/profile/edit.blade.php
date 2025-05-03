@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h2">Modifier mon Profil</h1>
    <p class="text-muted">Mettez à jour vos informations personnelles.</p>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe (Laisser vide pour ne pas changer)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <!-- Ajout du champ pour modifier la photo de profil -->
        <div class="form-group">
            <label for="photo">Modifier la photo de profil</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
        </button>
    </form>
</div>
@endsection
