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
        @php
            // Détermination de l'étape active pour le stepper (index de 0 à 4)
            // Adapte ces statuts selon les valeurs réelles de ta base de données
            $status = $order->statut ?? 'en_attente';
            $stepIndex = match($status) {
                'en_attente' => 0,
                'recu', 'confirmee' => 1,
                'en_lavage', 'lavage' => 2,
                'terminee', 'prete' => 3,
                'livree' => 4,
                default => 0
            };
        @endphp

        <div class="relative z-10 bg-content text-text rounded-xl shadow-[0_30px_60px_-20px_rgba(0,0,0,0.35)] hover:rotate-0 transition-transform duration-300 -rotate-[1.0deg] p-0 border border-muted/20 flex flex-col justify-between">

            <!-- En-tête du ticket -->
            <div class="px-[26px] pt-[22px] pb-4 border-b border-dashed border-muted/30 flex justify-between items-start">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-muted font-mono block">Prestation</span>
                    <span class="font-bold text-base text-text mt-0.5 block">{{ $order->items->first()?->service?->name ?? 'Service Pressing' }}</span>
                </div>
                <div class="font-[family-name:var(--mono)] text-[0.72rem] text-text text-right">
                    Code de suivi<b class="block text-[0.95rem] tracking-[0.04em] text-accent">{{ $order->tracking_code }}</b>
                </div>
            </div>

            <!-- Corps du ticket -->
            <div class="px-[26px] py-5 space-y-1">
                <div class="flex justify-between text-[0.86rem] py-[6px] text-muted">
                    <span>Articles</span><b class="text-text">{{ $order->items->sum('quantity') }} pièce(s)</b>
                </div>
                <div class="flex justify-between text-[0.86rem] py-[6px] text-muted">
                    <span>Date de dépôt</span><b class="text-text">{{ $order->created_at->format('d/m/Y') }}</b>
                </div>
                <div class="flex justify-between text-[0.86rem] py-[6px] text-muted">
                    <span>Montant total</span><b class="text-text">{{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} FCFA</b>
                </div>

                <!-- Stepper dynamique -->
                <div class="flex items-center mt-6 mb-2">
                    @php
                        $steps = ['En attente', 'Reçue', 'En lavage', 'Terminée', 'Livrée'];
                    @endphp

                    @foreach($steps as $index => $label)
                        @php
                            $isPassed = $index < $stepIndex;
                            $isActive = $index === $stepIndex;
                            $isLast = $index === count($steps) - 1;
                        @endphp
                        <div class="flex flex-col items-center flex-1 relative group">
                            <!-- Point du stepper -->
                            <div class="w-[11px] h-[11px] rounded-full z-20 transition-all
                                {{ $isPassed || $isActive ? 'bg-[var(--color-accent)] border-2 border-[var(--color-accent)]' : 'bg-transparent border-2 border-[var(--color-muted)]' }}">
                            </div>

                            <!-- Ligne de liaison -->
                            @if(!$isLast)
                                <div class="absolute top-[5px] left-1/2 w-full h-[2px] z-10 
                                    {{ $index < $stepIndex ? 'bg-[var(--color-accent)]' : 'bg-[var(--color-muted)]/30'}}">
                                </div>
                            @endif

                            <span class="font-[family-name:var(--mono)] text-[0.55rem] mt-2 text-center text-muted max-w-[60px] truncate">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pied du ticket avec lien vers les détails -->
            <div class="px-[26px] py-[14px] bg-inverse/[0.03] font-[family-name:var(--mono)] text-[0.7rem] text-muted flex justify-between items-center border-t border-muted/10 rounded-b-xl">
                <span>Statut : <strong class="text-text uppercase">{{ ucfirst($order->statut ?? 'En attente') }}</strong></span>
                <a href="{{ route('client.orders.show', $order) }}" class="text-accent hover:underline font-semibold flex items-center gap-1">
                    <span>Détails</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>
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