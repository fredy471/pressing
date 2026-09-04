@extends('layouts.guest')
@section('title', 'Commande ' . $order->tracking_code)
@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">

    <!-- Fil d'Ariane -->
    <nav class="flex items-center gap-2 text-xs text-muted mb-6">
        <a href="/" class="hover:text-text transition-colors">Accueil</a>
        <i class="fa-solid fa-chevron-right text-[10px] opacity-60"></i>
        <a href="{{ route('client.orders.index') }}" class="hover:text-text transition-colors">Historique</a>
        <i class="fa-solid fa-chevron-right text-[10px] opacity-60"></i>
        <span class="text-text font-medium">Commande {{ $order->tracking_code }}</span>
    </nav>

    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-bold text-3xl text-text">Commande {{ $order->tracking_code }}</h1>
                <!-- Badge de statut dynamique -->
                <span class="text-xs font-semibold px-3 py-1 rounded-full 
                    @switch($order->statut ?? 'en_attente')
                        @case('completed') bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 @break
                        @case('processing') bg-accent/10 text-accent border border-accent/20 @break
                        @case('cancelled') bg-danger/10 text-danger border border-danger/20 @break
                        @default bg-muted/10 text-muted border border-muted/20
                    @endswitch">
                    {{ ucfirst($order->statut ?? 'En attente') }}
                </span>
            </div>
            <p class="text-muted text-sm mt-1.5">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('client.orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-muted/20 bg-content text-text text-sm font-medium hover:border-accent/40 transition">
                <i class="fa-solid fa-arrow-left text-xs"><span>Retour</span></i>
            </a>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Colonne de gauche : Articles et Prestation (8 colonnes) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Carte des articles -->
            <div class="bg-content rounded-2xl border border-muted/10 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-muted/10 bg-inverse/[0.01] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-accent"></span>
                        <h2 class="font-semibold text-base text-text">Articles commandés</h2>
                    </div>
                    @if($order->items->isNotEmpty() && $order->items->first()->service)
                    <span class="text-xs font-semibold text-accent bg-accent/10 px-3 py-1 rounded-full">
                        {{ $order->items->first()->service->name }}
                    </span>
                    @endif
                </div>

                <div class="divide-y divide-muted/10">
                    @forelse($order->items as $item)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-inverse/[0.01] transition-colors">
                        <div>
                            <p class="text-sm font-semibold text-text">{{ $item->clothesType->name ?? 'Article' }}</p>
                            <p class="text-xs text-muted mt-0.5">Quantité : <span class="font-medium text-text">{{ $item->quantity }}</span> × {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-text">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }}</span>
                            <span class="text-xs text-muted">FCFA</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-muted text-sm">
                        Aucun article trouvé pour cette commande.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Carte du mode de récupération -->
            <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm">
                <h2 class="font-semibold text-base text-text mb-4">Informations de récupération</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 rounded-xl border border-muted/15 bg-bg">
                        <p class="text-xs uppercase tracking-wider text-muted font-semibold mb-1">Mode choisi</p>
                        <p class="font-medium text-text">
                            {{ $order->delivery_mode === 'collecte_livraison' ? 'Collecte à domicile' : 'Dépôt en agence' }}
                        </p>
                    </div>

                    @if($order->delivery_mode === 'collecte_livraison')
                    <div class="p-4 rounded-xl border border-muted/15 bg-bg">
                        <p class="text-xs uppercase tracking-wider text-muted font-semibold mb-1">Téléphone de contact</p>
                        <p class="font-medium text-text">{{ $order->pickup_phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="sm:col-span-2 p-4 rounded-xl border border-muted/15 bg-bg">
                        <p class="text-xs uppercase tracking-wider text-muted font-semibold mb-1">Adresse de collecte</p>
                        <p class="font-medium text-text">{{ $order->pickup_adress ?? 'Non renseignée' }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Colonne de droite : Résumé financier (4 colonnes) -->
        <div class="lg:col-span-4 lg:sticky lg:top-6 space-y-4">
            <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-sm overflow-hidden">
                <h2 class="font-semibold text-base text-text pb-4 border-b border-muted/10">Résumé financier</h2>

                <div class="py-4 space-y-3 text-sm">
                    <div class="flex justify-between text-muted">
                        <span>Sous-total articles :</span>
                        <span class="text-text font-medium">{{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between text-muted">
                        <span>Frais de service :</span>
                        <span class="text-text font-medium">0 FCFA</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-muted/10 flex items-center justify-between mb-6">
                    <span class="text-sm font-medium text-muted">Total général</span>
                    <span class="font-bold text-xl text-text">
                        {{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} <span class="text-xs font-normal text-muted">FCFA</span>
                    </span>
                </div>

                <!-- Actions contextuelles -->
                @if(($order->statut ?? 'en_attente') === 'en_attente')
                <a href="{{ route('client.orders.index') }}"
                    class="w-full text-text bg-inverse/5 hover:bg-inverse/10 font-medium text-sm py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Retour à l'historique</span>
                </a>
                @else
                <div class="text-center p-3 rounded-xl bg-inverse/5 text-muted text-xs">
                    Cette commande ne peut plus être modifiée.
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection