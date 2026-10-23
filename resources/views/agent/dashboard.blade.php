@extends('layouts.agent')
@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">

    {{-- EN-TÊTE DE BIENVENUE --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-text">Bonjour, {{ auth()->user()->name }} </h1>
            <p class="text-sm text-muted mt-1">Voici un aperçu de l'activité du pressing aujourd'hui.</p>
        </div>
        <div>
            <a href="{{ route('agent.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-accent text-inverse shadow-sm hover:opacity-90 transition">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>Gérer les commandes</span>
            </a>
        </div>
    </div>

    {{-- CARTES DE STATISTIQUES --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- En attente -->
        <div class="bg-content rounded-2xl border border-muted/10 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-mono uppercase tracking-wider text-muted">En attente</p>
                <p class="text-2xl font-bold font-mono text-text mt-1">{{ $stats['en_attente'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning/10 text-warning flex items-center justify-center text-lg">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- En lavage -->
        <div class="bg-content rounded-2xl border border-muted/10 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-mono uppercase tracking-wider text-muted">En lavage</p>
                <p class="text-2xl font-bold font-mono text-text mt-1">{{ $stats['en_lavage'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent/10 text-accent flex items-center justify-center text-lg">
                <i class="fa-solid fa-water"></i>
            </div>
        </div>

        <!-- Terminées -->
        <div class="bg-content rounded-2xl border border-muted/10 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-mono uppercase tracking-wider text-muted">Terminées</p>
                <p class="text-2xl font-bold font-mono text-text mt-1">{{ $stats['terminee'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Chiffre d'affaires du jour -->
        <div class="bg-content rounded-2xl border border-muted/10 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-mono uppercase tracking-wider text-muted">CA du jour</p>
                <p class="text-xl font-bold font-mono text-text mt-1">{{ number_format($stats['ca_jour'] ?? 0, 0, ',', ' ') }} F</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent/10 text-accent flex items-center justify-center text-lg">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>
    </div>

    {{-- DERNIÈRES COMMANDES RÉCENTES --}}
    <div class="bg-content rounded-2xl border border-muted/10 overflow-hidden shadow-xs">
        <div class="p-6 border-b border-muted/10 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-text">Dernières commandes</h2>
                <p class="text-xs text-muted mt-0.5">Les 5 dernières commandes enregistrées sur la plateforme.</p>
            </div>
            <a href="{{ route('agent.orders.index') }}" class="text-xs font-medium text-accent hover:underline">Voir tout</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-mono">Code</th>
                        <th class="text-left px-6 py-3 font-mono">Client</th>
                        <th class="text-left px-6 py-3 font-mono">Statut</th>
                        <th class="text-right px-6 py-3 font-mono">Total</th>
                        <th class="text-right px-6 py-3 font-mono">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                    @forelse ($recentOrders ?? [] as $order)
                    <tr>
                        <td class="px-6 py-4 font-mono text-text">{{ $order->tracking_code }}</td>
                        <td class="px-6 py-4 text-text">{{ $order->user->name }}</td>
                        <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                        <td class="px-6 py-4 text-right font-mono font-semibold text-text">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} F
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('agent.orders.show', $order) }}" class="text-accent font-medium hover:underline">Traiter</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-muted italic">Aucune commande récente pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection