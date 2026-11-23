<section class="home-data home-editorial-data wrap" aria-labelledby="home-data-title">
    <div class="home-data-heading">
        <div>
            <h2 id="home-data-title" class="home-editorial-title">{{ $block['title'] }} <em>{{ $block['accent'] }}</em></h2>
            <p class="home-editorial-lead">{{ $block['lead'] }}</p>
        </div>
        <a class="text-link" href="{{ route('statistics') }}">{{ $block['link'] }} <x-arrow /></a>
    </div>
    @if ($statistics->isNotEmpty())
        @php($seriesMax = max(1, (float) $statistics->max('value')))
        <figure class="home-data-figure">
            <figcaption>{{ $block['caption'] }}</figcaption>
            @foreach ($statistics as $stat)
                <div class="home-data-row">
                    <strong class="home-data-year">{{ $stat->year }}</strong>
                    <meter class="home-data-meter" aria-hidden="true" min="0" max="{{ $seriesMax }}" value="{{ $stat->value }}"></meter>
                    <span class="home-data-value"><strong>{{ number_format((float) $stat->value, 0, ',', ' ') }}</strong> personas</span>
                </div>
            @endforeach
        </figure>
        <p class="source-note">Fuente: <a href="{{ $statistics->first()->source_url }}" target="_blank" rel="noopener noreferrer">{{ $statistics->first()->source }}</a>.</p>
    @else
        <p>{{ $block['empty'] }}</p>
    @endif
</section>
