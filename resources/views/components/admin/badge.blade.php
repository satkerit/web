@props([
    'variant' => 'default',
    'size' => 'md'
])

@php
    $variants = [
        'default'   => 'bg-slate-50 text-slate-600 ring-slate-500/10',
        'primary'   => 'bg-blue-50 text-blue-700 ring-blue-600/10',
        'secondary' => 'bg-purple-50 text-purple-700 ring-purple-600/10',
        'success'   => 'bg-green-50 text-green-700 ring-green-600/20',
        'warning'   => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'danger'    => 'bg-red-50 text-red-700 ring-red-600/10',
        'info'      => 'bg-blue-50 text-blue-700 ring-blue-600/10',
        'dark'      => 'bg-slate-900 text-slate-100 ring-slate-800',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-[10px] leading-4',
        'md' => 'px-2.5 py-1 text-xs font-semibold',
        'lg' => 'px-3 py-1 text-sm font-semibold',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center rounded-lg ring-1 ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['md'])
]) }}>
    {{ $slot }}
</span>
