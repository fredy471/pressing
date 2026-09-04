@props(['active' => false])

@php
$classes = $active ? 'w-full rounded-sm border-b border-muted/20 bg-transparent px-0 py-3 text-left text-[0.95rem] text-accent transition hover:text-accent font-sans font-semibold border-accent'
: 'w-full rounded-sm border-b border-muted/20 bg-transparent px-0 py-3 text-left text-[0.95rem] text-text transition hover:text-accent font-sans';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>