@if($page)
    @if($page->is_provisional)
        <p class="content-status">Contenido de trabajo pendiente de validación institucional. @if($slug === 'mision-vision') La síntesis de misión y visión no sustituye la formulación oficial. @endif</p>
    @endif
    <div class="article-body">{{ $page->body }}</div>
    @if($page->source)<p class="source-note">Fuente: {{ $page->source }}</p>@endif
    @if($slug === 'derechos')
        <nav class="inside-related" aria-label="Fuentes oficiales de derechos laborales">
            <a href="https://busquedas.elperuano.pe/dispositivo/NL/1889434-1" target="_blank" rel="noopener noreferrer">Ley 31047: texto oficial <x-arrow /></a>
            <a href="https://www.gob.pe/institucion/mtpe/normas-legales/1844983-009-2021-tr" target="_blank" rel="noopener noreferrer">DS 009-2021-TR: reglamento <x-arrow /></a>
        </nav>
    @endif
@else
    <p>Estamos preparando esta información para su publicación institucional.</p>
@endif
