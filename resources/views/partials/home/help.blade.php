<section class="home-participate" aria-labelledby="home-participate-title">
    <div class="home-participate-inner wrap">
        <div class="home-editorial-copy">
            <h2 id="home-participate-title" class="home-editorial-title">{{ $block['title'] }} <em>{{ $block['accent'] }}</em></h2>
            <p class="home-editorial-lead">{{ $block['lead'] }}</p>
            <a class="text-link" href="{{ route('como-ayudar') }}">{{ $block['link'] }} <x-arrow /></a>
        </div>
        <ul class="home-participate-options">
            @foreach (['voluntariado', 'actividades', 'patrocinio'] as $kind)
                <li>
                    <a href="{{ route('participation.form', $kind) }}">
                        <span><strong>{{ $block[$kind.'_title'] }}</strong><small>{{ $block[$kind.'_lead'] }}</small></span>
                        <x-arrow />
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
