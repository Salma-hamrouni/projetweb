<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_API_KEY&libraries=places"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FocusMap') }}</title>

    <!-- Favicon -->
    <link href="{{ asset('logo.png') }}" rel="icon" type="image/png">

    <!-- Leaflet CSS -->
    <!-- Leaflet CSS -->
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
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
                        <li class="nav-item"><a class="nav-link" href="{{ route('etapes.index') }}">🧩 Étapes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('progressions.index') }}">📈 Progressions</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('MindMap.index') }}">🧠 Mindmap</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">👤 Profil</a></li>
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Scripts spécifiques des pages -->
    @yield('scripts')
    @stack('scripts')
</body>

</html>
