@extends('layouts.admin')
@section('title', ($record->exists ? 'Editar: ' : 'Crear: ').$spec['label'])
@section('content')
<a class="back-link" href="{{ route('admin.content.index', $module) }}">Volver al listado</a><p>El texto se guarda y muestra como texto seguro. No pegues HTML ni datos identificables de beneficiarias.</p>
<form class="admin-form" method="post" enctype="multipart/form-data" action="{{ $record->exists ? route('admin.content.update', [$module, $record->id]) : route('admin.content.store', $module) }}">@csrf @if($record->exists) @method('PUT') @endif
@foreach($spec['fields'] as $name => $field)
    @if($field[1] === 'checkbox')<input type="hidden" name="{{ $name }}" value="0"><label class="check-row"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $record->$name ?? true))><span>{{ $field[0] }}</span></label>
    @elseif($field[1] === 'select')<div class="field"><label for="{{ $name }}">{{ $field[0] }}</label><select id="{{ $name }}" name="{{ $name }}">@foreach($field[2] as $value => $label)<option value="{{ $value }}" @selected(old($name, $record->$name) === $value)>{{ $label }}</option>@endforeach</select></div>
    @elseif($field[1] === 'media')<div class="field"><label for="{{ $name }}">{{ $field[0] }}</label><select id="{{ $name }}" name="{{ $name }}"><option value="">Sin material adjunto</option>@foreach($mediaOptions as $media)<option value="{{ $media->id }}" @selected((string)old($name, $record->$name) === (string)$media->id)>{{ $media->title }} ({{ $media->status }})</option>@endforeach</select></div>
    @else<x-field :name="$name" :label="$field[0]" :type="$field[1]" :value="($record->$name instanceof \DateTimeInterface ? $record->$name->format('Y-m-d') : ($record->$name ?? ''))"/>@endif
@endforeach
@if($module === 'galeria')<x-field name="asset_file" label="Archivo privado (JPG, PNG, WebP, MP4 o WebM)" type="file"/><p class="form-help">Máximo de aplicación: 20 MB; el límite PHP del servidor puede ser menor. No se admiten SVG, HTML ni archivos ejecutables.</p>@if($record->exists && $record->path)<a href="{{ route('admin.media', $record) }}" target="_blank" rel="noopener">Revisar archivo privado</a>@endif @endif
<div class="field"><label for="status">Estado editorial</label><select id="status" name="status">@foreach($states as $key => $label)<option value="{{ $key }}" @selected(old('status', $record->status ?? 'draft') === $key)>{{ $label }}</option>@endforeach</select></div>
<label class="check-row"><input type="checkbox" name="publication_reviewed" value="1"><span>Si publico, confirmo que revisé contenido, fuentes y autorización institucional. No contiene información identificable no autorizada.</span></label><button class="button" type="submit" data-submit>Guardar contenido</button></form>
@if($record->exists && $module === 'testimonios') @include('admin.consent-form', ['testimonial' => $record]) @endif
@if($record->exists)<section class="delete-section"><h2>Eliminar contenido</h2><p>Esta acción elimina el registro. Para retirarlo temporalmente, cambia el estado a Oculto o Archivado.</p><form method="post" action="{{ route('admin.content.destroy', [$module, $record->id]) }}" data-confirm="¿Eliminar este contenido? Esta acción no se puede deshacer.">@csrf @method('DELETE')<button class="button danger" type="submit">Eliminar definitivamente</button></form></section>@endif
@endsection
