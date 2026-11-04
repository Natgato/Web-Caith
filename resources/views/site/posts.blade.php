@extends('layouts.site')
@section('title', 'Noticias y publicaciones')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Noticias y publicaciones.</h1><p class="lead">Actualidad institucional, actividades y recursos revisados.</p>
<form class="filter-form" method="get"><label for="categoria">Categoría</label><select name="categoria" id="categoria"><option value="">Todas las categorías</option>@foreach($categories as $category)<option @selected(request('categoria') === $category)>{{ $category }}</option>@endforeach</select><button class="button" type="submit">Filtrar</button></form>
<div class="entry-grid">@forelse($posts as $post)<x-entry :href="route('post', $post->slug)" :title="$post->title">{{ $post->published_at->format('d/m/Y') }} · {{ $post->category }}<br>{{ $post->excerpt }}</x-entry>@empty<p class="empty-state">Todavía no hay publicaciones revisadas en esta categoría. El panel permite preparar y aprobar los primeros artículos.</p>@endforelse</div>{{ $posts->links('components.pagination') }}</section>
@endsection
