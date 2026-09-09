@extends('layouts.agent')
@section('title', 'Tableau de bord')
@section('content')

<h1 class="font-display font-bold text-2xl text-text">Tableau de bord</h1>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
    <div class="bg-content rounded-xl p-5 border border-muted/10">
        <p class="text-muted text-xs uppercase tracking-wide font-mono">En attente</p>
        <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['en_attente'] }}</p>
    </div>
    <div class="bg-content rounded-xl p-5 border border-muted/10">
        <p class="text-muted text-xs uppercase tracking-wide font-mono">Reçues</p>
        <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['recue'] }}</p>
    </div>
    <div class="bg-content rounded-xl p-5 border border-muted/10">
        <p class="text-muted text-xs uppercase tracking-wide font-mono">En lavage</p>
        <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['en_lavage'] }}</p>
    </div>
    <div class="bg-danger/5 rounded-xl p-5 border border-danger/20">
        <p class="text-danger text-xs uppercase tracking-wide font-mono">Urgentes (délai proche)</p>
        <p class="font-display font-bold text-3xl text-danger mt-2">{{ $stats['urgentes'] }}</p>
    </div>
</div>

<div class="bg-content rounded-xl border border-muted/10 mt-8 p-6">
    <h2 class="font-display font-semibold text-text mb-4">À traiter</h2>
    <div class="space-y-3">
        @forelse ($recentOrders as $order)
            <a href="{{ route('agent.orders.show', $order) }}" class="flex items-center justify-between py-2 border-b border-muted/10 last:border-0 hover:bg-inverse/30 -mx-2 px-2 rounded-lg transition">
                <div>
                    <p class="font-mono text-sm text-text">{{ $order->tracking_code }}</p>
                    <p class="text-xs text-muted">{{ $order->user->name }}</p>
                </div>
                <x-status-badge :status="$order->status" />
            </a>
        @empty
            <p class="text-muted text-sm">Aucune commande à traiter.</p>
        @endforelse
    </div>
</div>
@endsection