@extends('layouts.site')
@section('title', 'Noticias y publicaciones')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('news'))
<section class="page-shell wrap inside-press">
    <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" />
    <form class="filter-form" method="get">
        <label for="categoria">Categoría</label>
        <select name="categoria" id="categoria">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)<option @selected(request('categoria') === $category)>{{ $category }}</option>@endforeach
        </select>
        <button class="button" type="submit">Filtrar</button>
    </form>
    @if($posts->isNotEmpty())
        <div class="inside-press-list">
            @foreach($posts as $post)
                <article class="home-press-item">
                    <p class="source-note"><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d/m/Y') }}</time> · {{ $post->category }}</p>
                    <h2><a href="{{ route('post', $post->slug) }}">{{ $post->title }}</a></h2>
                    @if($loop->first && $post->media?->isAuthorized() && ! $post->media->restricted_to_testimonial && $post->media->kind === 'photo')
                        <img src="{{ route('media.show', $post->media) }}" alt="{{ $post->media->alt_text }}" loading="lazy">
                    @endif
                    @if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                </article>
            @endforeach
        </div>
    @else
        <div class="inside-spread">
            <x-interior-art file="portada/actualidad.webp" />
            <div class="inside-press-note">
                <h2>Noticias, actividades y recursos</h2>
                <p>{{ request()->filled('categoria') ? 'Todavía no hay publicaciones revisadas en esta categoría.' : 'Las publicaciones aparecerán aquí después de su revisión editorial.' }}</p>
                <a class="text-link" href="{{ route('nuestra-labor') }}">Conoce nuestra labor <x-arrow /></a>
            </div>
        </div>
        <p class="source-note">Ilustración conceptual.</p>
    @endif
    {{ $posts->links('components.pagination') }}
</section>
@endsection
