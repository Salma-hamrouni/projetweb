@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- En-tête de la section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 font-weight-bold text-primary">Gestion des Étapes</h1>
        <a href="{{ route('etapes.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus mr-2"></i> Ajouter une étape
        </a>
    </div>

    <!-- Carte pour afficher les étapes -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 font-weight-semibold">Liste des Étapes</h5>
        </div>
        
        <div class="card-body p-0">
            @if($etapes->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune étape disponible</p>
                    <a href="{{ route('etapes.create') }}" class="btn btn-outline-primary">
                        Créer votre première étape
                    </a>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($etapes as $etape)
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('etapes.show', $etape->id) }}" class="font-weight-semibold text-dark">
                                {{ $etape->titre }}
                            </a>
                            <small class="d-block text-muted">Créé le {{ $etape->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('etapes.show', $etape->id) }}" class="btn btn-sm btn-outline-secondary mx-1" data-toggle="tooltip" data-placement="top" title="Voir les détails">
                                <i class="fas fa-eye"></i> Voir
                            </a>

                               
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($etapes->hasPages())
        <div class="card-footer bg-white">
            {{ $etapes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Effet de survol sur les éléments de la liste */
    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Amélioration des icônes et des boutons */
    .font-weight-semibold {
        font-weight: 600;
    }

    /* Style pour les boutons d'action */
    .btn-outline-secondary, .btn-outline-primary, .btn-outline-danger {
        font-size: 14px;
        padding: 5px 10px;
    }

    .btn-outline-secondary:hover, .btn-outline-primary:hover, .btn-outline-danger:hover {
        opacity: 0.8;
    }

    /* Animation de hover sur les éléments */
    .list-group-item:hover .btn {
        opacity: 1;
        transition: opacity 0.2s ease;
    }

    .actions .btn {
        opacity: 0.7;
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialisation des tooltips Bootstrap
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
