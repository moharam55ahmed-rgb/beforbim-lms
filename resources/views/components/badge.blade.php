@props([
    'variant' => 'gold',
])

@php
    $variants = [
        'gold' => 'bg-amber-50 text-[#B38F24] border-[#D4AF37]/40 font-semibold',
        'navy' => 'bg-[#071A36] text-[#F3D98B] border-[#123B68] font-medium',
        'blue' => 'bg-blue-50 text-[#123B68] border-blue-200 font-medium',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200 font-medium',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200 font-medium',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-200 font-medium',
        'neutral' => 'bg-slate-100 text-slate-700 border-slate-200 font-medium',
    ];

    $dotColors = [
        'gold' => 'bg-[#D4AF37]',
        'navy' => 'bg-[#F3D98B]',
        'blue' => 'bg-[#123B68]',
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-rose-500',
        'neutral' => 'bg-slate-400',
    ];

    $classes = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs border ' . ($variants[$variant] ?? $variants['gold']);
    $dotClass = 'w-1.5 h-1.5 rounded-full ' . ($dotColors[$variant] ?? $dotColors['gold']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    <span class="{{ $dotClass }}"></span>
    {{ $slot }}
</span>
