@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mindmap</h1>

    <div class="input-group mb-3">
        <input type="text" id="objectif-nom" class="form-control" placeholder="Nom de l'objectif">
        <button class="btn btn-primary" type="button" id="ask-button">Rechercher</button>
    </div>

    <div id="mindmap-container" class="mt-3">
        <div id="jsmind_container" style="width:100%; height:600px;"></div>
    </div>

    <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/style/jsmind.css" rel="stylesheet">
<style>
    #jsmind_container {
        border: 1px solid #ccc;
        background-color: #fff;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/js/jsmind.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = 'jsmind_container';
    let jm = null;

    const showMindmap = (mindData) => {
        if (jm) {
            jm.clear();
        }

        jm = new jsMind({
            container: container,
            editable: false,
            theme: 'primary'
        });

        jm.show(mindData);
        jm.expand_all();
    };

    document.getElementById('ask-button').addEventListener('click', () => {
        const objectifNom = document.getElementById('objectif-nom').value;

        if (!objectifNom) {
            displayError("Veuillez entrer le nom de l'objectif.");
            return;
        }

        fetch(`/ask?objectif_nom=${encodeURIComponent(objectifNom)}`)
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error("La réponse n'est pas au format JSON.");
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    displayError(data.error);
                } else if (data.mindmap) {
                    hideError();
                    showMindmap(data.mindmap);
                } else {
                    displayError("Données inattendues.");
                }
            })
            .catch(error => {
                console.error(error);
                displayError("Erreur lors de la récupération des données.");
            });
    });

    const displayError = (message) => {
        const errorDiv = document.getElementById('error-message');
        errorDiv.classList.remove('d-none');
        errorDiv.textContent = message;
    };

    const hideError = () => {
        const errorDiv = document.getElementById('error-message');
        errorDiv.classList.add('d-none');
        errorDiv.textContent = '';
    };
});
</script>
@endsection
