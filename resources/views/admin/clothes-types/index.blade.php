@extends('layouts.admin')
@section('title', 'Types de vêtements')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="font-display font-bold text-xl md:text-2xl text-text">Types de vêtements</h1>
        <p class="text-muted text-sm mt-1">Les articles pouvant être pris en charge par le pressing.</p>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-xs font-mono bg-inverse/20 text-muted px-3 py-1.5 rounded-xl">
            Total : <strong class="text-text font-semibold">{{ $count }}</strong>
        </span>
        <a href="{{ route('admin.clothes-types.create') }}"
            class="bg-accent text-inverse font-medium text-sm px-5 py-2.5 rounded-full hover:opacity-90 transition shadow-sm">
            + Nouveau type
        </a>
    </div>
</div>

<!-- Conteneur principal minimaliste -->
<div class="bg-content rounded-2xl border border-muted/10 mt-8 overflow-hidden shadow-sm">

    <!-- En-tête du tableau (Desktop uniquement) -->
    <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3.5 bg-inverse/20 text-muted text-xs font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
        <div class="col-span-4">Nom</div>
        <div class="col-span-4">Catégorie</div>
        <div class="col-span-2">Statut</div>
        <div class="col-span-2 text-right">Action</div>
    </div>

    <!-- Liste des types de vêtements -->
    <div class="divide-y divide-muted/10">
        @forelse ($clothesTypes as $type)
        <div class="p-5 lg:px-6 lg:py-4 flex flex-col lg:grid lg:grid-cols-12 lg:items-center gap-4 hover:bg-inverse/[0.02] transition-colors">

            <!-- Nom & Statut mobile -->
            <div class="lg:col-span-4 flex items-center justify-between lg:justify-start">
                <span class="font-semibold text-text text-sm md:text-base">{{ $type->name }}</span>
                <div class="lg:hidden">
                    <x-active-badge :isActive="$type->is_active" />
                </div>
            </div>

            <!-- Catégorie (Édition inline) -->
            <div class="lg:col-span-4">
                <span class="lg:hidden text-xs text-muted font-mono uppercase block mb-1">Catégorie :</span>
                <form method="POST" action="{{ route('admin.clothes-types.update-category', $type) }}"
                    class="flex items-center gap-2" x-data>
                    @csrf @method('PATCH')
                    <input type="text" name="category" value="{{ $type->category }}" placeholder="Aucune catégorie"
                        class="text-sm rounded-xl border-muted/20 bg-bg text-text placeholder:text-xs md:placeholder:text-sm w-full sm:w-40 py-1.5 px-3 focus:border-accent focus:ring-accent shadow-inner">
                    <button class="text-xs md:text-sm text-accent font-semibold hover:underline px-2 py-1 bg-accent/10 rounded-lg">OK</button>
                </form>
            </div>

            <!-- Statut (Desktop uniquement) -->
            <div class="hidden lg:block lg:col-span-2">
                <x-active-badge :isActive="$type->is_active" />
            </div>

            <!-- Action -->
            <div class="lg:col-span-2 flex justify-end pt-2 lg:pt-0 border-t border-muted/10 lg:border-t-0">
                <form method="POST" action="{{ route('admin.clothes-types.toggle-active', $type) }}"
                    x-data
                    @submit.prevent="if (confirm('{{ $type->is_active ? 'Désactiver' : 'Activer' }} le vêtement {{ $type->name }} ?')) $el.submit()">
                    @csrf @method('PATCH')
                    <button class="text-xs md:text-sm font-medium text-accent hover:underline bg-accent/10 px-3.5 py-1.5 rounded-lg transition">
                        {{ $type->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
            </div>

        </div>
        @empty
        <div class="p-8 text-center">
            <p class="text-muted text-xs md:text-sm">Aucun type de vêtement trouvé</p>
        </div>
        @endforelse
    </div>
</div>

<!-- N.B. informatif sur l'impact des statuts -->
<div class="mt-4 p-4 text-[11px] md:text-xs text-muted space-y-1">
    N.B :
    <p class="mt-2">  
     <strong class="text-text">Désactiver</strong> un type de vêtement le masquera pour les clients lors de leurs nouvelles commandes, tout en préservant l'historique des commandes existantes.</p>

    <p> <strong class="text-text">Activer</strong> le rendra immédiatement disponible à nouveau sur la plateforme.</p>
</div>

<div class="mt-3 flex items-center justify-between gap-4 px-4 pb-4">
    <div class="text-[11px] md:text-xs text-muted">
        @if ($clothesTypes->total())
        Affichage de {{ $clothesTypes->firstItem() }} à {{ $clothesTypes->lastItem() }} sur {{ $clothesTypes->total() }} types de vêtements
        @else
        Aucun type de vêtement
        @endif
    </div>

    @if ($clothesTypes->hasPages())
    <div class="flex justify-end">
        {{ $clothesTypes->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection