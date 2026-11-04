<header class="site-header wrap" id="inicio">
    <a class="wordmark" href="{{ route('home') }}" aria-label="Casa CAITH, inicio"><span class="wordmark-name">Casa CAITH</span><span class="wordmark-caption">Centro Yanapanakusun</span></a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" hidden><span data-menu-label>Menú</span><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M4 8h16M4 16h16"/></svg></button>
    <nav id="main-nav" class="main-nav" aria-label="Navegación principal">
        <a href="{{ route('nosotros') }}" @if(request()->routeIs('nosotros','historia','mision-vision','timeline')) aria-current="page" @endif>Nosotros</a>
        <a href="{{ route('nuestra-labor') }}" @if(request()->routeIs('nuestra-labor','programas','actividades','impacto')) aria-current="page" @endif>Nuestra labor</a>
        <a href="{{ route('realidad-social') }}" @if(request()->routeIs('realidad-social','derechos','trabajo-hogar','trata-explotacion','discriminacion','statistics')) aria-current="page" @endif>Realidad social</a>
        <a href="{{ route('explore') }}" @if(request()->routeIs('explore','news','post','stories','story','gallery')) aria-current="page" @endif>Explora</a>
        <a href="{{ route('como-ayudar') }}" class="nav-action">Participa <x-arrow /></a>
    </nav>
</header>
