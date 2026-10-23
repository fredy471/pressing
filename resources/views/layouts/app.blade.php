<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Le pressing en ligne pensé pour votre époque commandez, suivez, recevez.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title', 'Accueil') - {{ config('app.name', 'Presso') }}</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">



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

<body class="flex min-h-screen flex-col bg-bg font-sans text-gray-900 antialiased">
    <x-header />

    <main class="flex-1">
        {{-- Flash Notifications --}}
        <x-flash-message />

        @yield('content')
    </main>

    <x-footer />
</body>

</html>