@extends('layouts.admin')
@section('title', 'Resumen editorial')
@section('content')
<p>Revisa contenidos, permisos y solicitudes antes de publicar. Ningún testimonio se aprueba automáticamente.</p><div class="dashboard-grid">@foreach($modules as $key => $module)<a class="dashboard-item" href="{{ route('admin.content.index', $key) }}"><h2>{{ $module['label'] }}</h2><strong>{{ $module['count'] }}</strong><p>{{ $module['review'] }} en borrador o revisión</p></a>@endforeach<a class="dashboard-item" href="{{ route('admin.inbox.index', 'participacion') }}"><h2>Participación pendiente</h2><strong>{{ $participations }}</strong></a><a class="dashboard-item" href="{{ route('admin.inbox.index', 'contacto') }}"><h2>Contacto pendiente</h2><strong>{{ $contacts }}</strong></a></div><p class="source-note">Las cantidades son registros de gestión de esta web; no representan impacto institucional ni personas beneficiarias.</p>
@endsection
