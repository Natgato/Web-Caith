@extends('layouts.site')
@section('title', 'Donaciones — Próximamente')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Donaciones.</h1><p class="lead">Próximamente.</p><p class="readable">El apoyo económico podría contribuir a sostener el acompañamiento educativo y las actividades institucionales. Los mecanismos y condiciones se publicarán después de su aprobación por Casa CAITH.</p><p class="readable">Esta página no recibe pagos ni muestra cuentas bancarias. Puedes enviar una consulta al equipo.</p><a class="text-link" href="{{ route('contact') }}">Ir a contacto <x-arrow /></a></section>
@endsection
