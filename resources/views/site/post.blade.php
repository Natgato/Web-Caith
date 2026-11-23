@extends('layouts.site')
@section('title', $post->title)
@section('content')
<article class="page-shell wrap inside-document">
    <x-interior-opening :title="$post->title" :lead="$post->excerpt" back="news" back-label="Todas las publicaciones">
        <p class="inside-byline">{{ $post->category }} · <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d/m/Y') }}</time></p>
    </x-interior-opening>
    <div class="inside-reading">
        <div>
            <div class="article-body">{{ $post->body }}</div>
            @if($post->source)<p class="source-note">Fuente o procedencia: {{ $post->source }}</p>@endif
        </div>
        <aside>
            @if($post->media?->isAuthorized() && ! $post->media->restricted_to_testimonial && $post->media->kind === 'photo')
                <img class="inside-art" src="{{ route('media.show', $post->media) }}" alt="{{ $post->media->alt_text }}" loading="lazy">
            @else
                <x-interior-art file="portada/actualidad.webp" />
                <p class="source-note">Ilustración conceptual.</p>
            @endif
            <nav class="inside-related" aria-label="Sigue leyendo">
                <a href="{{ route('news') }}">Todas las publicaciones <x-arrow /></a>
                <a href="{{ route('gallery') }}">Galería <x-arrow /></a>
            </nav>
        </aside>
    </div>
</article>
@endsection
