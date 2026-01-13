{{--
    Lazy Image Component
    Usage: <x-lazy-image :src="$imagePath" alt="Description" class="w-full h-48" />
--}}

@props([
    'src' => null,
    'storagePath' => null,
    'alt' => '',
    'class' => '',
    'containerClass' => '',
    'fallbackIcon' => 'photo',
    'fallbackBg' => 'from-gray-100 to-gray-200',
    'aspectRatio' => '4/3',
    'objectFit' => 'cover',
    'priority' => false,
])

@php
    use Illuminate\Support\Facades\Storage;

    // Determine the image source
    $imageSrc = $src;
    if ($storagePath && !$src) {
        $imageSrc = Storage::url($storagePath);
    }

    $hasImage = !empty($imageSrc);
    $loadingAttr = $priority ? 'eager' : 'lazy';
    $fetchPriority = $priority ? 'high' : 'auto';

    // Generate placeholder
    $placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect fill='%23f3f4f6' width='100%25' height='100%25'/%3E%3C/svg%3E";
@endphp

<div class="relative overflow-hidden {{ $containerClass }}" style="aspect-ratio: {{ $aspectRatio }};">
    @if($hasImage)
        <img
            src="{{ $imageSrc }}"
            alt="{{ $alt }}"
            loading="{{ $loadingAttr }}"
            decoding="async"
            fetchpriority="{{ $fetchPriority }}"
            class="absolute inset-0 w-full h-full transition-all duration-300 {{ $class }}"
            style="object-fit: {{ $objectFit }}; background-image: url('{{ $placeholder }}'); background-size: cover;"
            onload="this.style.backgroundImage='none'; this.classList.add('loaded');"
            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'absolute inset-0 w-full h-full bg-gradient-to-br {{ $fallbackBg }} flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>';"
        >
    @else
        {{-- Fallback placeholder --}}
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br {{ $fallbackBg }} flex items-center justify-center">
            @if($fallbackIcon === 'photo')
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            @elseif($fallbackIcon === 'building')
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            @elseif($fallbackIcon === 'user')
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            @else
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
