@extends('layouts.site')
@section('title', 'Vista previa de páginas interiores')
@section('content')
<div class="wrap community-workspace">
    <div class="studio-preview-notice">
        <strong>Vista previa privada de encabezados</strong>
        <a href="{{ route('admin.studio.edit', 'interiores') }}">Regresar al editor</a>
    </div>
    <div class="community-list">
        @foreach($draft as $key => $block)
            <article class="workspace-panel" id="interior-{{ $key }}">
                <div>
                    <p class="workspace-eyebrow">{{ $schema[$key]['label'] }}</p>
                    @if(isset($block['title']))<h2>{{ $block['title'] }} @isset($block['accent'])<em>{{ $block['accent'] }}</em>@endisset</h2>@endif
                    <p>{{ $block['lead'] }}</p>
                    @foreach($block as $field => $value)
                        @if(str_starts_with($field, 'pillartitle_'))
                            <p><strong>{{ $value }}</strong> {{ $block['pillarlead_'.substr($field, 12)] }}</p>
                        @endif
                    @endforeach
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
