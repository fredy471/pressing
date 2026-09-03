@extends('layouts.admin')
@section('title', 'Nouveau service')
@section('content')
    <h1 class="font-display font-bold text-xl md:text-2xl text-text">Nouveau service</h1>

    <form method="POST" action="{{ route('admin.services.store') }}" class="mt-8 max-w-lg space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Nom du service</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-text mb-1.5">Description</label>
            <textarea name="description" rows="3"
                      class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-accent text-inverse font-medium text-xs md:text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition">
                Créer le service
            </button>
            <a href="{{ route('admin.services.index') }}" class="text-muted text-xs md:text-sm px-6 py-2.5 hover:text-text transition">
                Annuler
            </a>
        </div>
    </form>
@endsection