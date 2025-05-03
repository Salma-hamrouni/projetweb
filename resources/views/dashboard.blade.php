@extends('layouts.app')



@section('content')
    <div class="container py-4" style="background-color: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 30px;">
        <h1 class="mb-4 text-center fw-bold text-primary">Tableau de bord</h1>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">🎯 Objectifs</h5>
                        <p class="fs-3 fw-semibold text-dark">{{ $objectifsCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">📈 Progressions</h5>
                        <p class="fs-3 fw-semibold text-dark">{{ $averageProgression }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">🧩 Étapes</h5>
                        <p class="fs-3 fw-semibold text-dark">{{ $etapesCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lien vers la carte -->
        <div class="mt-4 text-center">
            <a class="btn btn-outline-primary" href="{{ route('map.index') }}">🗺️ Voir la carte interactive</a>
        </div>

   
@endsection



