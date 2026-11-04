@extends('layouts.site')
@section('title', $title)
@section('content')
<article class="page-shell wrap">
    <a class="back-link" href="{{ route('home') }}">Inicio</a><h1 class="page-title">{{ $page?->title ?? $title }}</h1>
    @if($page)
        @if($page->is_provisional)<p class="content-status">Contenido de trabajo pendiente de validación institucional. La síntesis de misión y visión no sustituye la formulación oficial.</p>@endif
        <div class="article-body">{{ $page->body }}</div>
        @if($page->source)<p class="source-note">Fuente: {{ $page->source }}</p>@endif
        @if($page->slug === 'derechos')<nav class="source-links" aria-label="Fuentes oficiales de derechos laborales"><p><a href="https://busquedas.elperuano.pe/dispositivo/NL/1889434-1" target="_blank" rel="noopener noreferrer">Ley 31047: texto oficial</a></p><p><a href="https://www.gob.pe/institucion/mtpe/normas-legales/1844983-009-2021-tr" target="_blank" rel="noopener noreferrer">DS 009-2021-TR: reglamento</a></p></nav>@endif
    @else
        <p class="lead">Estamos preparando esta información para su publicación institucional.</p>
    @endif
    @if($links)<nav class="entry-grid" aria-label="Continúa explorando">@foreach($links as $name => $label)<x-entry :href="route($name)" :title="$label">Conoce esta sección.</x-entry>@endforeach</nav>@endif
</article>
@endsection
