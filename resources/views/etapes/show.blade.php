@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body px-4 py-4">
            <h1 class="mb-3" style="color: #FF6347;">
                Titre : {{ $etape->titre }}
            </h1>

            <p class="lead mb-4">
                <strong>Description :</strong> {{ $etape->description }}
            </p>

            {{-- Statut venant de la BDD --}}
            <div class="mb-3">
                <strong>Statut :</strong>
                
                @php
                    $statuts = [
                        'en_cours' => ['label' => 'En cours', 'color' => 'primary', 'icon' => 'fas fa-spinner fa-spin'],
                        'terminee' => ['label' => 'Terminée', 'color' => 'success', 'icon' => 'fas fa-check-circle'],
                        'abandonnee' => ['label' => 'Abandonnée', 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
                    ];

                    // Récupération du statut depuis la BDD
                    $statut = $etape->status;

                    // Vérification de la validité du statut
                    $statutAffichage = $statuts[$statut] ?? null;

                    // Si le statut est invalide, afficher un statut par défaut
                    if (!$statutAffichage) {
                        $statutAffichage = ['label' => 'Inconnu', 'color' => 'secondary', 'icon' => 'fas fa-question-circle'];
                    }
                @endphp

                <span class="badge bg-{{ $statutAffichage['color'] }} px-3 py-2">
                    <i class="{{ $statutAffichage['icon'] }} me-2"></i> {{ $statutAffichage['label'] }}
                </span>
            </div>

            {{-- Objectif lié --}}
            <div class="mb-3">
                <strong>Objectif lié :</strong>
                <span class="text-muted">{{ $etape->objectif->title ?? 'Aucun objectif associé' }}</span>
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('etapes.edit', $etape->id) }}" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i> Modifier cette étape
                </a>

                <form method="POST" action="{{ route('etapes.destroy', $etape->id) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger d-flex align-items-center">
                        <i class="fas fa-trash me-2"></i> Supprimer cette étape
                    </button>
                </form>
            </div>
        </div>

        <div class="card-footer bg-light text-muted text-end py-3 px-4">
            <small>Créée le {{ $etape->created_at->format('d/m/Y à H:i') }}</small>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('etapes.index') }}" class="btn btn-light border d-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Retour à la liste des étapes
        </a>
    </div>
</div>
@endsection
