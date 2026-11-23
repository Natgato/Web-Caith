<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\CommunityText;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ConsentController extends Controller
{
    public function store(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'details' => ['required', 'string', 'min:20', 'max:5000'], 'allow_text' => ['required', 'boolean'], 'allow_image' => ['required', 'boolean'],
            'involves_minor' => ['required', 'boolean'], 'guardian_verified' => ['required', 'boolean'],
            'granted_at' => ['required', 'date', 'before_or_equal:today'], 'expires_at' => ['nullable', 'date', 'after_or_equal:granted_at', 'after_or_equal:today'],
            'evidence' => [$testimonial->consent?->evidence_path ? 'nullable' : 'required', 'file', 'mimetypes:application/pdf', 'max:5120'],
            'consent_reviewed' => ['required', 'accepted'],
            'review_version' => ['nullable', 'string', 'size:64'],
        ]);
        if ($request->boolean('involves_minor') && ! $request->boolean('guardian_verified')) {
            return back()->withErrors(['guardian_verified' => 'No puede aprobarse sin verificar la autorización del representante y el alcance aplicable.'])->withInput();
        }
        $uploaded = $request->file('evidence')?->store('consents', 'local');
        $oldPath = null;
        try {
            DB::transaction(function () use ($testimonial, $data, $uploaded, $request, &$oldPath) {
                $testimonial = Testimonial::whereKey($testimonial->id)->lockForUpdate()->firstOrFail();
                $previous = $testimonial->consent()->lockForUpdate()->first();
                $oldPath = $previous?->evidence_path;
                $path = $uploaded ?? $oldPath;
                if (! $path || ! Storage::disk('local')->exists($path)) {
                    throw ValidationException::withMessages(['evidence' => 'Adjunta la evidencia vigente en PDF.']);
                }

                if ($testimonial->contributor_id) {
                    if (! hash_equals(CommunityText::reviewVersion($testimonial), $data['review_version'] ?? '')) {
                        throw ValidationException::withMessages(['details' => 'El envío cambió. Vuelve a abrirlo para revisar el texto o la foto actualizados.']);
                    }
                    if (! $testimonial->submitted_at || $testimonial->contributor_withdrawn_at) {
                        throw ValidationException::withMessages(['details' => 'La persona autora debe enviar este testimonio antes de autorizarlo.']);
                    }
                    if ($previous?->withdrawn_at && ! $uploaded) {
                        throw ValidationException::withMessages(['evidence' => 'El envío nuevo necesita un PDF con el consentimiento actualizado.']);
                    }
                    if ($data['allow_image'] && (! $testimonial->image_requested_at || ! $testimonial->media)) {
                        throw ValidationException::withMessages(['allow_image' => 'La persona autora debe autorizar la imagen de este envío.']);
                    }
                    if ($data['allow_text'] && $data['allow_image'] && $testimonial->media) {
                        $testimonial->media->forceFill([
                            'status' => 'published',
                            'reviewer_id' => $request->user()->id,
                            'reviewed_at' => now(),
                            'published_at' => now(),
                            'authorization_reference' => 'Consentimiento privado del testimonio '.$testimonial->id,
                        ])->save();
                    }
                }
                $testimonial->consent()->updateOrCreate([], [...Arr::except($data, ['evidence', 'consent_reviewed', 'review_version']),
                    'evidence_path' => $path, 'reviewer_id' => $request->user()->id, 'reviewed_at' => now(), 'withdrawn_at' => null, 'withdrawal_reason' => null]);
                $testimonial->forceFill(['status' => 'review', 'reviewed_at' => null, 'published_at' => null, 'reviewer_id' => null])->save();
            });
        } catch (\Throwable $e) {
            if ($uploaded) {
                Storage::disk('local')->delete($uploaded);
            }
            throw $e;
        }
        if ($oldPath && $uploaded && $uploaded !== $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return back()->with('success', 'Consentimiento protegido guardado. Revisa el texto y publícalo por separado.');
    }

    public function withdraw(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate(['withdrawal_reason' => ['required', 'string', 'min:5', 'max:1000']]);
        DB::transaction(function () use ($testimonial, $data) {
            $testimonial = Testimonial::whereKey($testimonial->id)->lockForUpdate()->firstOrFail();
            $consent = $testimonial->consent()->lockForUpdate()->firstOrFail();
            $consent->update(['withdrawn_at' => now(), 'withdrawal_reason' => $data['withdrawal_reason']]);
            $testimonial->forceFill(['status' => 'hidden', 'reviewer_id' => null, 'reviewed_at' => null, 'published_at' => null])->save();
        });

        return back()->with('success', 'Consentimiento retirado. El testimonio y su imagen dejaron de estar disponibles públicamente.');
    }

    public function evidence(Testimonial $testimonial)
    {
        $path = $testimonial->consent?->evidence_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->download(Storage::disk('local')->path($path), 'consentimiento.pdf', ['Cache-Control' => 'no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
