@props(['isActive' => false])

@if ($isActive)
    <span class="inline-flex items-center gap-1.5 text-emerald-500 text-xs font-medium px-2.5 py-1 rounded-full">● Actif</span>
@else
    <span class="inline-flex items-center gap-1.5 text-muted text-xs font-medium bg-muted/10 px-2.5 py-1 rounded-full">● Inactif</span>
@endif
