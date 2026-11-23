@php
    $milestones = [
        1994 => ['Nace Casa CAITH', 'La casa de acogida abre en Cusco para acompañar a niñas, adolescentes y jóvenes.', 'casa.webp'],
        2001 => ['Nace el Centro Yanapanakusun', 'Casa CAITH pasa a formar parte del centro y continúa su labor de acogida y acompañamiento.', 'centro.webp'],
        2020 => ['Acompañar durante la pandemia', 'La emergencia sanitaria plantea un nuevo reto para la casa: cómo sostener el acompañamiento en ese contexto.', 'pandemia.webp'],
    ];
@endphp
<section class="home-timeline home-chronicle section-space wrap" aria-labelledby="home-timeline-title">
    <header class="chronicle-heading">
        <h2 id="home-timeline-title">{{ $block['title'] }} <em>{{ $block['accent'] }}</em></h2>
        <a class="quiet-link" href="{{ route('timeline') }}">{{ $block['link'] }} <x-arrow /></a>
    </header>

    @if ($events->isNotEmpty())
        <ol class="chronicle-track">
            @foreach ($events as $event)
                @php
                    [$title, $description, $illustration] = ($event->is_provisional ? ($milestones[$event->year] ?? null) : null) ?? [
                        $event->title,
                        \Illuminate\Support\Str::limit($event->body, 132),
                        $milestones[$event->year][2] ?? null,
                    ];
                @endphp
                <li class="chronicle-milestone">
                    <span class="chronicle-pin" aria-hidden="true"></span>
                    <article @class(['chronicle-event', 'has-illustration' => $illustration])>
                        @if ($illustration)
                            <div class="chronicle-collage">
                                <time class="chronicle-year" datetime="{{ $event->year }}"><span class="chronicle-year-back">{{ substr((string) $event->year, 0, 2) }}</span><span class="chronicle-year-front">{{ substr((string) $event->year, 2) }}</span></time>
                                <img class="chronicle-illustration" src="{{ asset('images/cronologia/'.$illustration) }}" width="960" height="640" alt="" loading="lazy" decoding="async">
                            </div>
                        @else
                            <time class="chronicle-year" datetime="{{ $event->year }}">{{ $event->year }}</time>
                        @endif
                        <div class="chronicle-copy">
                            <h3><a href="{{ route('timeline') }}#hito-{{ $event->id }}">{{ $title }}</a></h3>
                            <p>{{ $description }}</p>
                        </div>
                    </article>
                </li>
            @endforeach
        </ol>
        <p class="chronicle-note">
            Ilustraciones conceptuales.
            @if ($events->contains('is_provisional', true))
                Cronología provisional para revisión institucional.
            @endif
        </p>
    @else
        <p class="empty-state">{{ $block['empty'] }}</p>
    @endif
</section>
