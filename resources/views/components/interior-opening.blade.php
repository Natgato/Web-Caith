@props(['title', 'lead' => null, 'back' => null, 'backLabel' => 'Inicio'])
<header {{ $attributes->class('inside-opening') }}>
    @if($back)
        <a class="back-link" href="{{ route($back) }}">{{ $backLabel }}</a>
    @endif
    {{ $slot }}
    <h1>{{ $title }}</h1>
    @if($lead)<p class="inside-lead">{{ $lead }}</p>@endif
</header>
