<!-- Standard SEO -->
<title>{{ $seo->title }}</title>
<meta name="description" content="{{ $seo->description }}">
<meta name="keywords" content="{{ $seo->keywords }}">
<link rel="canonical" href="{{ $seo->canonical }}">
<meta name="robots" content="index, follow">
<meta name="author" content="{{ config('app.name') }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:url" content="{{ $seo->url }}">
<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:description" content="{{ $seo->description }}">
<meta property="og:image" content="{{ $seo->image }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

@if($seo->type === 'article')
    @if($seo->published_time)
<meta property="article:published_time" content="{{ $seo->published_time }}">
    @endif
    @if($seo->modified_time)
<meta property="article:modified_time" content="{{ $seo->modified_time }}">
    @endif
    @if($seo->section)
<meta property="article:section" content="{{ $seo->section }}">
    @endif
    @foreach($seo->tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
    @endforeach
@endif

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $seo->url }}">
<meta name="twitter:title" content="{{ $seo->title }}">
<meta name="twitter:description" content="{{ $seo->description }}">
<meta name="twitter:image" content="{{ $seo->image }}">

<!-- JSON-LD Schema -->
@if(!empty($seo->schema))
    @foreach($seo->schema as $schemaData)
<script type="application/ld+json" nonce="{{ $nonce }}">
    {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
    @endforeach
@endif
