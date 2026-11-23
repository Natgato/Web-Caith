@extends('layouts.admin')
@section('title', $spec['label'])
@section('actions')
    <a class="button" href="{{ route('admin.content.create', $module) }}">Crear contenido <x-arrow /></a>
@endsection
@section('content')
<section class="workspace-panel workspace-records">
    <form class="filter-form" method="get">
        <label for="q">Buscar título</label>
        <input id="q" name="q" value="{{ request('q') }}" maxlength="120">
        <label for="status">Estado</label>
        <select id="status" name="status">
            <option value="">Todos</option>
            @foreach(\App\Services\EditorialRegistry::STATES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="button secondary">Filtrar</button>
    </form>
    <div class="table-scroll">
        <table aria-label="{{ $spec['label'] }}">
            <thead><tr><th>Título</th><th>Estado</th><th>Actualizado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <th scope="row">{{ $record->title }}</th>
                        <td><span class="workspace-status" data-status="{{ $record->status }}">{{ \App\Services\EditorialRegistry::STATES[$record->status] ?? $record->status }}</span></td>
                        <td><time datetime="{{ $record->updated_at->toIso8601String() }}">{{ $record->updated_at->format('d/m/Y H:i') }}</time></td>
                        <td><a class="workspace-text-link" href="{{ route('admin.content.edit', [$module, $record->id]) }}">Editar y revisar <x-arrow /></a></td>
                    </tr>
                @empty
                    <tr><td colspan="4">No hay registros para los filtros seleccionados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links('components.pagination') }}
</section>
@endsection
