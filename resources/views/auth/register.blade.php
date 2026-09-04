@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="w-full max-w-md mx-auto px-4 py-12">
        
        <!-- En-tête du formulaire -->
        <div class="text-center mb-8">
            <h1 class="font-display font-bold text-2xl text-text">Créer un compte</h1>
            <p class="text-text text-sm mt-1">Rejoignez notre pressing pour suivre vos commandes</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Nom complet -->
            <div>
                <label for="name" class="block text-sm font-medium text-text mb-1.5">{{ __('Nom complet') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-text mb-1.5">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Téléphone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-text mb-1.5">{{ __('Téléphone') }}</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('phone')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-text mb-1.5">{{ __('Mot de passe') }}</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text mb-1.5">{{ __('Confirmer le mot de passe') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full rounded-lg border-muted/20 bg-bg text-text focus:border-accent focus:ring-accent text-sm">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-500" />
            </div>

            <!-- Bouton d'inscription -->
            <button type="submit"
                class="w-full bg-accent text-inverse font-medium text-sm py-3 rounded-full hover:opacity-90 transition shadow-sm shadow-accent/20">
                {{ __("S'inscrire") }}
            </button>

            <!-- Lien de connexion -->
            <p class="text-center text-sm text-muted pt-2">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-accent font-medium hover:underline">Se connecter</a>
            </p>
        </form>
    </div>
@endsection