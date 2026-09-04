<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <title>@yield('title', 'Accueil') - {{ config('app.name', 'Presso') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script>
    // Vérifie le thème stocké ou les préférences du système au chargement
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    function toggleMobileNav() {
        const nav = document.getElementById('mobileNav');
        nav.classList.toggle('hidden');
    }

    window.toggleMobileNav = toggleMobileNav;
</script>
@stack('scripts')
<body class="flex min-h-screen flex-col font-sans text-gray-900 antialiased bg-bg">
    <x-header />

    <main class="flex-1">
         @yield('content')
    </main>

    <x-footer />
</body>

</html>