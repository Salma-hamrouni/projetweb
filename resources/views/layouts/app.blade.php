<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ config('app.name', 'FocusMap') }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <!-- Favicon -->
    <link href="{{ asset('logo.png') }}" rel="icon" type="image/png">
   <!-- Styles empilés -->
   @stack('styles')
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_API_KEY&libraries=places"></script>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/mind-elixir/dist/mind-elixir.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/js/jsmind.js"></script>
<link href="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/style/jsmind.css" rel="stylesheet">


</head>



<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">FocusMap</a>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">📊 Tableau de bord</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('objectifs.index') }}">🎯 Objectifs</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('progressions.index')}}">📈 Progressions</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('mindmap.index') }}">🧠 Mindmap</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('timeline.index') }}">🕒 Timeline</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">👤 Profil</a></li>
                        <li class="nav-item">
                <a class="nav-link" href="{{ route('shared_objectifs') }}">👥 Objectifs partagés</a>
            </li>
                    </ul>

                    <form action="{{ route('logout') }}" method="POST" class="d-flex">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">🚪 Déconnexion</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        @push('styles')
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
    <script src="https://unpkg.com/mind-elixir/dist/mind-elixir.min.js"></script>
    <!-- Inclure la bibliothèque jsMind -->

<!-- Chargement de jsMind directement dans le template -->
<script src="https://cdn.jsdelivr.net/npm/jsmind@0.8.7/es6/jsmind.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsmind@0.8.7/es6/jsmind.draggable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/js/jsmind.js"></script>
    <!-- Scripts spécifiques aux pages -->
    @yield('scripts')
    @stack('scripts')
</body>

</html>
