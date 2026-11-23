@extends('layouts.admin')
@section('title', ['portada' => 'Editar portada', 'interiores' => 'Editar páginas interiores', 'menu' => 'Editar navegación', 'pie' => 'Editar pie de página'][$area])
@section('lead', 'Edita el contenido, ordena los bloques y revisa el borrador antes de publicar.')
@section('actions')
    <a class="button secondary" href="{{ route('admin.studio.preview', ['area' => $area]) }}" target="_blank" rel="noopener">Ver borrador <x-arrow /></a>
@endsection
@push('scripts')
    <script src="{{ asset('js/editorial-workspace.js') }}" defer></script>
@endpush
@section('content')
    <nav class="workspace-tabs" aria-label="Partes del sitio">
        @foreach(['portada' => 'Portada', 'interiores' => 'Páginas interiores', 'menu' => 'Navegación', 'pie' => 'Pie de página'] as $key => $label)
            <a href="{{ route('admin.studio.edit', $key) }}" @if($key === $area) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
    @if($area === 'interiores')
        <p class="workspace-muted">Aquí puedes ajustar los encabezados e ilustraciones. Los artículos y el contenido de cada sección se editan desde sus módulos del menú lateral.</p>
    @endif
    <form data-studio method="post" action="{{ route('admin.studio.update', $area) }}">
        @csrf
        @method('PUT')
        <input name="version" type="hidden" value="{{ $version }}">
        <div class="studio-columns">
            <aside class="workspace-panel studio-sections">
                <h2>Bloques</h2>
                <ol data-studio-order @if($area === 'interiores') data-fixed-order @endif>
                    @foreach($blocks as $key => $block)
                        <li data-studio-row data-key="{{ $key }}">
                            <input type="hidden" name="order[]" value="{{ $key }}">
                            <a href="#bloque-{{ $key }}" data-studio-select>{{ $schema[$key]['label'] }}</a>
                            <div data-studio-move hidden>
                                <button type="button" data-move="-1" aria-label="Subir {{ $schema[$key]['label'] }}">↑</button>
                                <button type="button" data-move="1" aria-label="Bajar {{ $schema[$key]['label'] }}">↓</button>
                            </div>
                        </li>
                    @endforeach
                </ol>
                @if(in_array($area, ['portada', 'interiores']))
                    <a class="workspace-text-link" href="{{ route('admin.content.index', 'galeria') }}">Gestionar imágenes <x-arrow /></a>
                @endif
            </aside>
            <div class="studio-fields">
                @foreach($blocks as $key => $block)
                    <fieldset class="workspace-panel" id="bloque-{{ $key }}" data-studio-block="{{ $key }}">
                        <legend>{{ $schema[$key]['label'] }}</legend>
                        @foreach($schema[$key]['fields'] as $field => [$label, $default])
                            @php($value = old("blocks.$key.$field", $block[$field]))
                            <div class="field">
                                <label for="{{ $key }}-{{ $field }}">{{ $label }}</label>
                                @if($default === null)
                                    <select id="{{ $key }}-{{ $field }}" name="blocks[{{ $key }}][{{ $field }}]" data-field="{{ $field }}">
                                        <option value="">Ilustración original</option>
                                        @foreach($gallery as $asset)
                                            <option value="{{ $asset->id }}" data-image-url="{{ route('media.show', $asset) }}" @selected((string) $value === (string) $asset->id)>{{ $asset->title }}</option>
                                        @endforeach
                                    </select>
                                @elseif(mb_strlen($default) > 75 || str_contains($default, "\n"))
                                    <textarea id="{{ $key }}-{{ $field }}" name="blocks[{{ $key }}][{{ $field }}]" data-field="{{ $field }}" rows="3" required>{{ $value }}</textarea>
                                @else
                                    <input id="{{ $key }}-{{ $field }}" name="blocks[{{ $key }}][{{ $field }}]" data-field="{{ $field }}" value="{{ $value }}" required>
                                @endif
                                @error("blocks.$key.$field")<small class="workspace-error">{{ $message }}</small>@enderror
                            </div>
                        @endforeach
                    </fieldset>
                @endforeach
                <div class="workspace-actions">
                    <button class="button secondary" type="submit" name="intent" value="draft">Guardar borrador</button>
                    <button class="button" type="submit" name="intent" value="publish">Publicar cambios</button>
                </div>
            </div>
            <aside class="workspace-panel studio-preview" data-studio-preview hidden>
                <p class="workspace-eyebrow">Vista rápida</p>
                <div data-studio-preview-content></div>
                <p class="workspace-muted">Guarda el borrador para verlo en la página completa.</p>
            </aside>
        </div>
    </form>
    <section class="workspace-panel workspace-history">
        <h2>Historial de publicaciones</h2>
        @forelse($revisions as $revision)
            <div><span>{{ $revision->created_at->format('d/m/Y H:i') }} · {{ $revision->user?->name ?? 'Versión original' }}</span>
                <form method="post" action="{{ route('admin.studio.restore', [$area, $revision]) }}">
                    @csrf<input name="version" type="hidden" value="{{ $version }}">
                    <button type="submit" class="workspace-text-link">Recuperar como borrador</button>
                </form>
            </div>
        @empty
            <p class="workspace-muted">El historial se inicia al publicar un cambio.</p>
        @endforelse
    </section>
@endsection
