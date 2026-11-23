@extends('layouts.site')
@section('title', $entry->title)
@section('content')
<div class="wrap community-workspace">
    <a class="workspace-text-link" href="{{ route('opinions') }}">← Todas las opiniones</a>
    <article class="magazine-preview magazine-published">
        <header class="magazine-masthead"><span>Casa CAITH</span><span>Opinión</span></header>
        <h1>{{ $entry->title }}</h1>
        <p class="magazine-signature">{{ $entry->public_name }} · {{ $entry->published_at->format('d/m/Y') }}</p>
        <div class="magazine-body">{!! \App\Services\CommunityText::render($entry->body) !!}</div>
    </article>
</div>
@endsection
