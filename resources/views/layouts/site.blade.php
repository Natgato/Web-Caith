<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Casa CAITH, programa del Centro Yanapanakusun en Cusco. Cuidado, educación y autonomía.')">
    <meta name="theme-color" content="#701f35"><title>@yield('title', 'Casa CAITH') — Casa CAITH</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}"><link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=plataforma-1"><link rel="stylesheet" href="{{ asset('css/platform.css') }}?v=1">
    <script src="{{ asset('js/site.js') }}?v=plataforma-1" defer></script>
    <script src="{{ asset('js/forms.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    @include('partials.site-header')
    <main id="contenido">@yield('content')</main>
    @include('partials.site-footer')
</body>
</html>
