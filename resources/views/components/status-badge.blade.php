@php
    $colors = [
        'en_attente' => 'bg-muted/15 text-muted',
        'recue' => 'bg-accent2/10 text-accent2',
        'en_lavage' => 'bg-accent/10 text-accent',
        'terminee' => 'bg-accent2/15 text-accent2',
        'livree' => 'bg-accent2/20 text-accent2',
        'annulee' => 'bg-danger/10 text-danger',
    ];
    $labels = [
        'en_attente' => 'En attente', 'recue' => 'Reçue', 'en_lavage' => 'En lavage',
        'terminee' => 'Terminée', 'livree' => 'Livrée', 'annulee' => 'Annulée',
    ];
@endphp
<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors[$status] ?? 'bg-muted/15 text-muted' }}">
    {{ $labels[$status] ?? $status }}
</span>