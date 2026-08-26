<x-admin-layout title="Détail commande">
    <a href="{{ route('admin.orders.index') }}" class="text-muted text-sm hover:text-text">← Retour</a>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="font-display font-bold text-2xl text-text font-mono">{{ $order->tracking_code }}</h1>
            <p class="text-muted text-sm mt-1">{{ $order->user->name }} — {{ $order->created_at->format('d/m/Y') }}</p>
        </div>
        <x-status-badge :status="$order->status" />
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-content rounded-xl border border-muted/10 p-6">
            <h2 class="font-display font-semibold text-text mb-4">Articles</h2>
            <table class="w-full text-sm">
                <thead class="text-muted text-xs uppercase">
                    <tr><th class="text-left pb-2">Article</th><th class="text-center pb-2">Qté</th><th class="text-right pb-2">Sous-total</th></tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="py-2 text-text">{{ $item->clothesType->name }}</td>
                            <td class="py-2 text-center text-muted">{{ $item->quantity }}</td>
                            <td class="py-2 text-right font-mono text-text">{{ number_format($item->subtotal, 0, ',', ' ') }} F</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between pt-4 mt-4 border-t border-muted/10 font-display font-semibold text-text">
                <span>Total</span>
                <span>{{ number_format($order->total_amount, 0, ',', ' ') }} F</span>
            </div>
        </div>

        <div class="bg-content rounded-xl border border-muted/10 p-6 space-y-4">
            <div>
                <p class="text-muted text-xs uppercase tracking-wide font-mono">Service</p>
                <p class="text-text font-medium mt-1">{{ $order->service->name }}</p>
            </div>
            <div>
                <p class="text-muted text-xs uppercase tracking-wide font-mono">Agent assigné</p>
                <p class="text-text font-medium mt-1">{{ $order->agent->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-muted text-xs uppercase tracking-wide font-mono">Paiement</p>
                @forelse ($order->payments as $payment)
                    <p class="text-text font-medium mt-1">{{ number_format($payment->amount, 0, ',', ' ') }} F — {{ $payment->status }}</p>
                @empty
                    <p class="text-muted mt-1">Aucun paiement enregistré</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>