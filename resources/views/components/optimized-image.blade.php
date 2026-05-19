@props([
    'src',
    'alt' => '',
    'class' => '',
    'lazy' => true,
    'width' => null,
    'height' => null,
    'sizes' => '100vw',
    'priority' => false,
    'aspectRatio' => null,
    'objectFit' => 'cover',
])

@php
    use App\Services\ImageCompressionService;
    use App\Helpers\StorageHelper;

    $loadingAttr = $priority ? 'eager' : ($lazy ? 'lazy' : 'eager');
    $fetchPriority = $priority ? 'high' : 'auto';
    $decodingAttr = $priority ? 'sync' : 'async';

    // Extract path from full URL if needed
    $imagePath = $src;
    if (str_contains($src, '/storage/')) {
        $imagePath = str_replace('/storage/', '', parse_url($src, PHP_URL_PATH));
    }

    // Get responsive WebP versions
    $webpVersions = ImageCompressionService::getExistingResponsiveWebP($imagePath);
    $compressedSrc = ImageCompressionService::getExistingCompressed($imagePath);

    // Build inline styles for aspect ratio and CLS prevention
    $styles = [];
    
    if ($aspectRatio) {
        $styles[] = "aspect-ratio: {$aspectRatio}";
    }

    if ($objectFit) {
        $styles[] = "object-fit: {$objectFit}";
    }

    $styleString = !empty($styles) ? implode('; ', $styles) : '';
@endphp

@if(!empty($webpVersions))
{{-- Use picture element for responsive WebP --}}
<picture>
    {{-- WebP sources for different breakpoints --}}
    @if(isset($webpVersions['mobile']))
    <source 
        media="(max-width: 640px)"
        srcset="{{ StorageHelper::url($webpVersions['mobile']) }}"
        type="image/webp">
    @endif
    
    @if(isset($webpVersions['tablet']))
    <source 
        media="(min-width: 641px) and (max-width: 1024px)"
        srcset="{{ StorageHelper::url($webpVersions['tablet']) }}"
        type="image/webp">
    @endif
    
    @if(isset($webpVersions['desktop']))
    <source 
        media="(min-width: 1025px)"
        srcset="{{ StorageHelper::url($webpVersions['desktop']) }}"
        type="image/webp">
    @endif

    {{-- Fallback to compressed JPEG/PNG --}}
    <img
        src="{{ StorageHelper::url($compressedSrc) }}"
        alt="{{ $alt }}"
        loading="{{ $loadingAttr }}"
        decoding="{{ $decodingAttr }}"
        fetchpriority="{{ $fetchPriority }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        @if($styleString) style="{{ $styleString }}" @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
</picture>
@else
{{-- Fallback jika WebP belum di-generate --}}
<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="{{ $loadingAttr }}"
    decoding="{{ $decodingAttr }}"
    fetchpriority="{{ $fetchPriority }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    @if($styleString) style="{{ $styleString }}" @endif
    {{ $attributes->merge(['class' => $class]) }}
>
@endif
