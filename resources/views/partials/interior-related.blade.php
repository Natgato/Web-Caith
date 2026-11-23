@if($links)
    <nav class="inside-related" aria-label="Continúa explorando">
        @foreach($links as $name => $label)
            <a href="{{ route($name) }}">{{ $label }} <x-arrow /></a>
        @endforeach
    </nav>
@endif
