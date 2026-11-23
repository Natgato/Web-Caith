@extends('layouts.site')
@section('title', 'Línea de tiempo')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('timeline'))
<section class="page-shell wrap inside-timeline">
    <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" back="nosotros" back-label="Nosotros" />
    @if($events->contains('is_provisional', true))
        <p class="content-status">Hitos provisionales para revisión institucional.</p>
    @endif
    @if($events->isNotEmpty())
        <nav class="inside-jump" aria-label="Ir a un hito">
            @foreach($events as $event)<a href="#hito-{{ $event->id }}">{{ $event->year }}</a>@endforeach
        </nav>
        <ol class="inside-history">
            @foreach($events as $event)
                @php($illustration = [1994 => 'casa.webp', 2001 => 'centro.webp', 2020 => 'pandemia.webp'][$event->year] ?? null)
                <li id="hito-{{ $event->id }}" @class(['inside-history-event', 'has-illustration' => $illustration])>
                    <div class="chronicle-collage">
                        <time class="chronicle-year" datetime="{{ $event->year }}"><span class="chronicle-year-back">{{ substr((string) $event->year, 0, 2) }}</span><span class="chronicle-year-front">{{ substr((string) $event->year, 2) }}</span></time>
                        @if($illustration)
                            <img class="chronicle-illustration" src="{{ asset('images/cronologia/'.$illustration) }}" width="960" height="640" alt="" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <article>
                        <h2>{{ $event->title }}</h2>
                        <div class="article-body">{{ $event->body }}</div>
                        @if($event->source)<p class="source-note">Fuente: {{ $event->source }}</p>@endif
                        @if($event->media?->isAuthorized() && ! $event->media->restricted_to_testimonial)
                            @if($event->media->kind === 'photo')
                                <img class="article-image" src="{{ route('media.show', $event->media) }}" alt="{{ $event->media->alt_text }}" loading="lazy">
                            @elseif($event->media->path)
                                <video class="article-image" controls preload="metadata" src="{{ route('media.show', $event->media) }}" aria-label="{{ $event->media->alt_text }}"></video>
                            @elseif($event->media->external_url)
                                <a class="text-link" href="{{ $event->media->external_url }}" rel="noopener noreferrer" target="_blank">Ver video en su plataforma <x-arrow /></a>
                            @endif
                        @endif
                    </article>
                </li>
            @endforeach
        </ol>
        <p class="source-note">Ilustraciones conceptuales.</p>
    @else
        <p class="empty-state">No hay hitos autorizados publicados todavía.</p>
    @endif
</section>
@endsection
