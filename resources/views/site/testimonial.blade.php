@extends('layouts.site')
@section('title', $story->title)
@section('content')
<article class="page-shell wrap"><a class="back-link" href="{{ route('stories') }}">Todas las historias</a><h1 class="page-title">{{ $story->title }}</h1><p class="lead">{{ $story->public_name }}</p><p>{{ $story->testimony_date?->format('d/m/Y') }}</p>
@if($story->media?->isAuthorized() && $story->consent->allow_image)<img class="article-image" src="{{ route('story.image', $story->slug) }}" alt="{{ $story->media->alt_text }}">@endif
<div class="article-body">{{ $story->body }}</div><p class="source-note">Testimonio revisado para publicación con consentimiento vigente. Los documentos de autorización son privados.</p><a class="text-link" href="{{ route('contact', ['tipo' => 'retiro-consentimiento']) }}">Solicitar retiro o corrección <x-arrow /></a></article>
@endsection
