<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        ]);
        if ($request->boolean('involves_minor') && ! $request->boolean('guardian_verified')) {
            return back()->withErrors(['guardian_verified' => 'No puede aprobarse sin verificar la autorización del representante y el alcance aplicable.'])->withInput();
        }
        $oldPath = $testimonial->consent?->evidence_path;
        $path = $request->file('evidence')?->store('consents', 'local') ?? $oldPath;
        try {
            DB::transaction(function () use ($testimonial, $data, $path, $request) {
                Testimonial::whereKey($testimonial->id)->lockForUpdate()->firstOrFail();
                $testimonial->consent()->updateOrCreate([], [...Arr::except($data, ['evidence', 'consent_reviewed']),
                    'evidence_path' => $path, 'reviewer_id' => $request->user()->id, 'reviewed_at' => now(), 'withdrawn_at' => null, 'withdrawal_reason' => null]);
                $testimonial->forceFill(['status' => 'review', 'reviewed_at' => null, 'published_at' => null, 'reviewer_id' => null])->save();
            });
        } catch (\Throwable $e) {
            if ($path !== $oldPath) {
                Storage::disk('local')->delete($path);
            } throw $e;
        }
        if ($oldPath && $path !== $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return back()->with('success', 'Consentimiento protegido guardado. Revisa el texto y publícalo por separado.');
    }

    public function withdraw(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate(['withdrawal_reason' => ['required', 'string', 'min:5', 'max:1000']]);
        DB::transaction(function () use ($testimonial, $data) {
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
