@extends('layouts.site')
@section('title', 'Galería')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('gallery'))
<section class="page-shell wrap inside-gallery"><x-interior-opening :title="$interior['title']" :lead="$interior['lead']" /><nav class="filter-links" aria-label="Filtrar galería"><a href="{{ route('gallery') }}" @if(!request()->filled('tipo')) aria-current="page" @endif>Todo</a><a href="{{ route('gallery', ['tipo' => 'photo']) }}" @if(request('tipo') === 'photo') aria-current="page" @endif>Fotografías</a><a href="{{ route('gallery', ['tipo' => 'video']) }}" @if(request('tipo') === 'video') aria-current="page" @endif>Videos</a></nav>@if(\App\Services\DemoAccess::enabled() && request('tipo') !== 'video')
<p class="content-status">Selección de stock para mostrar el diseño. Estas fotografías no documentan actividades ni personas de CAITH.</p>
<div class="stock-gallery">@foreach(array_keys(config('stock')) as $stockName)<x-stock-photo :name="$stockName" />@endforeach</div>
@endif
<div class="gallery-list">
@forelse($items as $item)<figure>@if($item->kind === 'photo')<a href="{{ route('media.show', $item) }}"><img src="{{ route('media.show', $item) }}" alt="{{ $item->alt_text }}" loading="lazy"></a>@elseif($item->path)<video controls preload="metadata" src="{{ route('media.show', $item) }}" aria-label="{{ $item->alt_text }}"></video>@else<a class="text-link" href="{{ $item->external_url }}" rel="noopener noreferrer" target="_blank">Ver video en su plataforma <x-arrow /></a>@endif<figcaption><h2>{{ $item->title }}</h2><p>{{ $item->alt_text }}</p><p class="source-note">Procedencia: {{ $item->source }}</p></figcaption></figure>@empty<p class="empty-state">La galería está lista para recibir material institucional autorizado. Las ilustraciones conceptuales del diseño no se presentan como fotografías de actividades reales.</p>@endforelse</div>{{ $items->links('components.pagination') }}</section>
@endsection
