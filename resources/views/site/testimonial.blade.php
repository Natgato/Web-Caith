@extends('layouts.site')
@section('title', $story->title)
@section('content')
<article class="page-shell wrap inside-document inside-story">
    <x-interior-opening :title="$story->title" :lead="$story->public_name" back="stories" back-label="Todas las historias">
        @if($story->testimony_date)<time class="inside-byline" datetime="{{ $story->testimony_date->toDateString() }}">{{ $story->testimony_date->format('d/m/Y') }}</time>@endif
    </x-interior-opening>
    <div class="inside-reading">
        <div>
            <div class="article-body">@if($story->contributor_id){!! \App\Services\CommunityText::render($story->body) !!}@else{{ $story->body }}@endif</div>
            <p class="source-note">Testimonio revisado para publicación con consentimiento vigente. Los documentos de autorización son privados.</p>
            <a class="text-link" href="{{ route('contact', ['tipo' => 'retiro-consentimiento']) }}">Solicitar retiro o corrección <x-arrow /></a>
        </div>
        <aside>
            @if($story->media?->isAuthorized() && $story->consent->allow_image)
                <img class="inside-art" src="{{ route('story.image', $story->slug) }}" alt="{{ $story->media->alt_text }}" loading="lazy">
            @else
                <x-interior-art file="portada/historias.webp" />
                <p class="source-note">Ilustración conceptual.</p>
            @endif
        </aside>
    </div>
</article>
@endsection
