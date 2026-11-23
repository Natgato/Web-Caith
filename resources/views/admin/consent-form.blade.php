@php($consent = $testimonial->consent)

@if($testimonial->contributor_id && ! $testimonial->submitted_at)
    <p class="feedback">Este testimonio necesita un envío de la persona autora para revisar el consentimiento.</p>
@else
    <section class="consent-section">
        <h2>Consentimiento protegido</h2>
        <p>Guardar o cambiar el consentimiento devuelve el testimonio a revisión. Publicar es un paso separado. Verifica la identidad, el alcance, el nombre público y la representación cuando corresponda. Mantén la evidencia en privado.</p>

        @if($consent)
            <p>
                Revisado el {{ $consent->reviewed_at?->format('d/m/Y') }}.
                @if($consent->withdrawn_at)
                    Retirado el {{ $consent->withdrawn_at->format('d/m/Y') }}.
                @else
                    {{ $testimonial->consent()->active()->exists() ? 'Vigente' : 'Pendiente de autorización para publicar' }}.
                @endif
            </p>
            <a href="{{ route('admin.consent.evidence', $testimonial) }}">Descargar evidencia privada</a>
        @endif

        <form class="admin-form" method="post" enctype="multipart/form-data" action="{{ route('admin.consent.store', $testimonial) }}">
            @csrf
            @if($testimonial->contributor_id)
                <input type="hidden" name="review_version" value="{{ \App\Services\CommunityText::reviewVersion($testimonial) }}">
            @endif

            <x-field name="details" label="Referencia protegida del consentimiento y su alcance" type="textarea" :value="$consent?->details ?? ''" :required="true" />

            @foreach([
                'allow_text' => 'Autoriza el texto y el nombre público indicado',
                'allow_image' => 'Autoriza la fotografía asociada',
                'involves_minor' => 'Involucra a una persona menor de edad',
                'guardian_verified' => 'Se verificó la representación y autorización aplicable',
            ] as $name => $label)
                <input type="hidden" name="{{ $name }}" value="0">
                <label class="check-row">
                    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $consent?->$name ?? false))>
                    <span>{{ $label }}</span>
                </label>
            @endforeach

            <x-field name="granted_at" label="Fecha de autorización" type="date" :value="$consent?->granted_at?->format('Y-m-d') ?? ''" :required="true" />
            <x-field name="expires_at" label="Vigente hasta (opcional)" type="date" :value="$consent?->expires_at?->format('Y-m-d') ?? ''" />
            <x-field name="evidence" label="Evidencia PDF privada (máximo 5 MB)" type="file" />
            <label class="check-row">
                <input type="checkbox" name="consent_reviewed" value="1" required>
                <span>He verificado una autorización válida y su alcance. La evidencia se mantendrá privada.</span>
            </label>
            <button class="button" type="submit">Guardar consentimiento</button>
        </form>

        @if($consent && ! $consent->withdrawn_at)
            <form class="admin-form" action="{{ route('admin.consent.withdraw', $testimonial) }}" method="post">
                @csrf
                <x-field name="withdrawal_reason" label="Motivo o referencia del retiro" type="textarea" :required="true" />
                <button class="button danger" type="submit">Retirar consentimiento y ocultar historia</button>
            </form>
        @endif
    </section>
@endif
