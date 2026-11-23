@php
    $navBlocks = $menuDraft ?? \App\Services\SiteStudio::published('menu');
    $navFamilies = [
        'nosotros' => ['nosotros', 'historia', 'mision-vision', 'timeline'],
        'nuestra-labor' => ['nuestra-labor', 'programas', 'actividades', 'impacto'],
        'realidad-social' => ['realidad-social', 'derechos', 'trabajo-hogar', 'trata-explotacion', 'discriminacion', 'statistics'],
        'explore' => ['explore', 'news', 'post', 'stories', 'story', 'gallery', 'opinions'],
        'como-ayudar' => ['como-ayudar', 'participation.form'],
    ];
@endphp
<header class="site-header-surface" @if(request()->routeIs('home') || (request()->routeIs('admin.studio.preview') && request('area') !== 'interiores')) data-home-header @endif>
  <div class="site-header wrap">
    <a class="wordmark" href="{{ route('home') }}" aria-label="{{ $navBlocks['brand']['name'] }}, inicio">
        <span class="wordmark-name">{{ $navBlocks['brand']['name'] }}</span><span class="wordmark-caption">{{ $navBlocks['brand']['caption'] }}</span>
    </a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" hidden><span data-menu-label>Menú</span><svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M4 8h16M4 16h16"/></svg></button>
    <nav id="main-nav" class="main-nav" aria-label="Navegación principal">
        @foreach($navBlocks as $destination => $link)
            @continue($destination === 'brand')
            <a href="{{ route($destination) }}" @class(['nav-action' => $destination === 'como-ayudar']) @if(request()->routeIs(...$navFamilies[$destination])) aria-current="page" @endif>
                {{ $link['label'] }} @if($destination === 'como-ayudar')<x-arrow />@endif
            </a>
        @endforeach
    </nav>
  </div>
</header>
