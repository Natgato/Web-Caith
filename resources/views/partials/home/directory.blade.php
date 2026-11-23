<section class="home-directory wrap" aria-labelledby="directory-title">
    <h2 id="directory-title">{{ $block['title'] }}</h2>
    <div class="home-directory-grid">
        @foreach (['nosotros', 'nuestra-labor', 'realidad-social', 'stories', 'news', 'gallery'] as $destination)
            <a href="{{ route($destination) }}" @class(['home-directory-entry', 'is-featured' => $destination === 'nuestra-labor'])>
                <h3>{{ $block[$destination.'_title'] }} <x-arrow /></h3>
                <img src="{{ \App\Services\SiteStudio::imageUrl($block, 'images/directorio/'.$destination.'.webp', $destination.'_image') }}" alt="" loading="lazy" decoding="async">
                <p>{{ $block[$destination.'_lead'] }}</p>
            </a>
        @endforeach
    </div>
    <p class="home-directory-credit">{{ $block['note'] }}</p>
</section>
