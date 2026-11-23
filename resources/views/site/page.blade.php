@extends('layouts.site')
@section('title', $title)
@section('content')
@php
    $cover = config('interiors.covers')[$slug] ?? null;
    [$parent, $intro, $art] = config('interiors.articles')[$slug] ?? ['home', null, null];
    $interior = \App\Services\SiteStudio::published('interiores')[$slug] ?? [];
    $parentTitle = config('site.pages')[$parent]['title'] ?? 'Inicio';
@endphp

@if($cover)
    <section @class(['inside-cover', 'inside-cover-wine' => $slug === 'nuestra-labor', 'inside-cover-context' => $slug === 'realidad-social']) aria-labelledby="inside-cover-title">
        <div class="wrap">
            <a class="inside-cover-back" href="{{ route('home') }}">Inicio <span aria-hidden="true">/</span> {{ $title }}</a>
            <div class="inside-cover-layout">
                <h1 id="inside-cover-title">{{ $interior['title'] }} <em>{{ $interior['accent'] }}</em></h1>
                <x-interior-art :src="\App\Services\SiteStudio::imageUrl($interior, 'images/'.$cover['art'])" eager />
                <p class="inside-lead">{{ $interior['lead'] }}</p>
            </div>
        </div>
    </section>
    <section class="wrap inside-chapters">
        <div class="inside-pillars">
            @foreach($cover['pillars'] as $heading => $text)
                <section><h2>{{ $interior['pillartitle_'.$loop->iteration] }}</h2><p>{{ $interior['pillarlead_'.$loop->iteration] }}</p></section>
            @endforeach
        </div>
        <div>@include('partials.interior-page-content')</div>
        @include('partials.interior-related')
    </section>
@elseif($slug === 'como-ayudar')
    <section class="inside-help">
        <div class="wrap inside-help-layout">
            <div>
                <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" back="home" />
                <x-interior-art file="interiores/ayuda.webp" />
            </div>
            <nav class="home-participate-options" aria-label="Formas de colaborar">
                <ul>
                    @foreach([
                        'voluntariado' => ['Voluntariado', 'Ofrece tu tiempo'],
                        'actividades' => ['Participar en actividades', 'Súmate a una actividad'],
                        'patrocinio' => ['Apoyo institucional', 'Propón una colaboración'],
                    ] as $kind => [$label, $summary])
                        <li><a href="{{ route('participation.form', $kind) }}"><span><strong>{{ $label }}</strong><small>{{ $summary }}</small></span><x-arrow /></a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </section>
    <section class="wrap inside-help-details">
        @include('partials.interior-page-content')
        <nav class="inside-related" aria-label="Más información">
            <a href="{{ route('donations') }}">Donaciones: próximamente <x-arrow /></a>
            <a href="{{ route('contact') }}">Contacto <x-arrow /></a>
        </nav>
    </section>
@else
    <article class="page-shell wrap inside-document">
        <x-interior-opening :title="$page?->title ?? $title" :lead="$interior['lead'] ?? $intro" :back="$parent" :back-label="$parentTitle" />
        <div class="inside-reading">
            <div>@include('partials.interior-page-content')</div>
            <aside>
                @if($art || ($interior['image'] ?? null))<x-interior-art :src="\App\Services\SiteStudio::imageUrl($interior, 'images/'.($art ?? 'portada/actualidad.webp'))" />@endif
                @include('partials.interior-related')
            </aside>
        </div>
    </article>
@endif
@endsection
