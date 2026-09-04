@extends('layouts.app')
@section('title', 'connexion')
@section('content')
    <div class="w-full max-w-md mx-auto px-4 py-12">
        
        <!-- En-tête du formulaire -->
        <div class="text-center mb-8">
            <h1 class="font-display font-bold text-2xl text-text">Connexion</h1>
            <p class="text-text text-sm mt-1">Accédez à votre espace</p>
        </div>

        <!-- Statut de session / Erreurs -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-text mb-1.5">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-text mb-1.5">{{ __('Mot de passe') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-muted/30 text-accent focus:ring-accent">
                    <span class="ms-2 text-muted select-none">{{ __('Se souvenir de moi') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-accent hover:underline">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
            </div>

            <!-- Bouton de connexion -->
            <button type="submit"
                class="w-full bg-accent text-inverse font-medium text-sm py-3 rounded-full hover:opacity-90 transition">
                {{ __('Se connecter') }}
            </button>

            <!-- Lien d'inscription -->
            @if (Route::has('register'))
                <p class="text-center text-sm text-muted pt-2">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-accent font-medium hover:underline">Créer un compte</a>
                </p>
            @endif
        </form>
    </div>
@endsection