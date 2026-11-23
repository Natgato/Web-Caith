@extends('layouts.site')
@php
    $story = $type === 'testimonio';
    $readOnly = $entry->status === 'published';
    $kind = $story ? 'Testimonio' : 'Opinión';
@endphp
@section('title', $readOnly ? 'Ver publicación' : ($story ? 'Editor de testimonios' : 'Editor de opiniones'))
@push('styles')
    <script src="{{ asset('js/editorial-workspace.js') }}" defer></script>
@endpush
@section('content')
<div class="wrap community-workspace">
    <a class="workspace-text-link" href="{{ route('community.index', ['tipo' => $type]) }}">← Mis publicaciones</a>
    <header class="workspace-heading community-heading">
        <div><p class="workspace-eyebrow">{{ $kind }}</p><h1>{{ $readOnly ? 'Tu publicación' : ($story ? 'Cuenta tu historia' : 'Comparte tu opinión') }}</h1>
            <p>{{ $readOnly ? 'Retira el texto si quieres modificarlo y volver a enviarlo.' : 'Guarda lo que llevas escrito o envíalo al equipo editorial cuando esté listo.' }}</p>
        </div>
    </header>
    <x-notice />
    @unless($readOnly)
        <div class="workspace-tabs" data-magazine-tabs hidden aria-label="Vista del editor">
            <button type="button" data-screen="write" aria-controls="community-writing" aria-pressed="true">Escribir</button>
            <button type="button" data-screen="preview" aria-controls="community-magazine" aria-pressed="false">Vista previa</button>
        </div>
    @endunless
    <div class="community-editor" data-magazine>
        @unless($readOnly)
        <form id="community-writing" class="workspace-panel community-writing" data-magazine-form method="post" enctype="multipart/form-data" action="{{ route('community.save', [$type, $entry->id]) }}">
            @csrf
            <x-field name="title" label="Título" :value="$entry->title ?? ''" required />
            <x-field name="public_name" label="Firma o seudónimo público" :value="$entry->public_name ?? ''" />
            <div class="field">
                <label for="body">Tu texto</label>
                <div class="magazine-tools" aria-label="Formato del texto" data-magazine-tools hidden>
                    <button type="button" data-format="bold"><strong>Negrita</strong></button>
                    <button type="button" data-format="italic"><em>Cursiva</em></button>
                    <button type="button" data-format="heading">Subtítulo</button>
                    <button type="button" data-format="quote">Cita</button>
                </div>
                <textarea id="body" name="body" rows="12" maxlength="18000" placeholder="{{ $story ? 'Empieza por lo que te gustaría contar…' : 'Empieza por la idea que quieres compartir…' }}">{{ old('body', $entry->body) }}</textarea>
            </div>
            @if($story)
                @if($entry->media_id)
                    <div class="community-photo-options">
                        <a href="{{ route('community.photo', $entry->id) }}">Ver foto adjunta</a>
                        <label class="check-row"><input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))><span>Quitar foto adjunta</span></label>
                    </div>
                @endif
                <div class="field">
                    <label for="photo">Foto para el testimonio (opcional)</label>
                    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
                    <small>JPG, PNG o WebP de hasta 3 MB. Se guarda de forma privada para la revisión.</small>
                </div>
                <x-field name="photo_alt" label="Describe brevemente la foto" />
            @endif
            <div class="community-consent">
                <label class="check-row"><input type="checkbox" name="allow_text" value="1" @checked(old('allow_text'))><span>Autorizo que se publiquen mi texto y mi firma tras su revisión. Sé que puedo retirar el envío desde mi cuenta.</span></label>
                @if($story)<label class="check-row"><input type="checkbox" name="allow_image" value="1" @checked(old('allow_image'))><span>Autorizo también la publicación de la foto que adjunté.</span></label>@endif
            </div>
            <div class="workspace-actions">
                <button class="button secondary" type="submit" name="action" value="draft">Guardar borrador</button>
                <button class="button" type="submit" name="action" value="submit">Enviar a revisión</button>
            </div>
        </form>
        @endunless
        <article id="community-magazine" class="magazine-preview">
            <header class="magazine-masthead"><span>Casa CAITH</span><span>{{ $readOnly ? $kind : 'Vista previa' }}</span></header>
            <h2 data-preview-title>{{ old('title', $entry->title) ?: 'Tu título' }}</h2>
            <p class="magazine-signature" data-preview-signature>{{ old('public_name', $entry->public_name) ?: 'Tu firma' }}</p>
            @if($story)
                <img class="magazine-photo" data-preview-photo alt="" @if($entry->media_id) src="{{ route('community.photo', $entry->id) }}" @else hidden @endif>
            @endif
            <div class="magazine-body" data-preview-body>
                @if(old('body', $entry->body))
                    {!! \App\Services\CommunityText::render(old('body', $entry->body)) !!}
                @else
                    <p>El texto aparecerá aquí mientras escribes.</p>
                @endif
            </div>
            @if($readOnly)
                <a class="workspace-text-link" href="{{ route('community.withdrawal', [$type, $entry->id]) }}">Retirar publicación <x-arrow /></a>
            @endif
        </article>
    </div>
</div>
@endsection
