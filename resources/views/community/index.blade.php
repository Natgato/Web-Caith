@extends('layouts.site')
@section('title', 'Mis publicaciones')
@section('content')
<div class="wrap community-workspace">
    <header class="workspace-heading community-heading">
        <div><p class="workspace-eyebrow">Mi cuenta</p><h1>Mis publicaciones</h1><p>Continúa tus borradores y consulta el estado de tus envíos.</p></div>
        <form method="post" action="{{ route('community.logout') }}">@csrf<button class="workspace-text-link" type="submit">Cerrar sesión</button></form>
    </header>
    <x-notice />
    <div class="workspace-actions">
        <a class="button" href="{{ route('community.editor', 'testimonio') }}">Crear testimonio <x-arrow /></a>
        <a class="button secondary" href="{{ route('community.editor', 'opinion') }}">Escribir opinión <x-arrow /></a>
    </div>
    <nav class="workspace-tabs" aria-label="Filtrar publicaciones">
        <a href="{{ route('community.index', ['tipo' => 'testimonio']) }}" @if($type === 'testimonio') aria-current="page" @endif>Testimonios</a>
        <a href="{{ route('community.index', ['tipo' => 'opinion']) }}" @if($type === 'opinion') aria-current="page" @endif>Opiniones</a>
    </nav>
    <div class="community-list">
        @forelse($entries as $entry)
            <article class="workspace-panel">
                <div>
                    <p class="workspace-eyebrow">{{ \App\Services\EditorialRegistry::STATES[$entry->status] }}</p>
                    <h2>{{ $entry->title }}</h2>
                    @if($entry->review_feedback)<p><strong>Nota editorial.</strong> {{ $entry->review_feedback }}</p>@endif
                    @if($entry->status === 'review')
                        <p class="workspace-muted">El equipo está revisando este envío.</p>
                    @elseif($entry->status === 'hidden')
                        <p class="workspace-muted">Esta publicación está fuera del sitio público.</p>
                    @endif
                </div>
                <div class="workspace-actions">
                    <a class="workspace-text-link" href="{{ route('community.editor', [$type, $entry->id]) }}">{{ $entry->status === 'published' ? 'Ver texto' : 'Abrir editor' }} <x-arrow /></a>
                    @if(in_array($entry->status, ['published', 'review']))
                        <a class="workspace-text-link" href="{{ route('community.withdrawal', [$type, $entry->id]) }}">Retirar</a>
                    @endif
                </div>
            </article>
        @empty
            <p class="workspace-panel workspace-muted">Todavía no tienes {{ $type === 'opinion' ? 'opiniones' : 'testimonios' }}.</p>
        @endforelse
    </div>
    {{ $entries->links('components.pagination') }}
    <p class="workspace-auth-switch">Si una historia publicada no aparece en tu cuenta, <a href="{{ route('contact', ['tipo' => 'retiro-consentimiento']) }}">solicita su retiro o corrección</a>.</p>
</div>
@endsection
