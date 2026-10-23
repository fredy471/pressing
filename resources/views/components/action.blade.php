@props(['mobile' => false])

@auth
@php
$isAdmin = auth()->user()->hasRole('admin');
// SOLUTION : On utilise 'bottom-full mb-2' pour que le menu s'ouvre VERS LE HAUT
// et reste bien à l'intérieur de l'écran (puisque le bouton est en bas de la sidebar).
$dropdownPosition = $mobile
? 'bottom-full left-0 mb-2 w-full'
: 'bottom-full right-0 mb-2 w-64';
@endphp

<div x-data="{ accountOpen: false }" @click.outside="accountOpen = false"
    class="relative z-50 font-sans {{ $mobile ? 'w-full' : 'w-full' }}">

    <button
        type="button"
        @click="accountOpen = !accountOpen"
        :aria-expanded="accountOpen.toString()"
        aria-haspopup="true"
        class="flex items-center justify-between w-full rounded-2xl border border-muted/30 bg-surface p-2 transition hover:border-text cursor-pointer">

        <div class="flex items-center gap-2.5 truncate">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-xs font-bold text-inverse shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>

            <div class="text-left truncate">
                <p class="truncate text-sm font-medium text-text">
                    {{ auth()->user()->name }}
                </p>
                <p class="truncate text-xs text-muted">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <i class="fa-solid fa-chevron-down text-[10px] text-muted transition-transform shrink-0 ml-2"
            :class="{ 'rotate-180': accountOpen }"></i>
    </button>

    <div x-cloak x-show="accountOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 overflow-hidden rounded-2xl border border-muted/15 bg-content shadow-xl {{ $dropdownPosition }}">

        <div class="border-b border-muted/10 px-4 py-3">
            <p class="truncate text-sm font-semibold text-text">
                {{ auth()->user()->name }}
            </p>

            <p class="mt-0.5 truncate text-xs text-muted">
                {{ auth()->user()->email }}
            </p>
        </div>

        <div class="p-2">
            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-text transition hover:bg-inverse/5">
                <i class="fa-regular fa-user w-5 text-center text-muted"></i>
                <span>Mon profil</span>
            </a>

            @if(auth()->user()->hasRole('client'))
            <a
                href="{{ route('client.orders.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-text transition hover:bg-inverse/5">
                <i class="fa-solid fa-receipt w-5 text-center text-muted"></i>
                <span>Mes commandes</span>
            </a>
            @endif
        </div>

        <div class="border-t border-muted/10 p-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition hover:bg-danger/10 hover:text-danger cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span>Se déconnecter</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endauth