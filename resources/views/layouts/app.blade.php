<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'دليلي الذكي')</title>



    <!-- Bootstrap CSS (CDN) -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    <!-- Cairo Font -->

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">



    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



    <!-- Leaflet CSS (للخرائط) -->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />



    <!-- ملفات Vite المجمعة (الخاصة بمشروعك) -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])



    @stack('styles')

</head>



<body class="d-flex flex-column min-vh-100">

    <!-- شريط التنقل -->

    <x-navbar />



    <!-- المحتوى الرئيسي -->

    <main class="flex-grow-1">

        @yield('content')

    </main>



    <!-- تذييل الصفحة -->

    <x-footer />



    <!-- الشريط الجانبي (للمستخدمين المسجلين) -->

    @auth

        <x-sidebar />

    @endauth



    <!-- JavaScript (CDN) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



    @stack('scripts')

</body>

</html>
