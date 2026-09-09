@props(['order'])

@php
    $status = $order->statut ?? 'en_attente';
    $stepIndex = match ($status) {
        'en_attente' => 0,
        'recu', 'confirmee' => 1,
        'en_lavage', 'lavage' => 2,
        'terminee', 'prete' => 3,
        'livree' => 4,
        default => 0,
    };
    $steps = ['En attente', 'Reçue', 'En lavage', 'Terminée', 'Livrée'];

    $serviceNames = $order->items->map(fn($item) => $item->service?->name)->filter()->unique();
    
    // On prend le premier service et on ajoute "..." s'il y en a plus d'un
    $firstService = $serviceNames->first() ?? 'Service Pressing';
    $prestationLabel = $serviceNames->count() > 1 ? $firstService . ' ...' : $firstService;
@endphp

<div class="relative z-10 flex flex-col justify-between rounded-xl border border-muted/20 bg-content p-0 text-text shadow-[0_30px_60px_-20px_rgba(0,0,0,0.35)]">
    <div class="flex items-start justify-between border-b border-dashed border-muted/30 px-[26px] pb-4 pt-[22px]">
        <div class="max-w-[70%]">
            <span class="block text-[10px] uppercase tracking-wider text-muted font-mono">Prestation(s)</span>
            <span class="mt-0.5 block text-base font-bold text-text truncate" title="{{ $serviceNames->implode(', ') }}">
                {{ $prestationLabel }}
            </span>
        </div>
        <div class="text-right font-[family-name:var(--mono)] text-[0.72rem] text-text">
            Code de suivi
            <b class="block text-[0.95rem] tracking-[0.04em] text-accent">{{ $order->tracking_code }}</b>
        </div>
    </div>

    <div class="space-y-1 px-[26px] py-5">
        <div class="flex justify-between py-[6px] text-[0.86rem] text-muted">
            <span>Articles</span>
            <b class="text-text">{{ $order->items->sum('quantity') }} pièce(s)</b>
        </div>
        <div class="flex justify-between py-[6px] text-[0.86rem] text-muted">
            <span>Date de dépôt</span>
            <b class="text-text">{{ $order->created_at->format('d/m/Y') }}</b>
        </div>
        <div class="flex justify-between py-[6px] text-[0.86rem] text-muted">
            <span>Montant total</span>
            <b class="text-text">{{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} FCFA</b>
        </div>

        <div class="mt-6 mb-2 flex items-center">
            @foreach ($steps as $index => $label)
                @php
                    $isPassed = $index < $stepIndex;
                    $isActive = $index === $stepIndex;
                    $isLast = $index === count($steps) - 1;
                @endphp
                <div class="group relative flex flex-1 flex-col items-center">
                    <div class="z-20 h-[11px] w-[11px] rounded-full transition-all {{ $isPassed || $isActive ? 'border-2 border-[var(--color-accent)] bg-[var(--color-accent)]' : 'border-2 border-[var(--color-muted)] bg-transparent' }}"></div>
                    @if (! $isLast)
                        <div class="absolute left-1/2 top-[5px] z-10 h-[2px] w-full {{ $index < $stepIndex ? 'bg-[var(--color-accent)]' : 'bg-[var(--color-muted)]/30' }}"></div>
                    @endif
                    <span class="mt-2 max-w-[60px] truncate text-center font-[family-name:var(--mono)] text-[0.55rem] text-muted">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-between rounded-b-xl border-t border-muted/10 bg-inverse/[0.03] px-[26px] py-[14px] font-[family-name:var(--mono)] text-[0.7rem] text-muted">
        <span>Statut : <strong class="uppercase text-text">{{ ucfirst($order->statut ?? 'En attente') }}</strong></span>
        <a href="{{ route('client.orders.show', $order) }}" class="flex items-center gap-1 font-semibold text-accent hover:underline">
            <span>Détails</span>
            <i class="fa-solid fa-arrow-right text-[9px]"></i>
        </a>
    </div>
</div>