<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Administración') — Casa CAITH</title>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=plataforma-1">
    <link rel="stylesheet" href="{{ asset('css/platform.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/editorial-workspace.css') }}">
    <script src="{{ asset('js/forms.js') }}" defer></script>
    @stack('scripts')
</head>
<body class="admin-body editorial-admin">
<a class="skip-link" href="#admin-main">Saltar al contenido</a>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">Casa CAITH</a>
        <p>Administración editorial</p>
        <nav aria-label="Panel administrativo">
            <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Resumen</a>
            <p class="admin-nav-label">Sitio web</p>
            <a href="{{ route('admin.studio.edit', 'portada') }}" @if(request()->routeIs('admin.studio.*')) aria-current="page" @endif>Editor del sitio</a>
            @foreach(\App\Services\EditorialRegistry::modules() as $key => $spec)
                <a href="{{ route('admin.content.index', $key) }}" @if(request()->route('module') === $key) aria-current="page" @endif>{{ $spec['label'] }}</a>
            @endforeach
            <p class="admin-nav-label">Bandejas</p>
            <a href="{{ route('admin.inbox.index', 'participacion') }}" @if(request()->route('box') === 'participacion') aria-current="page" @endif>Participación</a>
            <a href="{{ route('admin.inbox.index', 'contacto') }}" @if(request()->route('box') === 'contacto') aria-current="page" @endif>Contacto y privacidad</a>
            <a href="{{ route('home') }}">Ver sitio público <x-arrow /></a>
        </nav>
        <p class="admin-user">{{ auth()->user()->name }}</p>
        <form action="{{ route('admin.logout') }}" method="post">@csrf<button class="logout" type="submit">Cerrar sesión</button></form>
    </aside>
    <main id="admin-main" class="admin-main">
        <header class="workspace-heading">
            <div><h1>@yield('title', 'Administración')</h1>@hasSection('lead')<p>@yield('lead')</p>@endif</div>
            @yield('actions')
        </header>
        <x-notice />
        @yield('content')
    </main>
</div>
</body>
</html>
