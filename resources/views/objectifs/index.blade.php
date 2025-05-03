@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Mes Objectifs</h1>
        <a href="{{ route('objectifs.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un objectif
        </a>
    </div>

    @if($objectifs->isEmpty())
        <div class="alert alert-info">
            Aucun objectif trouvé. Cliquez sur "Ajouter un objectif" pour commencer.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($objectifs as $objectif)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $objectif->titre }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($objectif->description, 100) }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('objectifs.show', $objectif->id) }}" class="btn btn-outline-primary btn-sm">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
