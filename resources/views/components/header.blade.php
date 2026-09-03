<header class="sticky top-0 z-50 bg-bg border-b border-muted/30 text-text items-center justify-between">
    <div x-data="{ mobileOpen: false }" class="container mx-auto px-4 md:px-8 pt-4 pb-4">
        <nav class="flex h-[64px] items-center justify-between px-4 h-20">
            <x-pressing-logo />

            <div class="flex items-center gap-8 max-[920px]:hidden">
                <x-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}">
                    Accueil
                </x-nav-link>

                <x-nav-link :active="request()->routeIs('orders.create')" href="{{ route('orders.create') }}">
                    Services & Tarifs
                </x-nav-link>

                <x-nav-link :active="request()->routeIs('contact')" href="{{ route('contact') }}">
                    Contact
                </x-nav-link>
            </div>

            <x-theme-toggle />

            <div class="flex items-center gap-3 text-base">
                @guest
                <x-button href="{{ route('login') }}" variant="secondary" class="max-[920px]:hidden">
                    Se connecter
                </x-button>

                <x-button href="{{ route('orders.create') }}" variant="primary" class="max-[920px]:hidden">
                    Lancer ma commande
                </x-button>
                @endguest

                @auth
                <a href="{{ route('profile.edit') }}" title="Mon compte" aria-label="Mon compte"
                    class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-muted bg-surface text-sm font-semibold text-text transition duration-200 hover:border-text max-[920px]:hidden">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="inline-flex cursor-pointer items-center gap-2 whitespace-nowrap rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2 max-[920px]:hidden">Déconnexion</button>
                </form>
                @endauth
            </div>

            <button
                class="hidden cursor-pointer flex-col gap-[5px] border-0 bg-transparent max-[920px]:flex"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen.toString()"
                aria-label="Menu">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </nav>

        {{-- Menu Mobile --}}
        <div x-cloak x-show="mobileOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-bg/70 backdrop-blur-sm max-[920px]:block min-[921px]:hidden"
            @click="mobileOpen = false"></div>

        <aside x-cloak x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col justify-between bg-content p-4 shadow-2xl max-[920px]:flex min-[921px]:hidden">
            <div>
                <div class="flex h-16 items-center justify-between border-b border-muted/10 px-2 mb-4">
                    <x-pressing-logo />
                    <button type="button" @click="mobileOpen = false"
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-inverse/5 text-text hover:bg-inverse/10"
                        aria-label="Fermer le menu">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <nav class="flex flex-col gap-3">
                    <x-responsive-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}" @click="mobileOpen = false">
                        Accueil
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :active="request()->routeIs('orders.create')" href="{{ route('orders.create') }}" @click="mobileOpen = false">
                        Services & Tarifs
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :active="request()->routeIs('contact')" href="{{ route('contact') }}" @click="mobileOpen = false">
                        Contact
                    </x-responsive-nav-link>
                </nav>
            </div>

            <div class="space-y-1 border-t border-muted/15 p-4">
                @guest
                <x-responsive-nav-link :active="request()->routeIs('login')" href="{{ route('login') }}" @click="mobileOpen = false">
                    Se connecter
                </x-responsive-nav-link>
                <a href="{{ route('register') }}"
                    class="mt-2 inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2"
                    @click="mobileOpen = false">S'inscrire</a>
                @endguest

                @auth
                <a href="{{ route('profile.edit') }}" @click="mobileOpen = false"
                    class="mb-2 inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-muted bg-surface text-sm font-semibold text-text transition duration-200 hover:border-text"
                    title="Mon compte" aria-label="Mon compte">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-sm font-medium text-muted transition hover:bg-danger/10 hover:text-danger">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
                @endauth
            </div>
        </aside>
    </div>
</header>