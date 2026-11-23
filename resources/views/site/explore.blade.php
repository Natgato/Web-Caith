@extends('layouts.site')
@section('title', 'Explora Casa CAITH')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('explore'))
<section class="page-shell wrap inside-directory">
    <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" />
    <div class="home-directory-grid">
        @foreach([
            'nosotros' => ['Nosotros', 'Conoce cómo nació Casa CAITH y su relación con el Centro Yanapanakusun.', 'interiores/casa.webp'],
            'nuestra-labor' => ['Nuestra labor', 'Acogida, educación y autonomía en la vida cotidiana.', 'interiores/labor.webp'],
            'realidad-social' => ['Realidad social', 'Trabajo del hogar, derechos laborales y prevención de la violencia.', 'interiores/realidad.webp'],
            'stories' => ['Historias compartidas', 'Relatos con revisión institucional y consentimiento vigente.', 'portada/historias.webp'],
            'news' => ['Noticias y publicaciones', 'Novedades, actividades y recursos de la casa.', 'portada/actualidad.webp'],
            'gallery' => ['Galería', 'Fotografías y videos con procedencia y autorización.', 'directorio/gallery.webp'],
            'timeline' => ['Línea de tiempo', 'De los primeros encuentros a nuevas formas de acompañar.', 'cronologia/casa.webp'],
            'statistics' => ['Estadísticas', 'Datos con ámbito geográfico, año y fuente.', 'directorio/realidad-social.webp'],
        ] as $destination => [$label, $description, $image])
            <a href="{{ route($destination) }}" @class(['home-directory-entry', 'is-featured' => $destination === 'nuestra-labor'])>
                <h3>{{ $label }} <x-arrow /></h3>
                <x-interior-art :file="$image" />
                <p>{{ $description }}</p>
            </a>
        @endforeach
    </div>
    <p class="home-directory-credit">Ilustraciones conceptuales para Casa CAITH.</p>
    <a class="text-link" href="{{ route('contact') }}">Contacto <x-arrow /></a>
</section>
@endsection
