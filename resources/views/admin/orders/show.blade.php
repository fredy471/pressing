@extends('layouts.admin')
@section('title', 'Détails de la commande')
@section('content')

<!-- Bouton Retour -->
<a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-text text-sm hover:text-accent transition-colors mb-4 group">
    <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
    <span class="font-display font-medium">Retour aux commandes</span>
</a>

<!-- En-tête : Code & Statut -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
    <div>
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="font-display font-bold text-xl md:text-2xl text-text font-mono">{{ $order->tracking_code }}</h1>
            <x-status-badge :statut="$order->statut" />
        </div>
        <div class="text-sm text-muted mt-2 flex flex-wrap gap-x-4 gap-y-1">
            <p><span class="text-text font-medium">Client :</span> {{ $order->user->name }}</p>
            <p><span class="text-text font-medium">Date :</span> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</div>

<!-- Grille Principale -->
<div class="grid lg:grid-cols-3 gap-6 mt-8">
    
    <!-- Colonne de gauche : Articles -->
    <div class="lg:col-span-2 bg-content rounded-2xl border border-muted/10 shadow-sm overflow-hidden p-6">
        <h2 class="font-display font-semibold text-text text-base md:text-lg mb-4">Articles commandés</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm">
                <thead>
                    <tr class="bg-inverse/20 text-muted text-[11px] md:text-xs font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
                        <th class="text-left py-3.5 px-4 font-semibold">Article</th>
                        <th class="text-center py-3.5 px-4 font-semibold">Qté</th>
                        <th class="text-right py-3.5 px-4 font-semibold">Sous-total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                    @foreach ($order->items as $item)
                    <tr class="hover:bg-inverse/[0.02] transition-colors">
                        <td class="py-4 px-4 text-text font-medium flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span>
                            {{ $item->clothesType->name }}
                        </td>
                        <td class="py-4 px-4 text-center font-mono text-xs">
                            <span class="px-2.5 py-1 rounded-lg bg-accent/10 text-accent font-semibold">{{ $item->quantity }}</span>
                        </td>
                        <td class="py-4 px-4 text-right font-mono font-semibold text-text">
                            {{ number_format($item->subtotal, 0, ',', ' ') }} F
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total de la commande -->
        <div class="flex items-center justify-between pt-5 mt-4 border-t border-muted/10 font-display">
            <span class="text-text font-semibold text-base">Total global</span>
            <span class="font-mono font-bold text-sm md:text-base text-accent">{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
        </div>
    </div>

    <!-- Colonne de droite : Informations Générales -->
    <div class="bg-content rounded-2xl border border-muted/10 shadow-sm p-6 space-y-6">
        
        <!-- Service -->
        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Service principal</p>
            <div class="text-text font-medium mt-1">
                @if($order->items && $order->items->isNotEmpty())
                <span class="inline-flex items-center gap-1.5 text-accent font-semibold">
                    <i class="fa-solid fa-tag text-xs"></i>
                    {{ $order->items->first()->service->name ?? 'N/A' }}
                </span>
                @if($order->items->count() > 1)
                <span class="text-xs text-muted block mt-1"> {{ $order->items->count() }}  article(s)</span>
                @endif
                @else
                <span class="text-muted italic">Aucun service</span>
                @endif
            </div>
        </div>

        <hr class="border-muted/10">

        <!-- Agent assigné -->
        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Agent assigné</p>
            <p class="text-text font-medium mt-1">{{ $order->agent->name ?? '— Non assigné —' }}</p>
        </div>

        <hr class="border-muted/10">

        <!-- Paiement -->
        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Paiement</p>

            @if ($successfulPayment)
            <div class="p-3.5 rounded-xl bg-inverse/[0.02] border border-muted/10 flex items-center justify-between text-sm mt-2">
                <span class="font-mono font-bold text-text">
                    {{ number_format($order->payments->amount ?? $order->total_amount, 0, ',', ' ') }} F
                </span>
                <!-- <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                    Payé
                </span> -->
            </div>
            @else
            <div class="p-3.5 rounded-xl bg-inverse/[0.02] border border-muted/10 flex items-center justify-between text-sm mt-2">
                <span class="text-muted italic">En attente</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold  text-amber-500">
                    ----
                </span>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection