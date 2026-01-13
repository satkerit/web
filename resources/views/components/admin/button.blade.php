@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98] shadow-sm';

    $variants = [
        'primary' => 'bg-emerald-600 text-white hover:bg-emerald-500 focus:ring-emerald-500 hover:shadow-emerald-500/20 hover:shadow-lg border border-transparent',
        'secondary' => 'bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-500 border border-slate-200 hover:border-slate-300',
        'danger' => 'bg-red-600 text-white hover:bg-red-500 focus:ring-red-500 hover:shadow-red-500/20 hover:shadow-lg border border-transparent',
        'outline' => 'bg-transparent border border-slate-300 text-slate-700 hover:bg-slate-50 focus:ring-slate-500',
        'ghost' => 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 focus:ring-slate-500 shadow-none hover:shadow-none border border-transparent',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'lg' => 'px-6 py-3 text-base gap-2.5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <svg class="w-4 h-4 {{ $size === 'lg' ? 'w-5 h-5' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <svg class="w-4 h-4 {{ $size === 'lg' ? 'w-5 h-5' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
        @endif
        {{ $slot }}
    </button>
@endif
