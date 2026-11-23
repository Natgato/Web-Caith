@extends('layouts.admin')
@section('title', 'Resumen editorial')
@section('lead', 'Solicitudes, publicaciones y tareas de revisión de Casa CAITH.')
@section('actions')
    <a class="button" href="{{ route('admin.studio.edit', 'portada') }}">Editar portada <x-arrow /></a>
@endsection
@section('content')
    <div class="workspace-metrics">
        <a href="{{ route('admin.inbox.index', ['contacto', 'status' => 'pending']) }}">
            <strong>{{ $totals['contacto'] }}</strong><span>Mensajes de contacto</span><small>Pendientes de atención</small>
        </a>
        <a href="{{ route('admin.inbox.index', ['participacion', 'status' => 'pending']) }}">
            <strong>{{ $totals['participacion'] }}</strong><span>Solicitudes de participación</span><small>Pendientes de atención</small>
        </a>
        <a href="#tareas">
            <strong>{{ $modules->sum('review') }}</strong><span>Contenidos en revisión</span><small>En {{ $modules->where('review', '>', 0)->count() }} módulos</small>
        </a>
    </div>
    <div class="workspace-charts">
        <section class="workspace-panel">
            <h2>Solicitudes recibidas</h2>
            <p class="workspace-muted">Últimos seis meses</p>
            <ul class="chart-key"><li class="key-wine">Contacto</li><li class="key-clay">Participación</li></ul>
            @php($maximum = max(1, $series['contacto']->max(), $series['participacion']->max()))
            <svg class="requests-chart" viewBox="0 0 360 158" aria-hidden="true">
                @foreach([18, 57, 96, 135] as $y)
                    <line class="chart-rule" x1="0" x2="360" y1="{{ $y }}" y2="{{ $y }}" />
                @endforeach
                @foreach($months as $i => $date)
                    @php($barStart = 60 * $i + 16)
                    @foreach(['contacto', 'participacion'] as $category)
                        @php($height = $series[$category][$i] * 117 / $maximum)
                        <rect class="{{ $category === 'contacto' ? 'bar-wine' : 'bar-clay' }}" x="{{ $barStart + ($loop->first ? 0 : 15) }}" y="{{ 135 - $height }}" width="12" height="{{ $height }}" />
                    @endforeach
                    <text x="{{ $barStart + 13 }}" y="153" text-anchor="middle">{{ $date->locale('es')->isoFormat('MMM') }}</text>
                @endforeach
            </svg>
            @if(! $series['contacto']->sum() && ! $series['participacion']->sum())<p class="workspace-muted">Todavía no hay solicitudes en este periodo.</p>@endif
            <details class="workspace-details"><summary>Ver tabla de solicitudes</summary><div class="workspace-table-scroll">
                <table><thead><tr><th>Mes</th><th>Contacto</th><th>Participación</th></tr></thead><tbody>
                @foreach($months as $i => $date)
                    <tr><th scope="row">{{ $date->locale('es')->isoFormat('MMMM YYYY') }}</th><td>{{ $series['contacto'][$i] }}</td><td>{{ $series['participacion'][$i] }}</td></tr>
                @endforeach
                </tbody></table>
            </div></details>
        </section>
        <section class="workspace-panel">
            <h2>Estado editorial</h2>
            <p class="workspace-muted">Contenidos por módulo</p>
            <ul class="chart-key"><li class="key-paper">Borrador</li><li class="key-clay">En revisión</li><li class="key-wine">Publicado</li></ul>
            @php($largest = max(1, $modules->max(fn ($row) => $row['draft'] + $row['review'] + $row['published'])))
            <div class="editorial-bars" aria-hidden="true">
                @foreach($modules as $row)
                    <div class="editorial-bar">
                        <span>{{ $row['label'] }}</span>
                        <svg viewBox="0 0 120 9" preserveAspectRatio="none">
                            <rect class="bar-track" width="120" height="9" />
                            @php($offset = 0)
                            @foreach(['draft' => 'paper', 'review' => 'clay', 'published' => 'wine'] as $stage => $color)
                                @php($width = $row[$stage] * 120 / $largest)
                                <rect class="bar-{{ $color }}" x="{{ $offset }}" width="{{ $width }}" height="9" />
                                @php($offset += $width)
                            @endforeach
                        </svg>
                        <strong>{{ $row['draft'] + $row['review'] + $row['published'] }}</strong>
                    </div>
                @endforeach
            </div>
            <details class="workspace-details"><summary>Ver tabla de contenidos</summary><div class="workspace-table-scroll">
                <table><thead><tr><th>Módulo</th><th>Borrador</th><th>En revisión</th><th>Publicado</th></tr></thead><tbody>
                @foreach($modules as $row)
                    <tr><th scope="row">{{ $row['label'] }}</th><td>{{ $row['draft'] }}</td><td>{{ $row['review'] }}</td><td>{{ $row['published'] }}</td></tr>
                @endforeach
                </tbody></table>
            </div></details>
        </section>
    </div>
    <section class="workspace-panel workspace-queue" id="tareas">
        <h2>Tareas pendientes</h2>
        @forelse($queue as $task)
            <article>
                <span>{{ $task['label'] }}</span>
                <strong>{{ $task['title'] }}</strong>
                <time datetime="{{ $task['date']->toDateString() }}">{{ $task['date']->format('d/m/Y') }}</time>
                <a href="{{ $task['url'] }}">Revisar <x-arrow /></a>
            </article>
        @empty
            <p class="workspace-muted">No hay solicitudes ni contenidos pendientes de revisión.</p>
        @endforelse
    </section>
@endsection
