@extends('layouts.admin')
@section('title', 'services')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="font-display font-bold text-xl md:text-2xl text-text">Services</h1>
        <p class="text-muted text-sm mt-1">Gérez les prestations proposées par votre pressing.</p>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-xs font-mono bg-inverse/20 text-muted px-3 py-1.5 rounded-xl">
            Total : <strong class="text-text font-semibold">{{ $count }}</strong>
        </span>
        <a href="{{ route('admin.services.create') }}"
            class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition text-center shadow-sm">
            + Nouveau service
        </a>
    </div>
</div>

<!-- Conteneur principal minimaliste -->
<div class="bg-content rounded-2xl border border-muted/10 mt-8 overflow-hidden shadow-sm">

    <!-- En-tête du tableau (masqué sur mobile, visible sur desktop) -->
    <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3.5 bg-inverse/20 text-muted text-[11px] md:text-xs font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
        <div class="col-span-3">Nom</div>
        <div class="col-span-5">Description</div>
        <div class="col-span-2">Statut</div>
        <div class="col-span-2 text-right">Action</div>
    </div>

    <!-- Liste des services (Responsive Grid / Table row) -->
    <div class="divide-y divide-muted/10">
        @foreach ($services as $service)
        <div class="p-5 lg:px-6 lg:py-4 flex flex-col lg:grid lg:grid-cols-12 lg:items-center gap-4 hover:bg-inverse/[0.02] transition-colors">

            <!-- Nom du service -->
            <div class="lg:col-span-3 flex items-center justify-between lg:justify-start">
                <span class="font-semibold text-text text-sm">{{ $service->name }}</span>
                <!-- Badge statut visible uniquement sur mobile -->
                <div class="lg:hidden">
                    <x-active-badge :isActive="$service->is_active" />
                </div>
            </div>

            <!-- Description (Édition inline au clic) -->
            <div class="lg:col-span-5" x-data="{ editing: false }">
                <div x-show="!editing" @click="editing = true" class="cursor-pointer group py-1">
                    <p class="text-xs md:text-sm text-text group-hover:text-text transition-colors leading-relaxed">
                        {{ $service->description ?: 'Cliquez ici pour ajouter une description...' }}
                    </p>
                </div>

                <form x-show="editing" x-cloak
                    method="POST" action="{{ route('admin.services.update-description', $service) }}"
                    @submit="editing = false"
                    class="flex flex-col gap-2">
                    @csrf
                    @method('PATCH')
                    <textarea name="description" rows="2" autofocus
                        class="w-full text-sm rounded-xl border-muted/20 bg-bg text-text placeholder:text-xs md:placeholder:text-sm focus:border-accent focus:ring-accent p-2.5 shadow-inner">{{ $service->description }}</textarea>
                    <div class="flex gap-3">
                        <button type="submit" class="text-xs md:text-sm text-accent font-semibold hover:underline">Enregistrer</button>
                        <button type="button" @click="editing = false" class="text-xs md:text-sm text-text hover:underline">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Statut (Desktop uniquement) -->
            <div class="hidden lg:block lg:col-span-2">
                <x-active-badge :isActive="$service->is_active" />
            </div>

            <!-- Action -->
            <div class="lg:col-span-2 flex justify-end lg:justify-end pt-2 lg:pt-0 border-t border-muted/10 lg:border-t-0">
                <form method="POST" action="{{ route('admin.services.toggle-active', $service) }}"
                    x-data
                    @submit.prevent="if (confirm('{{ $service->is_active ? 'Désactiver' : 'Activer' }} ce service ?')) $el.submit()">
                    @csrf @method('PATCH')
                    <button class="text-xs md:text-sm font-medium text-accent hover:underline bg-accent/10 px-3.5 py-1.5 rounded-lg transition">
                        {{ $service->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>
</div>

<!-- N.B. et Pagination -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-6">
    <p class="text-[11px] md:text-xs text-text leading-relaxed font-medium">
        N B : Vous pouvez modifier directement la description d'un service en cliquant simplement dessus.
    </p>
</div>

<div class="mt-3 flex items-center justify-between gap-4 px-4 pb-4">
    <div class="text-[11px] md:text-xs text-muted">
        @if ($services->total())
        Affichage de {{ $services->firstItem() }} à {{ $services->lastItem() }} sur {{ $services->total() }} services
        @else
        Aucun service
        @endif
    </div>

    @if ($services->hasPages())
    <div class="flex justify-end">
        {{ $services->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection