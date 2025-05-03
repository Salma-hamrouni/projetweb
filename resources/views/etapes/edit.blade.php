@extends('layouts.app')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h3 class="mb-0 text-primary">Modifier l'étape</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('etapes.update', $etape->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de l'étape</label>
                    <input type="text" name="titre" class="form-control" id="titre" value="{{ old('titre', $etape->titre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4" required>{{ old('description', $etape->description) }}</textarea>
                </div>

                <!-- Sélecteur de statut -->
                <div class="mb-3">
                    <label for="status" class="form-label">Statut de l'étape</label>
                    <select name="status" id="status" class="form-control">
                        <option value="en cours" {{ old('status', $etape->status) == 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminée" {{ old('status', $etape->status) == 'terminée' ? 'selected' : '' }}>Terminée</option>
                        <option value="abondonnee" {{ old('status', $etape->status) == 'abondonnee' ? 'selected' : '' }}>abondonnee</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('etapes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Mettre à jour l'étape
                    </button>
                    <!-- Ajout du bouton de retour -->
                    <a href="{{ route('etapes.show', $etape->id) }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-1"></i> Retour
</a>

                </div>
            </form>

           
            </form>
        </div>
    </div>
</div>
@endsection
