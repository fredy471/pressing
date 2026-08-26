<x-admin-layout title="Tableau de bord">
    <h1 class="font-display font-bold text-2xl text-text">Tableau de bord</h1>
    <p class="text-muted text-sm mt-1">Vue d'ensemble de l'activité du pressing.</p>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
        <div class="bg-content rounded-xl p-5 border border-muted/10">
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Commandes (total)</p>
            <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-content rounded-xl p-5 border border-muted/10">
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Ce mois-ci</p>
            <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['orders_this_month'] }}</p>
        </div>
        <div class="bg-content rounded-xl p-5 border border-muted/10">
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Chiffre d'affaires</p>
            <p class="font-display font-bold text-3xl text-accent mt-2">{{ number_format($stats['revenue'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-content rounded-xl p-5 border border-muted/10">
            <p class="text-muted text-xs uppercase tracking-wide font-mono">Clients / Agents</p>
            <p class="font-display font-bold text-3xl text-text mt-2">{{ $stats['total_clients'] }} <span class="text-base text-muted font-sans">/ {{ $stats['total_agents'] }}</span></p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-content rounded-xl border border-muted/10 p-6">
            <h2 class="font-display font-semibold text-text mb-4">Dernières commandes</h2>
            <div class="space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="flex items-center justify-between py-2 border-b border-muted/10 last:border-0">
                        <div>
                            <p class="font-mono text-sm text-text">{{ $order->tracking_code }}</p>
                            <p class="text-xs text-muted">{{ $order->user->name }} — {{ $order->service->name }}</p>
                        </div>
                        <x-status-badge :status="$order->status" />
                    </div>
                @empty
                    <p class="text-muted text-sm">Aucune commande pour l'instant.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-content rounded-xl border border-muted/10 p-6">
            <h2 class="font-display font-semibold text-text mb-4">Répartition par statut</h2>
            <div class="space-y-2">
                @foreach ($ordersByStatus as $status => $count)
                    <div class="flex items-center justify-between text-sm">
                        <x-status-badge :status="$status" />
                        <span class="font-mono text-text">{{ $count }}</span>
                    </div>
                @endforeach
            </div>

            @if ($topService)
                <div class="mt-6 pt-4 border-t border-muted/10">
                    <p class="text-muted text-xs uppercase tracking-wide font-mono">Service le plus demandé</p>
                    <p class="font-display font-semibold text-text mt-1">{{ $topService->service->name }}</p>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>