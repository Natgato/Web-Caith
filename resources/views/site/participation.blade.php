@extends('layouts.site')
@section('title', $title)
@section('content')
@php($interior = \App\Services\SiteStudio::interior('participa-'.$kind))
<section class="page-shell wrap inside-form">
<aside>
    <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" back="como-ayudar" back-label="Cómo ayudar" />
    <p>Tu solicitud se guardará para revisión. El envío no implica inscripción ni aceptación automática.</p>
    <x-interior-art file="caith-cantuta-tinta.png" />
</aside>
<div><x-notice />
@if(config('caith.forms_enabled'))
@if(app()->environment('local'))<p class="content-status">Entorno local de revisión: utiliza datos ficticios. No se envían correos reales.</p>@endif
<form class="public-form" method="post" action="{{ route('participation.store') }}">@csrf<input type="hidden" name="kind" value="{{ $kind }}">
<x-field name="name" label="Nombres y apellidos" :required="true" autocomplete="name"/><x-field name="email" label="Correo electrónico" type="email" :required="true" autocomplete="email"/><x-field name="phone" label="Teléfono (opcional)" type="tel" autocomplete="tel"/>
<x-field name="availability" :label="$kind === 'voluntariado' ? 'Disponibilidad' : 'Disponibilidad (opcional)'" :required="$kind === 'voluntariado'"/>
<x-field name="message" label="Motivación o mensaje" type="textarea" :required="true"/><p class="form-help">No incluyas DNI, documentos personales ni información de casos sensibles.</p><x-privacy-check /><button class="button" type="submit" data-submit>Enviar solicitud</button></form>
@else<p>El formulario se habilitará después de la validación institucional de privacidad y operación.</p>@endif</div>
</section>
@endsection
