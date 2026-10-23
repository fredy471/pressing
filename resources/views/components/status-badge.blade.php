@props(['status'])

@php
    $status = $status instanceof \App\Enums\OrderStatus
        ? $status
        : \App\Enums\OrderStatus::from($status);
@endphp

<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $status->badgeClasses() }}">
    {{ $status->label() }}
</span>