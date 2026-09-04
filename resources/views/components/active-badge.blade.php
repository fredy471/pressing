@props(['isActive' => false])

@if ($isActive)
    <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-500">● Actif</span>
@else
    <span class="inline-flex items-center gap-1.5 rounded-md bg-muted/10 px-2.5 py-1 text-xs font-medium text-muted">● Inactif</span>
@endif
