<section class="home-hero" data-home-hero aria-labelledby="hero-title">
          <div class="hero wrap">
            <div class="hero-copy">
                <h1 id="hero-title">{!! nl2br(e($block['title']), false) !!}</h1>
                <p class="hero-description">{{ $block['lead'] }}</p>
                <a class="text-link" href="{{ route('historia') }}">{{ $block['link'] }} <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14"/></svg></a>
            </div>
            <div class="hero-asides"><p class="hero-origin"><span>{{ $block['since'] }}</span><strong>{{ $block['year'] }}</strong></p></div>
            <figure class="hero-art hero-art-community">
                <img src="{{ \App\Services\SiteStudio::imageUrl($block, 'images/caith-convivencia.png') }}" alt="{{ $block['alt'] }}" width="1536" height="1024" fetchpriority="high">
                <figcaption class="scene-words" aria-hidden="true">@foreach(preg_split('/\R/u', $block['scene']) as $word)<span>{{ $word }}</span>@endforeach</figcaption>
            </figure>
          </div>
        </section>
