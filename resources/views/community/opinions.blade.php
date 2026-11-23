@extends('layouts.site')
@section('title', 'Opiniones de la comunidad')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('opinions'))
<div class="wrap community-workspace">
    <header class="workspace-heading community-heading">
        <div><p class="workspace-eyebrow">Comunidad</p><h1>{{ $interior['title'] }}</h1><p>{{ $interior['lead'] }}</p></div>
        <a class="button" href="{{ route(auth()->user()?->is_admin ? 'admin.content.index' : 'community.editor', auth()->user()?->is_admin ? 'opiniones' : 'opinion') }}">{{ auth()->user()?->is_admin ? 'Administrar opiniones' : 'Escribir mi opinión' }} <x-arrow /></a>
    </header>
    <div class="community-list">
        @forelse($entries as $entry)
            <article class="workspace-panel">
                <div><p class="workspace-eyebrow">{{ $entry->public_name }}</p><h2><a href="{{ route('opinions', $entry->slug) }}">{{ $entry->title }}</a></h2>
                    <p>{{ \App\Services\CommunityText::excerpt($entry->body, 190) }}</p>
                </div>
            </article>
        @empty
            <p class="workspace-panel workspace-muted">Las opiniones aparecerán aquí cuando estén publicadas.</p>
        @endforelse
    </div>
    {{ $entries->links('components.pagination') }}
</div>
@endsection
