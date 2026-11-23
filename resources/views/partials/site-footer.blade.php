@php
    $footer = $footerDraft ?? \App\Services\SiteStudio::published('pie');
    $footerRoutes = ['community_index' => 'community.index', 'admin_login' => 'admin.login'];
@endphp
<footer class="site-footer"><div class="wrap">
    <div class="footer-main">
        <div>
            <a class="footer-name" href="{{ route('home') }}">{{ $footer['brand']['name'] }}</a>
            <p class="footer-affiliation">{{ $footer['brand']['caption'] }}</p>
        </div>
        <nav class="footer-links" aria-label="Enlaces institucionales">
            @foreach($footer as $destination => $link)
                @continue($destination === 'brand' || ($destination === 'community_index' && auth()->user()?->is_admin))
                <a href="{{ route($footerRoutes[$destination] ?? $destination) }}">{{ $link['label'] }}</a>
            @endforeach
            <a href="#inicio">Volver arriba <x-arrow /></a>
        </nav>
    </div>
    <p class="footer-disclosure">
        {{ $footer['brand']['note'] }}
        @if(\App\Services\DemoAccess::enabled())
            Fotografías de stock con crédito.
        @endif
        {{ $footer['brand']['disclosure'] }}
    </p>
</div></footer>
