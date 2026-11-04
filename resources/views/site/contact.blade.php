@extends('layouts.site')
@section('title', 'Contacto')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Conversemos.</h1><p class="lead">Envía una consulta a Casa CAITH o solicita la revisión, retiro o eliminación de tus datos.</p><x-notice />
@if(config('caith.forms_enabled'))
@if(app()->environment('local'))<p class="content-status">Entorno local de revisión: utiliza datos ficticios. Las consultas se guardan en el panel; no se envían correos reales.</p>@endif
<form class="public-form" method="post" action="{{ route('contact.store') }}">@csrf
<x-field name="name" label="Nombres y apellidos" :required="true" autocomplete="name"/><x-field name="email" label="Correo electrónico" type="email" :required="true" autocomplete="email"/><x-field name="phone" label="Teléfono (opcional)" type="tel" autocomplete="tel"/>
<div class="field"><label for="kind">Tipo de consulta</label><select id="kind" name="kind">@foreach(['consulta' => 'Consulta general', 'retiro-consentimiento' => 'Retirar un consentimiento o testimonio', 'eliminacion' => 'Solicitar eliminación de datos', 'acceso-datos' => 'Consultar o corregir mis datos'] as $value => $label)<option value="{{ $value }}" @selected(old('kind', request('tipo', 'consulta')) === $value)>{{ $label }}</option>@endforeach</select></div>
<x-field name="message" label="Consulta o solicitud" type="textarea" :required="true"/><p class="form-help">Incluye la referencia de una solicitud anterior si la tienes. No envíes documentos de identidad ni detalles de beneficiarias. Este formulario no atiende emergencias.</p><x-privacy-check/><button class="button" type="submit" data-submit>Enviar consulta</button></form>
@else<p>Estamos preparando los canales oficiales. El formulario se habilitará tras su validación institucional.</p>@endif
<p class="source-note">Ubicación institucional: Cusco, Perú. El teléfono, correo y dirección de atención se incorporarán cuando la institución confirme su publicación.</p></section>
@endsection
