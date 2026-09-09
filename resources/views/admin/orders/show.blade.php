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

    <!-- Colonne de gauche : Articles regroupés par service -->
    <div class="lg:col-span-2 bg-content rounded-2xl border border-muted/10 shadow-sm overflow-hidden p-6 space-y-6">
        <h2 class="font-display font-semibold text-text text-base md:text-lg">Articles commandés</h2>

        @php
            $itemsByService = $order->items->groupBy(fn($item) => $item->service->name ?? 'Autre service');
        @endphp

        <div class="space-y-6">
            @foreach ($itemsByService as $serviceName => $items)
            <div class="border border-muted/15 rounded-xl overflow-hidden bg-bg">
                <!-- En-tête du service -->
                <div class="bg-inverse/[0.03] px-4 py-3 border-b border-muted/15 flex items-center justify-between">
                    <span class="font-semibold text-xs md:text-sm text-text flex items-center gap-2">
                        <i class="fa-solid fa-tag text-accent text-xs"></i>
                        {{ $serviceName }}
                    </span>
                    <span class="text-[11px] font-mono text-muted">
                        {{ $items->sum('quantity') }} article(s)
                    </span>
                </div>

                <!-- Table des articles pour ce service -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs md:text-sm">
                        <thead>
                            <tr class="text-muted text-[10px] md:text-[11px] font-mono font-semibold uppercase tracking-wider border-b border-muted/10">
                                <th class="text-left py-2.5 px-4 font-semibold">Article</th>
                                <th class="text-center py-2.5 px-4 font-semibold">Qté</th>
                                <th class="text-right py-2.5 px-4 font-semibold">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-muted/10">
                            @foreach ($items as $item)
                            <tr class="hover:bg-inverse/[0.02] transition-colors">
                                <td class="py-3 px-4 text-text font-medium flex items-center gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span>
                                    {{ $item->clothesType->name }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-xs">
                                    <span class="px-2 py-0.5 rounded-md bg-accent/10 text-accent font-semibold">{{ $item->quantity }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-text">
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} F
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total de la commande -->
        <div class="flex items-center justify-between pt-4 border-t border-muted/10 font-display">
            <span class="text-text font-semibold text-base">Total global</span>
            <span class="font-mono font-bold text-base md:text-lg text-accent">{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
        </div>
    </div>

    <!-- Colonne de droite : Informations Générales -->
    <div class="bg-content rounded-2xl border border-muted/10 shadow-sm p-6 space-y-6">

        <!-- Prestations associées -->
        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Prestations incluses</p>
            <div class="mt-2 flex flex-wrap gap-1.5">
                @php
                    $uniqueServices = $order->items->map(fn($item) => $item->service?->name)->filter()->unique();
                @endphp
                @forelse($uniqueServices as $serviceName)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-accent/10 text-accent">
                    <i class="fa-solid fa-tag text-[10px]"></i>
                    {{ $serviceName }}
                </span>
                @empty
                <span class="text-muted italic text-sm">Aucun service</span>
                @endforelse
            </div>
            <p class="text-xs text-muted mt-2">Total de pièces : <strong class="text-text font-mono">{{ $order->items->sum('quantity') }}</strong></p>
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
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                    Payé
                </span>
            </div>
            @else
            <div class="p-3.5 rounded-xl bg-inverse/[0.02] border border-muted/10 flex items-center justify-between text-sm mt-2">
                <span class="text-muted italic">En attente</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-500">
                    En attente
                </span>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection