@extends('layouts.site')
@section('title', 'Explora Casa CAITH')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Explora Casa CAITH.</h1><p class="lead">Historia, actualidad y recursos para conocer nuestra labor.</p>
<div class="entry-grid"><x-entry :href="route('news')" title="Noticias y publicaciones">Artículos y actividades revisados por el equipo editorial.</x-entry><x-entry :href="route('stories')" title="Historias y testimonios">Relatos con revisión institucional y consentimiento vigente.</x-entry><x-entry :href="route('gallery')" title="Galería">Fotografías y videos con autorización de publicación.</x-entry><x-entry :href="route('timeline')" title="Línea de tiempo">Hitos desde 1994 y su contexto.</x-entry><x-entry :href="route('statistics')" title="Estadísticas">Datos con ámbito geográfico, año y fuente.</x-entry><x-entry :href="route('contact')" title="Contacto">Consultas y solicitudes a la institución.</x-entry></div></section>
@endsection
