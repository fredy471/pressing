<header class="sticky top-0 z-50 bg-bg border-b border-muted/30 text-text items-center justify-between">
    <div x-data="{ mobileOpen: false }" class="container mx-auto px-4 md:px-8 pt-4 pb-4">
        <nav class="flex h-20 items-center justify-between px-4">
            <x-pressing-logo />

            <div class="flex items-center gap-8 max-[920px]:hidden">
                @guest
                <x-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}">
                    Accueil
                </x-nav-link>
                @endguest

                <x-nav-link :active="request()->routeIs('orders.create')" href="{{ route('orders.create') }}">
                    Services & Tarifs
                </x-nav-link>

                @auth
                <x-nav-link :active="request()->routeIs('client.orders.index')" href="{{ route('client.orders.index') }}">
                    Historique
                </x-nav-link>
                @endauth

                <x-nav-link :active="request()->routeIs('contact')" href="{{ route('contact') }}">
                    Contact
                </x-nav-link>
            </div>

            <div class="flex items-center gap-3 text-base">
                <x-theme-toggle />
                @guest
                <x-button href="{{ route('login') }}" variant="secondary" class="max-[920px]:hidden">
                    Se connecter
                </x-button>

                <x-button href="{{ route('orders.create') }}" variant="primary" class="max-[920px]:hidden">
                    Lancer ma commande
                </x-button>
                @endguest

                @auth
                <x-action />
                @endauth

                <button type="button"
                    class="hidden h-10 w-10 cursor-pointer items-center justify-center rounded-xl border-0 bg-inverse/5 text-text max-[920px]:flex"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-label="Menu">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
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
                @guest
                <x-responsive-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}" @click="mobileOpen = false">
                    Accueil
                </x-responsive-nav-link>
                @endguest

                <x-responsive-nav-link :active="request()->routeIs('orders.create')" href="{{ route('orders.create') }}" @click="mobileOpen = false">
                    Services & Tarifs
                </x-responsive-nav-link>

                @auth
                <x-responsive-nav-link :active="request()->routeIs('client.orders.index')" href="{{ route('client.orders.index') }}" @click="mobileOpen = false">
                    Historique
                </x-responsive-nav-link>
                @endauth

                <x-responsive-nav-link :active="request()->routeIs('contact')" href="{{ route('contact') }}" @click="mobileOpen = false">
                    Contact
                </x-responsive-nav-link>
            </nav>
        </div>

        <div class="space-y-3 border-t border-muted/15 p-4">
            @guest
            <x-responsive-nav-link :active="request()->routeIs('login')" href="{{ route('login') }}" @click="mobileOpen = false">
                Se connecter
            </x-responsive-nav-link>
            <a href="{{ route('register') }}"
                class="mt-2 inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2"
                @click="mobileOpen = false">S'inscrire</a>
            @endguest

            @auth
            <div x-data="{ accountOpen: false }" class="relative">
                <button type="button" @click="accountOpen = !accountOpen"
                    :aria-expanded="accountOpen.toString()" aria-haspopup="true"
                    class="flex w-full items-center justify-between gap-3 rounded-xl border border-muted/30 bg-surface p-2 text-left transition hover:border-text">
                    <span class="flex min-w-0 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-inverse">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <span class="truncate text-sm font-medium text-text">{{ auth()->user()->name }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-muted transition-transform"
                        :class="{ 'rotate-180': accountOpen }"></i>
                </button>

                <div x-cloak x-show="accountOpen" x-transition @click.outside="accountOpen = false"
                    class="mt-2 overflow-hidden rounded-xl border border-muted/15 bg-content shadow-xl">
                    <a href="{{ route('profile.edit') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-text transition hover:bg-inverse/5">
                        <i class="fa-regular fa-user w-5 text-center text-muted"></i>
                        <span>Mon profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 border-t border-muted/10 px-3 py-2.5 text-left text-sm font-medium text-muted transition hover:bg-danger/10 hover:text-danger">
                            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </aside>
    </div>
</header>