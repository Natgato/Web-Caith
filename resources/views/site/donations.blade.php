@extends('layouts.site')
@section('title', 'Donaciones — Próximamente')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('donations'))
<section class="page-shell wrap inside-spread inside-donations">
    <div>
        <x-interior-opening :title="$interior['title']" :lead="$interior['lead']" />
        <p class="readable">Los mecanismos y condiciones se publicarán después de su aprobación por Casa CAITH.</p>
        <a class="text-link" href="{{ route('como-ayudar') }}">Ver formas de colaborar <x-arrow /></a>
        <a class="text-link" href="{{ route('contact') }}">Ir a contacto <x-arrow /></a>
    </div>
    <x-interior-art file="caith-cantuta-tinta.png" />
</section>
@endsection
