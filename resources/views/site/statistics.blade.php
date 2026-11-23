@extends('layouts.site')
@section('title', 'Estadísticas con fuentes')
@section('content')
@php($interior = \App\Services\SiteStudio::interior('statistics'))
<section class="page-shell wrap inside-data">
<x-interior-opening :title="$interior['title']" :lead="$interior['lead']" />
<p>Los datos históricos se presentan según su ámbito. Las cifras nacionales no se extrapolan a Cusco ni representan resultados de Casa CAITH.</p>
<form class="filter-form" method="get"><label for="ambito">Ámbito geográfico</label><select name="ambito" id="ambito"><option value="">Todos los ámbitos</option>@foreach($geographies as $geography)<option @selected(request('ambito') === $geography)>{{ $geography }}</option>@endforeach</select><button class="button" type="submit">Filtrar</button></form>
@forelse($statistics->groupBy(fn($s) => $s->indicator.' · '.$s->geography.' · '.$s->unit) as $label => $series)
<figure class="stat-chart"><figcaption>{{ $label }}</figcaption>@php($max = $series->first()->unit === '%' ? 100 : max(1, $series->max('value')))
@foreach($series as $stat)<div class="chart-row"><span>{{ $stat->year }}</span><svg viewBox="0 0 400 24" preserveAspectRatio="none" role="img" aria-label="{{ $stat->year }}: {{ number_format((float)$stat->value, 1, ',', ' ') }} {{ $stat->unit }}"><rect width="{{ min(400, max(0, (float)$stat->value / $max * 400)) }}" height="24" fill="currentColor"/></svg><strong>{{ number_format((float)$stat->value, $stat->unit === '%' ? 1 : 0, ',', ' ') }} {{ $stat->unit }}</strong></div>@endforeach</figure>
@empty<p class="empty-state">No hay indicadores verificados para este ámbito. No se sustituyen por ejemplos ficticios.</p>@endforelse
@if($statistics->isNotEmpty())<details class="inside-dataset" open><summary>Tabla y procedencia de las cifras</summary><p class="form-help">En pantallas pequeñas, desplaza la tabla horizontalmente para consultar todas las fuentes.</p><div class="table-scroll"><table class="statistics-table"><caption>Indicadores, alcance y fuentes verificadas</caption><thead><tr><th>Indicador</th><th>Dato</th><th>Año</th><th>Ámbito</th><th>Fuente</th></tr></thead><tbody>@foreach($statistics as $stat)<tr><th scope="row">{{ $stat->indicator }}</th><td>{{ number_format((float)$stat->value, $stat->unit === '%' ? 1 : 0, ',', ' ') }} {{ $stat->unit }}</td><td>{{ $stat->year }}</td><td>{{ $stat->geography }}</td><td><a href="{{ $stat->source_url }}" rel="noopener noreferrer" target="_blank">{{ $stat->source }}</a><p>{{ $stat->source_locator }}</p><p>{{ $stat->body }}</p><small>Verificada: {{ $stat->verified_at?->format('d/m/Y') }}</small></td></tr>@endforeach</tbody></table></div></details>@endif</section>
@endsection
