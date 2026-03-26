<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'دليلي الذكي')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Cairo Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS الخاص بالتطبيق -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />


    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- تضمين الشريط التنقل -->
    <x-navbar />

    <!-- المحتوى الرئيسي -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- تضمين الفوتر -->
    <x-footer />

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- JS الخاص بالتطبيق -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @auth
        <x-sidebar />
    @endauth
</body>

</html>
