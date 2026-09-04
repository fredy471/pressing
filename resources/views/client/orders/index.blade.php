@extends('layouts.app')
@section('title', 'Mon historique de commandes')
@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">
    
    <!-- Fil d'Ariane -->
    <nav class="flex items-center gap-2 text-xs text-muted mb-6">
        <a href="/" class="hover:text-text transition-colors">Accueil</a>
        <i class="fa-solid fa-chevron-right text-[10px] opacity-60"></i>
        <span class="text-text font-medium">Historique des commandes</span>
    </nav>

    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <h1 class="font-bold text-3xl text-text">Mes commandes</h1>
            <p class="text-muted text-sm mt-1.5">Retrouvez l'historique et le suivi de toutes vos prestations de pressing.</p>
        </div>

        <a href="{{ route('orders.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-accent text-inverse font-medium text-sm hover:opacity-95 transition shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Nouvelle commande</span>
        </a>
    </div>

    @if ($latestOrder)
        <section class="mb-10 rounded-2xl border border-accent/20 bg-accent/5 p-5 sm:p-6" aria-labelledby="latest-order-title">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-accent">Votre dernière commande</p>
                    <h2 id="latest-order-title" class="mt-1 font-display text-xl font-bold text-text">Suivez-la en un coup d’œil</h2>
                </div>
                <i class="fa-solid fa-clock-rotate-left text-xl text-accent" aria-hidden="true"></i>
            </div>

            <div class="grid gap-4 text-sm sm:grid-cols-4">
                <div>
                    <span class="block text-xs text-muted">Code de suivi</span>
                    <strong class="mt-1 block font-mono text-accent">{{ $latestOrder->tracking_code }}</strong>
                </div>
                <div>
                    <span class="block text-xs text-muted">Service</span>
                    <strong class="mt-1 block text-text">{{ $latestOrder->items->first()?->service?->name ?? 'Service Pressing' }}</strong>
                </div>
                <div>
                    <span class="block text-xs text-muted">Articles</span>
                    <strong class="mt-1 block text-text">{{ $latestOrder->items->sum('quantity') }} pièce(s)</strong>
                </div>
                <div>
                    <span class="block text-xs text-muted">Statut</span>
                    <strong class="mt-1 block uppercase text-text">{{ str_replace('_', ' ', $latestOrder->statut ?? 'en_attente') }}</strong>
                </div>
            </div>

            <div class="mt-5 flex flex-col items-start justify-between gap-4 border-t border-accent/15 pt-4 sm:flex-row sm:items-center">
                <strong class="font-display text-lg text-text">{{ number_format($latestOrder->total_amount ?? 0, 0, ',', ' ') }} FCFA</strong>
                <a href="{{ route('client.orders.show', $latestOrder) }}" class="inline-flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-sans font-semibold text-inverse transition hover:opacity-90">
                    Voir les détails
                    <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>
        </section>
    @endif

    <!-- Grille ou Liste des tickets -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($orders as $order)
        <x-order-ticket-card :order="$order" />
        @empty
        <!-- État vide (Aucune commande) -->
        <div class="col-span-full bg-content rounded-2xl border border-muted/10 p-12 text-center space-y-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-accent/10 text-accent flex items-center justify-center mx-auto text-lg">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            <div>
                <h3 class="font-semibold text-base text-text">Aucune commande pour le moment</h3>
                <p class="text-xs text-muted mt-1">Vous n'avez pas encore passé de commande de pressing chez nous.</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('orders.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-accent text-inverse font-medium text-sm hover:opacity-95 transition shadow-sm">
                    Lancer ma première commande
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($orders, 'links'))
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif

</div>

@endsection