@props([
    'variant' => 'navy',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-sm';

    $variants = [
        'navy' => 'bg-[#071A36] text-white hover:bg-[#123B68] focus:ring-[#D4AF37] border border-[#123B68] shadow-md shadow-[#071A36]/15',
        'primary' => 'bg-[#071A36] text-white hover:bg-[#123B68] focus:ring-[#D4AF37] border border-[#123B68] shadow-md shadow-[#071A36]/15',
        'gold' => 'bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-[#B38F24] text-[#071A36] font-bold hover:brightness-105 hover:shadow-md hover:shadow-[#D4AF37]/30 focus:ring-[#D4AF37] border border-[#F3D98B]/50',
        'outline-gold' => 'bg-transparent text-[#B38F24] hover:text-[#071A36] border border-[#D4AF37] hover:bg-[#D4AF37]/10 focus:ring-[#D4AF37]',
        'secondary' => 'bg-[#123B68] text-white hover:bg-[#1A4E88] focus:ring-[#123B68] border border-transparent shadow-sm',
        'outline' => 'bg-transparent text-slate-700 hover:bg-slate-100 focus:ring-slate-400 border border-slate-300',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500 border border-transparent',
    ];

    $sizes = [
        'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
        'md' => 'px-5 py-2.5 text-sm gap-2',
        'lg' => 'px-7 py-3.5 text-base gap-2.5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['navy']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
