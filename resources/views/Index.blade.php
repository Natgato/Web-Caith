@extends('layouts.site')
@section('title', 'Cuidado, educación y autonomía')
@section('content')
        <section class="hero wrap" aria-labelledby="hero-title">
            <div class="hero-copy">
                <h1 id="hero-title">Un lugar<br>para crecer.</h1>
                <p class="hero-description">En Cusco, acompañamos a niñas, adolescentes y jóvenes con acogida, educación y herramientas para una vida autónoma.</p>
                <a class="text-link" href="{{ route('historia') }}">Conoce nuestra historia <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></a>
            </div>
            <div class="hero-asides" aria-hidden="true"><p class="hero-origin"><span>Desde</span><strong>1994</strong></p><p class="hero-note">Crecer<br>juntas.</p></div>
            <figure class="hero-art hero-art-community">
                <img src="{{ asset('images/caith-convivencia.png') }}" alt="Ilustración conceptual de jóvenes y una educadora compartiendo libros y cuadernos alrededor de una mesa" width="1536" height="1024" fetchpriority="high">
                <figcaption class="scene-words" aria-hidden="true"><span>Aprender.</span><span>Compartir.</span><span>Decidir.</span></figcaption>
            </figure>
            <img class="hero-cantuta" src="{{ asset('images/caith-cantuta-tinta.png') }}" width="1448" height="1086" alt="">
            <div class="hero-foot"><p>Una historia que empezó en <strong>1994.</strong></p><p>Cuidado, educación y autonomía.</p></div>
        </section>

<section class="home-intro section-space wrap"><h2>Conoce<br>Casa CAITH.</h2><div><p class="lead">Una casa de acogida en Cusco y una historia de acompañamiento que conecta educación, derechos y comunidad.</p><p>Casa CAITH es un programa del Centro Yanapanakusun. Su propósito es acompañar a niñas, adolescentes y jóvenes para fortalecer sus capacidades y construir una vida autónoma.</p><p class="source-note">Síntesis editorial para revisión institucional.</p><a class="text-link" href="{{ route('mision-vision') }}">Misión y visión <x-arrow /></a></div></section>
<section class="home-directory section-space wrap" aria-label="Páginas principales"><div class="entry-grid">
<x-entry :href="route('nosotros')" title="Nosotros">La casa, sus orígenes y su relación con el Centro Yanapanakusun.</x-entry>
<x-entry :href="route('nuestra-labor')" title="Nuestra labor">Programas, servicios y acompañamiento a lo largo de cada proceso.</x-entry>
<x-entry :href="route('realidad-social')" title="Realidad social">Trabajo del hogar, derechos y prevención de violencias.</x-entry>
<x-entry :href="route('stories')" title="Historias compartidas">Testimonios revisados, publicados únicamente con autorización vigente.</x-entry>
<x-entry :href="route('news')" title="Noticias y publicaciones">Artículos, actividades y recursos institucionales.</x-entry>
<x-entry :href="route('gallery')" title="Galería">Memoria audiovisual con procedencia y permisos de publicación.</x-entry>
</div></section>
<section class="home-timeline section-space wrap"><div class="section-heading"><h2>Desde 1994.</h2><a class="quiet-link" href="{{ route('timeline') }}">Explora la línea de tiempo</a></div><div class="timeline-preview">@forelse($events as $event)<a href="{{ route('timeline') }}#hito-{{ $event->id }}"><strong>{{ $event->year }}</strong><h3>{{ $event->title }}</h3>@if($event->is_provisional)<p class="source-note">Hito provisional para revisión.</p>@endif</a>@empty<p>Los hitos se incorporarán según su revisión institucional.</p>@endforelse</div></section>
<section class="home-news section-space wrap"><div class="section-heading"><h2>Actualidad.</h2><a class="quiet-link" href="{{ route('news') }}">Todas las publicaciones</a></div><div class="entry-grid">@forelse($posts as $post)<x-entry :href="route('post', $post->slug)" :title="$post->title">{{ $post->excerpt }}</x-entry>@empty<p class="empty-state">Las noticias y actividades aparecerán aquí después de la revisión editorial.</p>@endforelse</div></section>
<section class="home-data section-space wrap"><div class="section-heading"><h2>Datos con contexto.</h2><a class="quiet-link" href="{{ route('statistics') }}">Indicadores y fuentes</a></div><div class="stat-preview">@forelse($statistics as $stat)<article><strong>{{ number_format((float)$stat->value, $stat->unit === '%' ? 1 : 0, ',', ' ') }} {{ $stat->unit }}</strong><h3>{{ $stat->indicator }}</h3><p>{{ $stat->geography }} · {{ $stat->year }}. Serie histórica.</p><a href="{{ $stat->source_url }}" rel="noopener noreferrer" target="_blank">{{ $stat->source }}</a></article>@empty<p class="empty-state">Los indicadores se mostrarán cuando tengan fuente, año y alcance verificados.</p>@endforelse</div><p class="source-note">Estas cifras contextualizan la realidad social; no son resultados de CAITH ni estadísticas actuales de Cusco.</p></section>
<section class="home-stories section-space wrap"><div class="section-heading"><h2>Voces compartidas.</h2><a class="quiet-link" href="{{ route('stories') }}">Historias y testimonios</a></div>@if($stories->isNotEmpty())<div class="entry-grid">@foreach($stories as $story)<x-entry :href="route('story', $story->slug)" :title="$story->title">{{ $story->public_name }}</x-entry>@endforeach</div>@else<p class="empty-state">Este espacio espera relatos autorizados. Cuidar su publicación también es parte del acompañamiento.</p>@endif</section>
<section class="home-help section-space wrap"><h2>Tu apoyo puede<br>acompañar.</h2><div><p class="lead">Conoce las formas de colaborar y envía una solicitud al equipo.</p><div class="action-links"><a class="button" href="{{ route('como-ayudar') }}">Cómo ayudar</a><a class="text-link" href="{{ route('contact') }}">Contacto <x-arrow /></a></div><p class="source-note">Donaciones: próximamente. Esta web no procesa pagos.</p></div></section>
@endsection
