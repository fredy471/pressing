<x-admin-layout title="Commandes">
    <h1 class="font-display font-bold text-2xl text-text">Toutes les commandes</h1>

    <form method="GET" class="flex flex-wrap gap-3 mt-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un code de suivi..."
               class="rounded-lg border-muted/20 bg-content text-sm w-64">
        <select name="status" class="rounded-lg border-muted/20 bg-content text-sm" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            @foreach (['en_attente', 'recue', 'en_lavage', 'terminee', 'livree', 'annulee'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <button class="text-accent text-sm font-medium">Filtrer</button>
    </form>

    <div class="bg-content rounded-xl border border-muted/10 mt-6 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-inverse/40 text-muted text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-3 font-mono">Code</th>
                    <th class="text-left px-6 py-3 font-mono">Client</th>
                    <th class="text-left px-6 py-3 font-mono">Service</th>
                    <th class="text-left px-6 py-3 font-mono">Statut</th>
                    <th class="text-right px-6 py-3 font-mono">Montant</th>
                    <th class="text-right px-6 py-3 font-mono">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-6 py-4 font-mono text-text">{{ $order->tracking_code }}</td>
                        <td class="px-6 py-4 text-text">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-muted">{{ $order->service->name }}</td>
                        <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                        <td class="px-6 py-4 text-right font-mono text-text">{{ number_format($order->total_amount, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-accent text-sm font-medium hover:underline">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
</x-admin-layout>