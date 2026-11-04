@extends('layouts.site')
@section('title', $post->title)
@section('content')
<article class="page-shell wrap"><a class="back-link" href="{{ route('news') }}">Todas las publicaciones</a><h1 class="page-title">{{ $post->title }}</h1><p class="source-note">{{ $post->category }} · {{ $post->published_at->format('d/m/Y') }}</p><p class="lead">{{ $post->excerpt }}</p>
@if($post->media?->isAuthorized() && ! $post->media->restricted_to_testimonial && $post->media->kind === 'photo')<img class="article-image" src="{{ route('media.show', $post->media) }}" alt="{{ $post->media->alt_text }}">@endif
<div class="article-body">{{ $post->body }}</div><p class="source-note">Fuente o procedencia: {{ $post->source }}</p></article>
@endsection
