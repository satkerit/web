@props([
    'src',
    'alt' => '',
    'class' => '',
    'lazy' => true,
    'width' => null,
    'height' => null,
    'sizes' => null,
    'priority' => false,
    'aspectRatio' => null,
    'objectFit' => 'cover',
])

@php
    use App\Services\ImageOptimizationService;

    $loadingAttr = $priority ? 'eager' : ($lazy ? 'lazy' : 'eager');
    $fetchPriority = $priority ? 'high' : 'auto';

    // Generate blur placeholder
    $w = $width ?? 400;
    $h = $height ?? 300;
    $placeholder = ImageOptimizationService::blurPlaceholder($w, $h);

    // Build inline styles
    $styles = [];
    $styles[] = "background-image: url('{$placeholder}')";
    $styles[] = "background-size: cover";
    $styles[] = "background-position: center";

    if ($aspectRatio) {
        $styles[] = "aspect-ratio: {$aspectRatio}";
    }

    if ($objectFit) {
        $styles[] = "object-fit: {$objectFit}";
    }

    $styleString = implode('; ', $styles);
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="{{ $loadingAttr }}"
    decoding="async"
    fetchpriority="{{ $fetchPriority }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    @if($sizes) sizes="{{ $sizes }}" @endif
    style="{{ $styleString }}"
    {{ $attributes->merge(['class' => $class . ' transition-opacity duration-300']) }}
    onload="this.style.backgroundImage='none'; this.classList.add('loaded');"
    onerror="this.onerror=null; this.classList.add('img-error');"
>
