@extends('layouts.admin')
@section('title', 'Details de commandes')
@section('content')
<a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-text text-sm hover:text-text transition-colors mb-4">
    <i class="fa-solid fa-arrow-left text-base"> <span class="font-display">Retour</span></i>
</a>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="font-display font-bold text-2xl text-text font-mono">{{ $order->tracking_code }}</h1>
            <x-status-badge :statut="$order->statut" />
        </div>
        <div class="text-sm mt-2 flex flex-col gap-1">
            <p class=""> <span class="font-bold">Client </span>: {{ $order->user->name }} </p>
            <p class=""> <span class="font-bold">Date </span>: {{ $order->created_at->format('d/m/Y') }} </p>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mt-8">
    <!-- Colonne de gauche : Articles -->
    <div class="lg:col-span-2 bg-content rounded-2xl border border-muted/10 shadow-sm overflow-hidden p-6">
        <h2 class="font-display font-semibold text-text mb-4">Articles</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-inverse/[0.03] text-muted text-[11px] font-mono uppercase tracking-wider border-b border-muted/10">
                        <th class="text-left py-3 px-4 font-semibold">Article</th>
                        <th class="text-center py-3 px-4 font-semibold">Qté</th>
                        <th class="text-right py-3 px-4 font-semibold">Sous-total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                    @foreach ($order->items as $item)
                    <tr class="hover:bg-inverse/[0.01] transition-colors">
                        <td class="py-3.5 px-4 text-text font-medium flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                            {{ $item->clothesType->name }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-mono text-muted text-xs">
                            <span class="px-2 py-0.5 rounded-md bg-accent text-inverse font-display">{{ $item->quantity }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono font-semibold text-text">
                            {{ number_format($item->subtotal, 0, ',', ' ') }} F
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between pt-5 mt-4 border-t border-muted/10 font-display font-semibold text-text">
            <span>Total</span>
            <span class="font-mono font-bold text-lg text-accent">{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
        </div>
    </div>

    <!-- Colonne de droite : Informations Générales -->
    <div class="bg-content rounded-2xl border border-muted/10 shadow-sm p-6 space-y-6">
        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Service</p>
            <div class="text-text font-medium mt-1">
                @if($order->items && $order->items->isNotEmpty())
                <span class="inline-flex items-center gap-1.5 text-accent font-semibold">
                    <i class="fa-solid fa-tag text-xs"></i>
                    {{ $order->items->first()->service->name ?? 'N/A' }}
                </span>
                @if($order->items->count() > 1)
                <span class="text-xs text-muted block mt-1">(+{{ $order->items->count() - 1 }})</span>
                @endif
                @else
                <span class="text-muted/60 italic">Aucun service</span>
                @endif
            </div>
        </div>

        <hr class="border-muted/10">

        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Agent assigné</p>
            <p class="text-text font-medium mt-1">{{ $order->agent->name ?? '—' }}</p>
        </div>

        <hr class="border-muted/10">

        <div>
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Paiement</p>

            @if ($successfulPayment)
            <div class="p-3 rounded-xl bg-inverse/[0.02] border border-muted/10 flex items-center justify-between text-sm mt-1">
                <span class="font-mono font-bold text-text">
                    {{ number_format($order->payments->amount, 0, ',', ' ') }} F
                </span>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-600">
                    {{ $order->payment->statut }}
                </span>
            </div>
            @else
            <p class="text-muted mt-1">
                Aucun paiement enregistré
            </p>
            @endif
        </div>
    </div>
</div>
@endsection