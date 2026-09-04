@props([
    'type' => 'button',
    'variant' => 'primary',
    'href' => null,
])

@php
$variants = [
    'primary' => 'bg-accent hover:bg-accent2 font-bold text-inverse transition duration-200 hover:bg-accent-2',
    'secondary' => 'bg-transparent border border-muted text-text hover:border-text font-semibold transition duration-200',
];

$selectedVariant = $variants[$variant] ?? $variants['primary'];
$tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($tag === 'button') type="{{ $type }}" @else href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex cursor-pointer items-center gap-2 whitespace-nowrap px-5 py-2.5 text-[0.86rem] font-sans transition duration-200 ' . $selectedVariant]) }}>
    {{ $slot }}
</{{ $tag }}>