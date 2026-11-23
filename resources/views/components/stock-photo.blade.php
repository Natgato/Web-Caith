@props(['name' => 'library', 'compact' => false])
@php($photo = config('stock.'.$name))
@if(\App\Services\DemoAccess::enabled() && $photo)
<figure {{ $attributes->class(['stock-photo', 'stock-photo-compact' => $compact]) }}>
    <img src="{{ asset('images/stock/'.$photo['file']) }}" alt="{{ $photo['alt'] }}" width="1200" height="800" loading="lazy">
    <figcaption>Stock de demostración · {{ $photo['title'] }}. <a href="{{ $photo['source'] }}" target="_blank" rel="noopener noreferrer">{{ $photo['author'] }} / Pexels</a>. No es una fotografía de CAITH.</figcaption>
</figure>
@endif
