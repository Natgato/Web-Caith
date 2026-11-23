<section class="home-stories home-editorial home-editorial-voices wrap" aria-labelledby="home-voices-title">
    <img class="home-editorial-art" src="{{ \App\Services\SiteStudio::imageUrl($block, 'images/portada/historias.webp') }}" width="960" height="640" alt="" loading="lazy" decoding="async">
    <div class="home-editorial-copy">
        <h2 id="home-voices-title" class="home-editorial-title">{{ $block['title'] }} <em>{{ $block['accent'] }}</em></h2>
        <p class="home-editorial-lead">{{ $block['lead'] }}</p>
        @if ($story = $stories->first())
            <figure class="home-voice-excerpt">
                <blockquote>{{ $story->contributor_id ? \App\Services\CommunityText::excerpt($story->body, 190) : \Illuminate\Support\Str::limit(\Illuminate\Support\Str::squish($story->body), 190) }}</blockquote>
                <figcaption>{{ $story->public_name }} · {{ $story->title }}</figcaption>
            </figure>
            <a class="text-link" href="{{ route('story', $story->slug) }}">{{ $block['link'] }} <x-arrow /></a>
        @else
            <p>{{ $block['empty'] }}</p>
            <a class="text-link" href="{{ route('stories') }}">{{ $block['fallback'] }} <x-arrow /></a>
        @endif
    </div>
</section>
