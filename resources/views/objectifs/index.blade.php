@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">🎯 Mes Objectifs</h1>
        <a href="{{ route('objectifs.create') }}" class="btn btn-primary">+ Nouvel Objectif</a>
    </div>

    {{-- Statistiques rapides --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Total</h5>
                    <span class="display-6">{{ $stats->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Terminés</h5>
                    <span class="display-6 text-success">{{ $stats->completed }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des objectifs --}}
    @if($objectifs->count())
        @foreach($objectifs as $objectif)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h4>{{ $objectif->title }}</h4>
                    <p class="text-muted mb-1">{{ $objectif->description ?? 'Pas de description.' }}</p>
                    <p class="mb-2">
                        <span class="badge bg-secondary">{{ ucfirst($objectif->status) }}</span>
                        
                        @if($objectif->lieu)
                            <span class="ms-2"><i class="bi bi-geo-alt-fill"></i> {{ $objectif->lieu }}</span>
                        @endif
                    </p>
                    <a href="{{ route('objectifs.show', $objectif->id) }}" class="btn btn-outline-primary btn-sm">Voir détails</a>
                    <a href="{{ route('objectifs.edit', $objectif->id) }}" class="btn btn-outline-secondary btn-sm">Modifier</a>
                    <form action="{{ route('objectifs.destroy', $objectif->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Supprimer cet objectif ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $objectifs->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Aucun objectif enregistré. Commencez par en créer un !
        </div>
    @endif
</div>
@endsection
