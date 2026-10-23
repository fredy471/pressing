@extends('layouts.agent')
@section('title', 'Détail de la commande ' . $order->tracking_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- En-tête : Code de suivi et retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-display font-bold text-2xl text-text font-mono tracking-tight">{{ $order->tracking_code }}</h1>
                <x-status-badge :status="$order->status" />
            </div>
            <p class="text-muted text-sm mt-1">
                Client : <span class="text-text font-medium">{{ $order->user->name }}</span>
                <span class="text-muted/50 mx-2">•</span>
                Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>

        <div>
            <a href="{{ route('agent.orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-inverse/5 text-text hover:bg-inverse/10 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Retour aux commandes</span>
            </a>
        </div>
    </div>

    <!-- Grille d'informations (Détails & Livraison) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Articles de la commande (2 colonnes) -->
        <div class="md:col-span-2 bg-content rounded-2xl border border-muted/10 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-mono uppercase tracking-wider text-muted/70 font-semibold mb-4">Articles commandés</h3>

                <div class="divide-y divide-muted/15">
                    @foreach ($order->items as $item)
                    <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-accent/10 text-accent flex items-center justify-center font-semibold text-xs shrink-0">
                                {{ $item->quantity }}x
                            </div>
                            <div>
                                <span class="text-sm font-medium text-text block">{{ $item->clothesType->name }}</span>
                                <span class="text-xs text-muted font-mono"><i class="fa-solid fa-tag text-[10px] mr-1"></i>{{ $item->service->name ?? 'Service standard' }}</span>
                            </div>
                        </div>

                        @if(isset($item->subtotal))
                        <span class="text-sm font-mono font-semibold text-text">{{ number_format($item->subtotal, 0, ',', ' ') }} F</span>
                        @elseif(isset($item->price))
                        <span class="text-sm font-mono font-semibold text-text">{{ number_format($item->price, 0, ',', ' ') }} F</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes de la commande (si la colonne notes existe sur ton modèle Order) -->
            @if(!empty($order->notes))
            <div class="mt-6 pt-4 border-t border-muted/10">
                <p class="text-xs font-semibold text-muted mb-1">Notes du client :</p>
                <p class="text-sm text-text italic bg-inverse/5 p-3 rounded-xl">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Informations de Livraison / Mode (1 colonne) -->
        <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-xs space-y-4">
            <h3 class="text-xs font-mono uppercase tracking-wider text-muted/70 font-semibold">Mode de service</h3>

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid {{ $order->delivery_mode === 'collecte_livraison' ? 'fa-truck-fast' : 'fa-store' }}"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-text">
                        {{ $order->delivery_mode === 'collecte_livraison' ? 'Collecte & Livraison' : 'Dépôt en pressing' }}
                    </p>
                    <p class="text-xs text-muted mt-0.5">
                        {{ $order->delivery_mode === 'collecte_livraison' ? 'À l\'adresse du client' : 'Sur place' }}
                    </p>
                </div>
            </div>

            @if ($order->delivery_mode === 'collecte_livraison' && $order->pickup_address)
            <div class="pt-4 border-t border-muted/10">
                <p class="text-xs font-semibold text-muted mb-1">Adresse de récupération :</p>
                <p class="text-sm text-text bg-inverse/5 p-3 rounded-xl flex items-start gap-2">
                    <i class="fa-solid fa-location-dot text-accent mt-1 shrink-0"></i>
                    <span>{{ $order->pickup_address }}</span>
                </p>
            </div>
            @endif
        </div>

    </div>

    <!-- Section Actions / Workflow de Statut -->
    <div class="bg-content rounded-2xl border border-muted/10 p-6 shadow-xs">
        <h3 class="text-xs font-mono uppercase tracking-wider text-muted/70 font-semibold mb-4">Actions disponibles</h3>

        @php
        $currentTransitions = $order->status->nextStatuses();
        @endphp

        @if (empty($currentTransitions))
        <p class="text-sm text-muted italic">
            Aucune action disponible pour le moment
            (Commande finalisée ou annulée).
        </p>
        @else
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($currentTransitions as $nextStatus)
            <form method="POST"
                action="{{ route('agent.orders.update-status', $order) }}">
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="{{ $nextStatus->value }}">

                <button
                    type="submit"
                    class="{{ $nextStatus === \App\Enums\OrderStatus::ANNULEE
                        ? 'bg-danger/10 text-danger hover:bg-danger/20 border border-danger/20'
                        : 'bg-accent text-inverse shadow-md shadow-accent/20 hover:opacity-90' }}
                        text-sm font-medium px-5 py-2.5 rounded-xl
                        transition-all duration-200 flex items-center gap-2">
                    {{ $nextStatus->actionLabel() }}
                </button>
            </form>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection