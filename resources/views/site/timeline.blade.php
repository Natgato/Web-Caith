@extends('layouts.site')
@section('title', 'Línea de tiempo')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Una historia compartida.</h1><p class="lead">De 1994 a la actualidad: los hitos disponibles se incorporan según sus fuentes y autorizaciones.</p>
@if($events->contains('is_provisional', true))<p class="content-status">Vista local de hitos provisionales para revisión institucional. No se añaden años sin acontecimientos documentados.</p>@endif
<div class="history timeline-page"><div class="timeline-inner">
@if($events->isNotEmpty())<div class="history-controls" role="group" aria-label="Elige una etapa" hidden>@foreach($events as $event)<button type="button" data-year="{{ $event->id }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-controls="hito-{{ $event->id }}">{{ $event->year }}</button>@endforeach</div>@endif
<div class="history-stories">@forelse($events as $event)<article class="history-story" id="hito-{{ $event->id }}" data-history="{{ $event->id }}"><p class="history-year">{{ $event->year }}</p><div class="history-copy"><h2>{{ $event->title }}</h2><div class="article-body">{{ $event->body }}</div><p class="source-note">Fuente: {{ $event->source }}</p>@if($event->media?->isAuthorized() && ! $event->media->restricted_to_testimonial)@if($event->media->kind === 'photo')<img src="{{ route('media.show', $event->media) }}" alt="{{ $event->media->alt_text }}" loading="lazy">@elseif($event->media->path)<video controls preload="metadata" src="{{ route('media.show', $event->media) }}" aria-label="{{ $event->media->alt_text }}"></video>@else<a href="{{ $event->media->external_url }}" rel="noopener noreferrer" target="_blank">Ver video en su plataforma</a>@endif @endif</div></article>@empty<p>No hay hitos autorizados publicados todavía.</p>@endforelse</div>
</div></div></section>
@endsection
