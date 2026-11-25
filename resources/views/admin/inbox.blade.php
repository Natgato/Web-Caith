@extends('layouts.admin')
@section('title', $box === 'participacion' ? 'Solicitudes de participación' : 'Contacto y privacidad')
@section('lead', 'Busca por número de solicitud y abre el registro para revisarlo.')
@section('content')
<section class="workspace-panel workspace-records">
    <form class="filter-form" method="get">
        <label for="q">Número de solicitud</label>
        <input name="q" id="q" value="{{ request('q') }}" maxlength="100">
        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="">Todos</option>
            @foreach($states as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="button secondary" type="submit">Filtrar</button>
    </form>
    <div class="table-scroll">
        <table aria-label="Solicitudes recibidas">
            <thead><tr><th>Número</th><th>Finalidad</th><th>Estado</th><th>Recibida</th><th>Acción</th></tr></thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <th scope="row">{{ $record->id }}</th>
                        <td>{{ $record->kind }}</td>
                        <td><span class="workspace-status" data-status="{{ $record->status }}">{{ $states[$record->status] ?? $record->status }}</span></td>
                        <td><time datetime="{{ $record->created_at->toIso8601String() }}">{{ $record->created_at->format('d/m/Y H:i') }}</time></td>
                        <td><a class="workspace-text-link" href="{{ route('admin.inbox.show', [$box, $record->id]) }}">Revisar <x-arrow /></a></td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay solicitudes para estos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links('components.pagination') }}
</section>
@endsection
