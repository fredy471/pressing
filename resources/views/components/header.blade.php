<header class="sticky top-0 z-50 bg-bg border-b border-muted/30 text-text items-center justify-between">
    <div x-data="{ mobileOpen: false }" class="container mx-auto px-4 md:px-8 pt-4 pb-4">
        <nav class="flex h-[64px] items-center justify-between px-4 h-20">
            <x-pressing-logo />

            <div class="flex items-center gap-8 max-[920px]:hidden">
                <x-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}">
                    Accueil
                </x-nav-link>

                <x-nav-link :active="request()->routeIs('services')" href="{{ route('services') }}">
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

                <x-button href="{{ route('register') }}" variant="primary" class="max-[920px]:hidden">
                    Créer un compte
                </x-button>
                @endguest

                @auth
                <a href="{{ route('client.dashboard') }}" class="inline-flex cursor-pointer items-center gap-2 whitespace-nowrap rounded-sm border border-muted px-5 py-2.5 text-[0.86rem] font-semibold text-text transition duration-200 hover:border-text max-[920px]:hidden">Mon compte</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="inline-flex cursor-pointer items-center gap-2 whitespace-nowrap rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2">Déconnexion</button>
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

        <!-- Menu Mobile -->
        <div
            id="mobileNav"
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-y-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-y-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-y-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-y-95"
            style="display: none;"
            style="display: none;"
            class="flex flex-col gap-2 border-t border-muted/30 py-3 pb-[18px] overflow-hidden origin-top min-[920px]:hidden">

            <x-responsive-nav-link :active="request()->routeIs('home')" href="{{ route('home') }}">
                Accueil
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('services')" href="{{ route('services') }}">
                Services & Tarifs
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('contact')" href="{{ route('contact') }}">
                Contact
            </x-responsive-nav-link>

            @guest
            <x-responsive-nav-link :active="request()->routeIs('login')" href="{{ route('login') }}">
                Se connecter
            </x-responsive-nav-link>
            <a href="{{ route('register') }}" class="inline-flex cursor-pointer items-center justify-center gap-2 whitespace-nowrap rounded-sm border border-transparent bg-accent px-5 py-2.5 text-[0.86rem] font-semibold text-inverse transition duration-200 hover:bg-accent2" @click="mobileOpen = false;">S'inscrire</a>
            @endguest
        </div>
    </div>
</header>