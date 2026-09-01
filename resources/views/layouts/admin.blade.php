<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title') — Presso</title>

    <!---Fonts----->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-bg text-text font-sans antialiased selection:bg-accent/20 selection:text-accent">
    <div class="flex h-full overflow-hidden" x-data="{ mobileNav: false }">

        {{-- Sidebar Desktop --}}
        <aside class="hidden lg:flex lg:flex-col w-72 bg-content border-r border-muted/10 shrink-0 z-20 shadow-sm">
            <!-- Logo & Brand -->
            <div class="h-20 flex items-end px-6 border-b w-full justify-between border-muted/10 pb-2">
                <x-pressing-logo />
                <x-theme-toggle />
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                @php
                $links = [
                ['route' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'fa-solid fa-chart-pie'],
                ['route' => 'admin.orders.index', 'label' => 'Commandes', 'icon' => 'fa-solid fa-bag-shopping'],
                ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => 'fa-solid fa-tag'],
                ['route' => 'admin.clothes-types.index', 'label' => 'Types de vêtements', 'icon' => 'fa-solid fa-shirt'],
                ['route' => 'admin.prices.index', 'label' => 'Tarifs', 'icon' => 'fa-solid fa-coins'],
                ['route' => 'admin.users.index', 'label' => 'Utilisateurs', 'icon' => 'fa-solid fa-users'],
                ];
                @endphp

                <p class="px-3 pb-2 text-[11px] font-mono uppercase tracking-wider text-muted/70 font-semibold">Menu Principal</p>

                @foreach ($links as $link)
                @php
                $isActive = request()->routeIs($link['route'].'*');
                @endphp
                <a href="{{ route($link['route']) }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                                  {{ $isActive ? 'bg-accent text-inverse shadow-md shadow-accent/20' : 'text-text hover:bg-inverse/50' }}">
                    <i class="{{ $link['icon'] }} text-base w-5 text-center transition-transform group-hover:scale-110 {{ $isActive ? 'text-inverse' : 'text-text group-hover:text-accent' }}"></i>
                    <span>{{ $link['label'] }}</span>
                </a>
                @endforeach
            </nav>

            <!-- User profile / Logout section -->
            <div class="p-4 border-t border-muted/10 bg-inverse/[0.01]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-muted hover:text-danger hover:bg-danger/10 transition-all duration-200 group">
                        <i class="fa-solid fa-right-from-bracket text-base w-5 text-center transition-transform group-hover:-translate-x-0.5"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Nav Overlay --}}
        <div x-cloak x-show="mobileNav" x-transition.opacity class="fixed inset-0 z-50 lg:hidden bg-bg backdrop-blur-sm" @click="mobileNav = false"></div>

        <aside x-cloak x-show="mobileNav" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-72 bg-content p-4 z-50 lg:hidden shadow-2xl flex flex-col justify-between">
            <div>
                <div class="h-16 flex items-center justify-between px-2 border-b border-muted/10 mb-4">
                    <x-pressing-logo />
                    <button @click="mobileNav = false" class="w-8 h-8 rounded-lg bg-inverse/5 flex items-center justify-center text-text hover:bg-inverse/10">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <nav class="space-y-1.5">
                    @foreach ($links as $link)
                    @php
                    $isActive = request()->routeIs($link['route'].'*');
                    @endphp
                    <a href="{{ route($link['route']) }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl text-sm font-medium {{ $isActive ? 'bg-accent text-white' : 'text-text hover:bg-inverse/5' }}">
                        <i class="{{ $link['icon'] }} text-base w-5 text-center"></i>
                        <span>{{ $link['label'] }}</span>
                    </a>
                    @endforeach
                </nav>
            </div>
            <div class="p-2 border-t border-muted/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-danger hover:bg-danger/10">
                        <i class="fa-solid fa-right-from-bracket text-base w-5 text-center text-accent"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Wrapper --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Top Header Mobile --}}
            <header class="h-20 flex items-center justify-between px-6 border-b border-muted/30 bg-bg lg:hidden z-10 shadow-xs">
                <x-pressing-logo />
                <button @click="mobileNav = true" class="w-10 h-10 rounded-xl bg-inverse/5 flex items-center justify-center text-text hover:bg-inverse/10 transition-colors">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </header>

            {{-- Main Content Area --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 bg-bg">

                {{-- Flash Notifications --}}
                @if (session('success'))
                <div class="mb-6 bg-accent/10 text-accent border border-accent/20 rounded-2xl px-5 py-4 text-sm font-medium flex items-center gap-3 shadow-xs animate-fade-in">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-6 bg-danger/10 text-danger border border-danger/20 rounded-2xl px-5 py-4 text-sm shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Veuillez corriger les erreurs ci-dessous :</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-1 text-xs opacity-90">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Slot / Content --}}
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>