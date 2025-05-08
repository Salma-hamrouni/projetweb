@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="fas fa-share-alt"></i> Objectifs Partagés Avec Moi</h2>

    @if($objectifsPartagesAvecMoi->isEmpty())
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fa-lg me-2"></i>
            <div>
                Aucun objectif ne vous a été partagé pour le moment. Revenez plus tard ou créez un objectif à partager.
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($objectifsPartagesAvecMoi as $objectif)
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="fas fa-bullseye"></i> {{ $objectif->name }}</h5>
                            <p class="card-text"><strong>Statut :</strong> 
                                <span class="badge 
                                    {{ $objectif->status === 'terminee' ? 'bg-success' : 
                                       ($objectif->status === 'en_cours' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($objectif->status) }}
                                </span>
                            </p>
                            <p class="card-text text-muted"><small>Partagé par : {{ optional($objectif->user)->name }}</small></p>
                        </div>
                        <div class="card-footer text-end bg-light">
                            <a href="{{ route('objectifs.show', $objectif->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Voir Détail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
