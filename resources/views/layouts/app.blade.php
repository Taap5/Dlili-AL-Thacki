<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'دليلي الذكي')</title>

    <link href="{{ app()->environment('local') ? asset('css/bootstrap.min.css') : secure_asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link href="{{ app()->environment('local') ? asset('css/app.css') : secure_asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <x-navbar />

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <x-footer />

    @auth
        <x-sidebar />
    @endauth

    <script src="{{ app()->environment('local') ? asset('js/bootstrap.bundle.min.js') : secure_asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ app()->environment('local') ? asset('js/app.js') : secure_asset('js/app.js') }}"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
