<section class="home-news home-editorial wrap" aria-labelledby="home-news-title">
    <div class="home-editorial-copy">
        <h2 id="home-news-title" class="home-editorial-title">{{ $block['title'] }} <em>{{ $block['accent'] }}</em></h2>
        <p class="home-editorial-lead">{{ $block['lead'] }}</p>
        @if ($posts->isEmpty())
            <p>{{ $block['empty'] }}</p>
            <a class="text-link" href="{{ route('nuestra-labor') }}">{{ $block['fallback'] }} <x-arrow /></a>
        @else
            <a class="text-link" href="{{ route('news') }}">{{ $block['link'] }} <x-arrow /></a>
        @endif
    </div>
    @if ($posts->isEmpty())
        <img class="home-editorial-art" src="{{ \App\Services\SiteStudio::imageUrl($block, 'images/portada/actualidad.webp') }}" width="960" height="640" alt="" loading="lazy" decoding="async">
    @else
        <div class="home-press-list">
            @foreach ($posts as $post)
                <article class="home-press-item">
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d/m/Y') }}</time>
                    <h3><a href="{{ route('post', $post->slug) }}">{{ $post->title }}</a></h3>
                    @if ($loop->first && $post->media?->isAuthorized() && ! $post->media->restricted_to_testimonial && $post->media->kind === 'photo')
                        <img src="{{ route('media.show', $post->media) }}" alt="{{ $post->media->alt_text }}" loading="lazy" decoding="async">
                    @endif
                    @if ($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                </article>
            @endforeach
        </div>
    @endif
</section>
