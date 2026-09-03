@extends('layouts.admin')

@section('title', 'Nouvel agent')

@section('content')
<div class=" flex items-start justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <h1 class="font-display font-bold text-xl md:text-2xl text-text mb-3">Nouvel utilisateur interne</h1>
        <form method="POST" action="{{ route('admin.users.store') }}" class="sapce-y-5">
            @csrf

            <div class="py-2">
                <label class="block text-sm font-medium text-text text-start mb-1.5">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm" placeholder="desire doe">
            </div>

            <div  class="py-2">
                <label class="block text-sm font-medium text-text text-start mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm" placeholder="doe@gmail.com">
            </div>

            <div  class="py-2">
                <label class="block text-sm font-medium text-text mb-1.5 text-start">Téléphone</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required
                    class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm placeholder:text-xs md:placeholder:text-sm" placeholder="01 XX XX XX XX">
            </div>

            <div  class="py-2">
                <label class="block text-sm font-medium text-text mb-1.5 text-start">Rôle</label>
                <select name="role" class="w-full rounded-lg border-muted/20 bg-content focus:border-accent focus:ring-accent text-sm">
                    <option value="agent">Agent</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex gap-3 pt-3">
                <button type="submit" class="bg-accent text-inverse font-medium text-xs md:text-sm px-6 py-2.5 rounded-full hover:opacity-90 transition">
                    Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-muted text-xs md:text-sm px-6 py-2.5 hover:text-text transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection