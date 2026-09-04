@extends('layouts.guest')
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