@extends('layouts.admin')
@section('title', 'Nouveau type de vetement')
@section('content')
    <h1 class="font-display font-bold text-xl md:text-2xl text-text">Nouveau type de vêtement</h1>

    <form method="POST" action="{{ route('admin.clothes-types.store') }}" class="mt-8 max-w-lg space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Catégorie <span class="text-muted font-normal">(optionnel)</span></label>
            <input type="text" name="category" value="{{ old('category') }}" placeholder="Ex : Haut, Bas, Accessoire"
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-accent text-inverse font-medium text-xs md:text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition">
                Créer
            </button>
            <a href="{{ route('admin.clothes-types.index') }}" class="text-muted text-xs md:text-sm px-6 py-2.5 hover:text-text transition">
                Annuler
            </a>
        </div>
    </form>
@endsection