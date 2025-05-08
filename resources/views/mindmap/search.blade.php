@extends('layouts.app')

@section('title', 'Générer une Mindmap')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Générer une Mindmap</h2>
                </div>
                
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('mindmap.generate') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="objectif_name" class="form-label">Rechercher un objectif</label>
                            <div class="input-group">
                                <input type="text" name="objectif_name" id="objectif_name" 
                                       class="form-control form-control-lg" 
                                       placeholder="Entrez le nom de l'objectif..." required>
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fas fa-project-diagram me-2"></i> Générer
                                </button>
                            </div>
                            <div class="form-text">La mindmap affichera l'objectif et toutes ses étapes</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<style>
    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }
</style>
@endsection
@endsection