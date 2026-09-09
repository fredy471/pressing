@php
    $layout = match(true) {
        auth()->user()->hasRole('admin') => 'layouts.admin',
        auth()->user()->hasRole('agent') => 'layouts.agent',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Mon profil')
@section('content')
<div class="mx-auto w-full max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    {{-- EN-TÊTE --}}
    <div class="mb-8">
        <div class="mb-3 flex items-center gap-2 text-sm text-muted">
            <a href="{{ url()->previous() }}" class="transition hover:text-text"> Retour </a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span>Mon profil</span>
        </div>

        <h1 class="text-2xl font-bold text-text sm:text-3xl">Mon profil</h1>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-muted">
            Consultez les informations de votre compte et gérez
            vos coordonnées ainsi que votre sécurité.
        </p>
    </div>

    {{-- MESSAGE SUCCÈS PROFIL --}}
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-success/20 bg-success/10 px-4 py-3 text-sm text-success">
        <i class="fa-solid fa-circle-check mt-0.5"></i>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    {{-- INFORMATIONS DU COMPTE --}}
    <section class="rounded-2xl border border-muted/20 bg-content shadow-sm">
        <div class="border-b border-muted/15 p-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent/10 text-accent">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-text">Informations du compte</h2>
                    <p class="text-sm text-muted">Ces informations sont gérées par le système.</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-muted/10">
            <div class="flex flex-col gap-2 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-muted">Nom</p>
                    <p class="mt-1 text-sm font-medium text-text">{{ $user->name }}</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-lg bg-inverse/5 px-3 py-1.5 text-xs text-muted">
                    <i class="fa-solid fa-lock text-[10px]"></i> Non modifiable
                </span>
            </div>

            {{-- EMAIL --}}
            <div class="flex flex-col gap-2 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-muted">Adresse email</p>
                    <p class="mt-1 text-sm font-medium text-text">{{ $user->email }}</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-lg bg-inverse/5 px-3 py-1.5 text-xs text-muted">
                    <i class="fa-solid fa-lock text-[10px]"></i> Non modifiable
                </span>
            </div>

            {{-- RÔLE --}}
            <div class="flex flex-col gap-2 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-muted">Rôle</p>
                    <p class="mt-1 text-sm font-medium capitalize text-text">{{ $user->getRoleNames()->first() ?? 'Utilisateur' }}</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-lg bg-inverse/5 px-3 py-1.5 text-xs text-muted">
                    <i class="fa-solid fa-lock text-[10px]"></i> Non modifiable
                </span>
            </div>
        </div>
    </section>

    {{-- COORDONNÉES --}}
    <section class="mt-6 rounded-2xl border border-muted/20 bg-content shadow-sm">
        <div class="border-b border-muted/15 p-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent/10 text-accent">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-text">Mes coordonnées</h2>
                    <p class="text-sm text-muted">
                        Vous pouvez modifier votre téléphone et votre domicile.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="p-6">
            @csrf
            @method('PATCH')
            <div class="grid gap-6 md:grid-cols-2">
                {{-- TÉLÉPHONE --}}
                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-text">Numéro de téléphone</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" autocomplete="tel" placeholder="+229 XX XX XX XX" class="w-full rounded-xl border border-muted/30 bg-surface py-3 pl-11 pr-4 text-sm text-text outline-none transition placeholder:text-muted/60 focus:border-accent focus:ring-2 focus:ring-accent/10">
                    </div>

                    @error('phone')
                    <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DOMICILE --}}
                <div>
                    <label for="domicile" class="mb-2 block text-sm font-medium text-text">Domicile</label>
                    <div class="relative">
                        <i class="fa-solid fa-house absolute left-4 top-4 text-sm text-muted"></i>
                        <textarea id="domicile" name="domicile" rows="1" autocomplete="street-address" placeholder="Votre domicile" class="min-h-[48px] w-full resize-none rounded-xl border border-muted/30 bg-surface py-3 pl-11 pr-4 text-sm text-text outline-none transition placeholder:text-muted/60 focus:border-accent focus:ring-2 focus:ring-accent/10">{{ old('domicile', $user->domicile) }}</textarea>
                    </div>

                    @error('domicile')
                    <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end border-t border-muted/15 pt-6">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-accent px-5 py-3 text-sm font-semibold text-inverse transition hover:bg-accent2">
                    <i class="fa-solid fa-check"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </section>

    {{-- SÉCURITÉ --}}
    <section class="mt-6 rounded-2xl border border-muted/20 bg-content shadow-sm">
        <div class="border-b border-muted/15 p-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent/10 text-accent">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-text">Sécurité</h2>
                    <p class="text-sm text-muted">
                        Protégez votre compte en gardant votre mot de passe à jour.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if(session('password_success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-success/20 bg-success/10 px-4 py-3 text-sm text-success">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <p>{{ session('password_success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- MOT DE PASSE ACTUEL --}}
                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-text">
                        Mot de passe actuel
                    </label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                        class="w-full rounded-xl border border-muted/30 bg-surface px-4 py-3 text-sm text-text outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/10">

                    @error('current_password')
                    <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    {{-- NOUVEAU MOT DE PASSE --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-text">Nouveau mot de passe</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full rounded-xl border border-muted/30 bg-surface px-4 py-3 text-sm text-text outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/10">

                        @error('password')
                        <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONFIRMATION --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-text">Confirmer le nouveau mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full rounded-xl border border-muted/30 bg-surface px-4 py-3 text-sm text-text outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/10">
                    </div>
                </div>

                <div class="flex justify-end border-t border-muted/15 pt-6">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-muted/30 px-5 py-3 text-sm font-semibold text-text transition hover:bg-inverse/5">
                        <i class="fa-solid fa-key"></i> Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection