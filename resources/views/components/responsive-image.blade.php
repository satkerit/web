@props([
    'model' => null,
    'src' => null,
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'sizes' => '(max-width: 480px) 480px, (max-width: 768px) 768px, (max-width: 1024px) 1024px, (max-width: 1280px) 1280px, 1920px'
])

@if($model && $model->image)
    @php
        $srcset = $model->getSrcset();
        $imageUrl = $model->getImageUrl('large');
    @endphp

    @if($srcset)
        <img src="{{ $imageUrl }}"
             srcset="{{ $srcset }}"
             sizes="{{ $sizes }}"
             alt="{{ $alt }}"
             class="{{ $class }}"
             loading="{{ $loading }}"
             {{ $attributes }}>
    @else
        <img src="{{ \App\Helpers\StorageHelper::url($model->image) }}"
             alt="{{ $alt }}"
             class="{{ $class }}"
             loading="{{ $loading }}"
             {{ $attributes }}>
    @endif
@elseif($src)
    <img src="{{ $src }}"
         alt="{{ $alt }}"
         class="{{ $class }}"
         loading="{{ $loading }}"
         {{ $attributes }}>
@endif
