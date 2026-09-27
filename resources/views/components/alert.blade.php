@props([
    'type' => 'info',
])

@php
    $types = [
        'success' => 'bg-emerald-50 border-emerald-300 text-emerald-800',
        'warning' => 'bg-amber-50 border-amber-300 text-amber-800',
        'danger' => 'bg-rose-50 border-rose-300 text-rose-800',
        'info' => 'bg-blue-50 border-blue-300 text-blue-800',
    ];

    $classes = 'p-4 rounded-xl border text-sm flex items-start gap-3 ' . ($types[$type] ?? $types['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex-1">
        {{ $slot }}
    </div>
</div>
