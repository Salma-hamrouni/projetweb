@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Créer une Nouvelle Étape</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oups !</strong> Des erreurs ont été détectées :
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('etapes.store') }}">
        @csrf

        <div class="mb-3">
            <label for="titre" class="form-label">Titre de l'étape</label>
            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" id="titre" value="{{ old('titre') }}" required>
            @error('titre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="4" required>{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
    <label class="form-label d-block mb-2">Statut de l'étape</label>
    <div class="btn-group" role="group" aria-label="Status options">
        @php
            $statuses = [
                'en_attente' => 'en cours',
                'en_cours' => 'terminee',
                'terminee' => 'abondonnee'
            ];
        @endphp

        @foreach($statuses as $value => $label)
            <input type="radio" class="btn-check" name="status" id="status_{{ $value }}" value="{{ $value }}" autocomplete="off"
                {{ old('status') === $value ? 'checked' : '' }} required>
            <label class="btn btn-outline-secondary @if(old('status') === $value) active @endif" for="status_{{ $value }}">
                {{ $label }}
            </label>
        @endforeach
    </div>

    @error('status')
        <div class="text-danger mt-2">{{ $message }}</div>
    @enderror
</div>



        <div class="mb-3">
            <label for="objectif_id" class="form-label">Objectif associé</label>
            <select name="objectif_id" class="form-select @error('objectif_id') is-invalid @enderror" required>
                <option value="">-- Sélectionner un objectif --</option>
                @foreach($objectifs as $objectif)
                    <option value="{{ $objectif->id }}" {{ old('objectif_id') == $objectif->id ? 'selected' : '' }}>
                        {{ $objectif->title }}
                    </option>
                @endforeach
            </select>
            @error('objectif_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Ajouter l'étape
        </button>
    </form>
</div>
@endsection
@section('styles')
<style>
    .btn-check:checked + .btn-outline-secondary {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .btn-outline-secondary {
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-secondary:hover {
        background-color: #e2e6ea;
    }
</style>
@endsection
