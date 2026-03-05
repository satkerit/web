@php
    $office = \App\Models\Office::find(1);
    $lat = old('latitude', $office->latitude ?? '');
    $lng = old('longitude', $office->longitude ?? '');
@endphp

<pre>
Raw latitude: {{ $office->latitude }}
Raw longitude: {{ $office->longitude }}
Type latitude: {{ gettype($office->latitude) }}
Type longitude: {{ gettype($office->longitude) }}
JS lat: @js($lat ?: '')
JS lng: @js($lng ?: '')
JS lat raw: @js($lat)
JS lng raw: @js($lng)
</pre>

<div x-data="mapPicker({ lat: @js($lat ?: ''), lng: @js($lng ?: '') })">
    <p>mapLat: <span x-text="mapLat"></span></p>
    <p>mapLng: <span x-text="mapLng"></span></p>
    <p>hasCoordinates: <span x-text="hasCoordinates"></span></p>
</div>
