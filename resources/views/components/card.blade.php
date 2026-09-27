@props([
    'padding' => 'p-6',
    'elevated' => true,
    'variant' => 'default', // 'default', 'navy', 'blue', 'glass'
])

@php
    $variants = [
        'default' => 'bg-white rounded-2xl border border-slate-200/80 ' . ($elevated ? 'shadow-sm hover:shadow-md transition-all duration-200' : ''),
        'navy' => 'bg-[#071A36] text-white rounded-2xl border border-[#D4AF37]/30 shadow-xl shadow-black/20 hover:border-[#D4AF37]/60 transition-all duration-300',
        'blue' => 'bg-[#123B68] text-white rounded-2xl border border-white/10 shadow-lg hover:border-[#D4AF37]/40 transition-all duration-300',
        'glass' => 'navy-glass text-white rounded-2xl border border-[#D4AF37]/30 shadow-2xl transition-all duration-300',
    ];
    $selectedVariant = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => $selectedVariant . ' ' . $padding]) }}>
    {{ $slot }}
</div>

