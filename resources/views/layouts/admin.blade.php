<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>$title ?? 'Admin' — Presso</title>

    <!---Font----->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-bg text-text font-sans antialiased">
    <div class="flex h-full" x-data="{ mobileNav: false }">

        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col w-64 bg-content border-r border-muted/15 shrink-0">
            <div class="h-16 flex items-center px-6 border-b border-muted/15">
                <span class="font-display font-bold text-lg tracking-tight">Presso <span class="text-accent">Admin</span></span>
            </div>
            <nav class="flex-1 px-3 py-6 space-y-1">
                @php
                $links = [
                ['route' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'grid'],
                ['route' => 'admin.orders.index', 'label' => 'Commandes', 'icon' => 'list'],
                ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => 'tag'],
                ['route' => 'admin.clothes-types.index', 'label' => 'Types de vêtements', 'icon' => 'shirt'],
                ['route' => 'admin.prices.index', 'label' => 'Tarifs', 'icon' => 'coin'],
                ['route' => 'admin.users.index', 'label' => 'Utilisateurs', 'icon' => 'users'],
                ];
                @endphp
                @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs($link['route'].'*') ? 'bg-accent/10 text-accent' : 'text-muted hover:bg-inverse/50 hover:text-text' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs($link['route'].'*') ? 'bg-accent' : 'bg-muted/40' }}"></span>
                    {{ $link['label'] }}
                </a>
                @endforeach
            </nav>
            <div class="p-4 border-t border-muted/15">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-muted hover:text-danger transition px-3 py-2">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile nav overlay --}}
        <div x-cloak x-show="mobileNav" x-transition class="fixed inset-0 z-40 lg:hidden">
            <div class="absolute inset-0 bg-text/40" @click="mobileNav = false"></div>
            <aside class="absolute left-0 top-0 bottom-0 w-64 bg-content p-4">
                <nav class="space-y-1 mt-4">
                    @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-text hover:bg-inverse/50">
                        {{ $link['label'] }}
                    </a>
                    @endforeach
                </nav>
            </aside>
        </div>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-16 flex items-center justify-between px-6 border-b border-muted/15 bg-content lg:hidden">
                <span class="font-display font-bold">Presso Admin</span>
                <button @click="mobileNav = true" class="text-text">☰</button>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-10">
                @if (session('success'))
                <div class="mb-6 bg-accent2/10 text-accent2 border border-accent2/20 rounded-lg px-4 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                @endif
                @if ($errors->any())
                <div class="mb-6 bg-danger/10 text-danger border border-danger/20 rounded-lg px-4 py-3 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>