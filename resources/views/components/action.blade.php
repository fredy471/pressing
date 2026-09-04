@auth
<div x-data="{ accountOpen: false }" class="relative font-sans max-[920px]:hidden">

    <button
        type="button"
        @click="accountOpen = !accountOpen"
        @click.outside="accountOpen = false"
        :aria-expanded="accountOpen.toString()"
        aria-haspopup="true"
        class="flex items-center gap-2 rounded-full border border-muted/30 bg-surface p-1 pr-3 transition hover:border-text"
    >
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-xs font-bold text-inverse">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </span>

        <span class="max-w-28 truncate text-sm font-medium">
            {{ auth()->user()->name }}
        </span>

        <i
            class="fa-solid fa-chevron-down text-[10px] text-muted transition-transform"
            :class="{ 'rotate-180': accountOpen }"
        ></i>
    </button>

    <div
        x-cloak
        x-show="accountOpen"
        x-transition
        class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-muted/15 bg-content shadow-xl"
    >

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
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-text transition hover:bg-inverse/5"
            >
                <i class="fa-regular fa-user w-5 text-center text-muted"></i>
                <span>Mon profil</span>
            </a>

            @if(auth()->user()->hasRole('client'))
                <a
                    href="{{ route('client.orders.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-text transition hover:bg-inverse/5"
                >
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
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition hover:bg-danger/10 hover:text-danger"
                >
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span>Se déconnecter</span>
                </button>
            </form>

        </div>
    </div>
</div>
@endauth