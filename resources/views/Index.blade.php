@extends('layouts.site')
@section('title', 'Cuidado, educación y autonomía')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/caith-strip.css') }}?v=13">
@endpush
@section('content')
    @if($previewArea)
        <div class="wrap studio-preview-notice">
            <strong>Vista previa privada</strong>
            <a href="{{ route('admin.studio.edit', $previewArea) }}">Regresar al editor</a>
        </div>
    @endif
    @foreach($homeBlocks as $key => $block)
        @if($key === 'timeline')
            @include('partials.home-timeline')
        @elseif(in_array($key, ['news', 'data', 'voices', 'help']))
            @include('partials.home-closing')
        @else
            @include('partials.home.'.$key)
        @endif
    @endforeach
@endsection
