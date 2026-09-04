@props(['active'])

@php
$classes = ($active ?? false)
            ? 'border-accent border-b-2 text-text font-sans font-semibold gap-3  py-2 text-base transition-colors duration-150'
            : ' border-transparent text-text hover:text-accent font-sans gap-3 px-4 py-2.5 text-base transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>