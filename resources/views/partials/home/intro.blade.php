<section class="caith-strip" aria-labelledby="home-intro-title">
    <div class="caith-strip-inner">
        <div class="caith-meta-top">
            <span>{{ $block['folio'] }}</span>
            <span>{{ $block['place'] }}</span>
        </div>
        <div class="caith-grid">
            <div class="caith-illustrated-title">
                <h2 id="home-intro-title">
                    <span class="caith-eyebrow">{{ $block['eyebrow'] }}</span>
                    <span class="caith-word-casa">{{ $block['name'] }}</span>
                    <span class="caith-word-caith">{{ $block['emblem'] }} <span class="caith-star" aria-hidden="true">✦</span></span>
                </h2>
                <img class="caith-strip-cat" src="{{ \App\Services\SiteStudio::imageUrl($block, 'images/caith-gato-recostado.png') }}" width="1402" height="1122" alt="" loading="lazy" decoding="async">
                <p class="caith-poster-stamp">{!! nl2br(e($block['values']), false) !!}</p>
            </div>
            <div class="caith-content">
                <span class="caith-text-marks" aria-hidden="true">
                    <span></span><span></span><span></span><span></span>
                </span>
                <h3 class="caith-story-title">
                    <span>{{ $block['title'] }}</span>
                    <strong>{{ $block['accent'] }}</strong>
                </h3>
                <p class="caith-lead">{{ $block['lead'] }}</p>
                <p class="caith-body">{{ $block['body'] }}</p>
                <p class="caith-story-year">
                    <strong>{{ $block['year'] }}</strong>
                    <em>{{ $block['year_note'] }}</em>
                </p>
                <p class="caith-body">{{ $block['closing'] }}</p>
                <div class="caith-action-row">
                    <div>
                        <a class="caith-history-link" href="{{ route('historia') }}">{{ $block['link'] }} <x-arrow /></a>
                        <p class="caith-editorial-note">{{ $block['note'] }}</p>
                    </div>
                    <a class="caith-mission-link" href="{{ route('mision-vision') }}">{{ $block['mission'] }} <x-arrow /></a>
                </div>
            </div>
        </div>
    </div>
</section>
