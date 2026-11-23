@extends('layouts.site')
@section('title', 'Historias y testimonios')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('stories'))
<section class="page-shell wrap inside-voices">
    <div class="inside-spread">
        <x-interior-art file="portada/historias.webp" />
        <div>
            <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" />
            @if($stories->isEmpty())<p>Aquí aparecerán relatos cuando tengan autorización para publicarse.</p>@endif
            @unless(auth()->user()?->is_admin)
                <a class="text-link" href="{{ route('community.editor', 'testimonio') }}">Compartir mi testimonio <x-arrow /></a>
            @endunless
            <a class="text-link" href="{{ route('contact', ['tipo' => 'retiro-consentimiento']) }}">Solicitar retiro o corrección <x-arrow /></a>
        </div>
    </div>
    <p class="source-note">Ilustración conceptual.</p>
    @if($stories->isNotEmpty())
        <div class="inside-press-list">
            @foreach($stories as $story)
                <article class="home-press-item">
                    <p class="source-note">{{ $story->public_name }}@if($story->testimony_date) · {{ $story->testimony_date->format('d/m/Y') }}@endif</p>
                    <h2><a href="{{ route('story', $story->slug) }}">{{ $story->title }}</a></h2>
                </article>
            @endforeach
        </div>
    @endif
    {{ $stories->links('components.pagination') }}
</section>
@endsection
