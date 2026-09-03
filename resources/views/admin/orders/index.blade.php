@extends('layouts.admin')
@section('title', 'Toutes les commandes')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <h1 class="font-display font-bold text-xl md:text-2xl text-text">Toutes les commandes</h1>
</div>

<!-- Filtres de recherche -->
<form method="GET" class="flex flex-wrap items-center gap-3 mt-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un code de suivi..."
        class="rounded-xl border-muted/20 bg-content text-text text-sm placeholder:text-xs md:placeholder:text-sm px-4 py-2.5 w-full sm:w-64 focus:border-accent focus:ring-accent shadow-xs">
    
    <select name="status" class="rounded-xl border-muted/20 bg-content text-text text-sm px-4 py-2.5 focus:border-accent focus:ring-accent shadow-xs" onchange="this.form.submit()">
        <option value="">Tous les statuts</option>
        @foreach (['en_attente', 'recue', 'en_lavage', 'terminee', 'livree', 'annulee'] as $status)
        <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
        @endforeach
    </select>

    <button class="bg-accent/10 text-accent font-medium text-sm px-5 py-2.5 rounded-xl hover:bg-accent/20 transition">
        Filtrer
    </button>
</form>

<!-- Conteneur principal minimaliste -->
<div class="bg-content rounded-2xl border border-muted/10 mt-6 overflow-hidden shadow-sm">
    
    <!-- En-tête du tableau (Desktop) -->
    <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3.5 bg-inverse/20 text-muted text-[11px] md:text-xs font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
        <div class="col-span-2">Code</div>
        <div class="col-span-2">Client</div>
        <div class="col-span-3">Service (nbre art)</div>
        <div class="col-span-2">Statut</div>
        <div class="col-span-2 text-right">Montant</div>
        <div class="col-span-1 text-right">Action</div>
    </div>

    <!-- Liste des commandes -->
    <div class="divide-y divide-muted/10">
        @forelse ($orders as $order)
        <div class="p-5 lg:px-6 lg:py-4 flex flex-col lg:grid lg:grid-cols-12 lg:items-center gap-4 hover:bg-inverse/[0.02] transition-colors">
            
            <!-- Code & Statut mobile -->
            <div class="lg:col-span-2 flex items-center justify-between lg:justify-start">
                <span class="font-mono font-semibold text-text text-sm">{{ $order->tracking_code }}</span>
                <div class="lg:hidden">
                    <x-status-badge :statut="$order->statut" />
                </div>
            </div>

            <!-- Client -->
            <div class="lg:col-span-2 text-xs md:text-sm text-text">
                <span class="lg:hidden text-xs text-muted font-mono uppercase block mb-0.5">Client :</span>
                {{ $order->user->name }}
            </div>

            <!-- Service (nbre art) -->
            <div class="lg:col-span-3 text-xs md:text-sm text-text">
                <span class="lg:hidden text-xs text-muted font-mono uppercase block mb-0.5">Service :</span>
                @if($order->items && $order->items->isNotEmpty())
                    {{ $order->items->first()->service->name ?? 'N/A' }}
                    @if($order->items->count() > 1)
                    <span class="text-xs text-accent font-medium">({{ $order->items->count() }})</span>
                    @endif
                @else
                <span class="italic text-muted">Aucun service</span>
                @endif
            </div>

            <!-- Statut (Desktop uniquement) -->
            <div class="hidden lg:block lg:col-span-2">
                <x-status-badge :statut="$order->statut" />
            </div>

            <!-- Montant -->
            <div class="lg:col-span-2 lg:text-right font-mono font-semibold text-text text-xs md:text-sm flex items-center justify-between lg:justify-end">
                <span class="lg:hidden text-xs text-muted font-mono uppercase">Montant :</span>
                <span>{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
            </div>

            <!-- Action -->
            <div class="lg:col-span-1 flex justify-end pt-2 lg:pt-0 border-t border-muted/10 lg:border-t-0">
                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-medium text-accent hover:underline bg-accent/10 px-3.5 py-1.5 rounded-lg transition">
                    Voir
                </a>
            </div>

        </div>
        @empty
        <div class="p-8 text-center">
            <p class="text-muted text-sm">Aucun élément trouvé</p>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-3 flex items-center justify-between gap-4 px-4 pb-4">
    <div class="text-[11px] md:text-xs text-muted">
        @if ($orders->total())
        Affichage de {{ $orders->firstItem() }} à {{ $orders->lastItem() }} sur {{ $orders->total() }} commandes
        @else
        Aucune commande
        @endif
    </div>

    @if ($orders->hasPages())
    <div class="flex justify-end">
        {{ $orders->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection