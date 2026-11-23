<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Opinion;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class CommunityController extends Controller
{
    private const TYPES = ['testimonio' => Testimonial::class, 'opinion' => Opinion::class];

    public function index(Request $request)
    {
        $type = $request->query('tipo', 'testimonio');
        abort_unless(is_string($type) && isset(self::TYPES[$type]), 404);
        $entries = $this->owned($request, $type)->latest('updated_at')->paginate(12)->withQueryString();

        return view('community.index', compact('entries', 'type'));
    }

    public function editor(Request $request, string $type, ?int $id = null)
    {
        $entry = $id ? $this->owned($request, $type)->findOrFail($id) : new (self::TYPES[$type]);

        return view('community.editor', compact('entry', 'type'));
    }

    public function save(Request $request, string $type, ?int $id = null)
    {
        $isStory = $type === 'testimonio';
        $submit = $request->input('action') === 'submit';
        $data = $request->validate([
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'title' => ['required', 'string', 'max:180'],
            'public_name' => [$submit ? 'required' : 'nullable', 'string', 'max:100'],
            'body' => $submit ? ['required', 'string', 'min:20', 'max:18000'] : ['nullable', 'string', 'max:18000'],
            'allow_text' => $submit ? ['accepted'] : ['nullable', 'boolean'],
            'allow_image' => $isStory ? ['nullable', 'boolean'] : ['prohibited'],
            'photo' => $isStory ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'dimensions:max_width=5000,max_height=5000', 'max:3072'] : ['prohibited'],
            'photo_alt' => ['nullable', 'required_with:photo', 'string', 'max:250'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $newPath = null;
        try {
            DB::transaction(function () use ($request, $type, $id, $isStory, $submit, $data, &$newPath) {
                $entry = $id ? $this->owned($request, $type)->lockForUpdate()->findOrFail($id) : new (self::TYPES[$type]);
                if ($entry->status === 'published') {
                    throw ValidationException::withMessages(['body' => 'Retira primero la publicación para modificarla y enviarla de nuevo.']);
                }

                $oldMedia = $isStory ? $entry->media : null;
                if ($isStory && $request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $newPath = $file->store('testimonios-privados', 'local');
                    if (! $newPath) {
                        throw ValidationException::withMessages(['photo' => 'No pudimos guardar la foto. Inténtalo de nuevo.']);
                    }
                    $media = new Media;
                    $media->forceFill([
                        'title' => 'Imagen de testimonio · '.Str::limit($data['title'], 140),
                        'slug' => 'testimonio-'.Str::uuid(),
                        'kind' => 'photo',
                        'path' => $newPath,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'alt_text' => $data['photo_alt'],
                        'author_id' => $request->user()->id,
                        'status' => 'draft',
                        'restricted_to_testimonial' => true,
                        'source' => 'Imagen adjuntada por la persona autora del testimonio.',
                    ])->save();
                    $entry->media_id = $media->id;
                } elseif ($isStory && $request->boolean('remove_photo')) {
                    $entry->media_id = null;
                }

                if ($isStory && $submit && $entry->media_id && ! $request->boolean('allow_image')) {
                    throw ValidationException::withMessages(['allow_image' => 'Autoriza la imagen por separado o quítala antes de enviar.']);
                }

                $entry->forceFill([
                    ...Arr::only($data, ['title']),
                    'public_name' => $data['public_name'] ?? '',
                    'body' => $data['body'] ?? '',
                    'status' => $submit ? 'review' : 'draft',
                    'source' => 'Enviado desde el espacio de usuario de Casa CAITH.',
                    'submitted_at' => $submit ? now() : null,
                    'review_feedback' => null,
                    'reviewed_at' => null,
                    'reviewer_id' => null,
                    'published_at' => null,
                ]);
                if (! $entry->exists) {
                    $entry->forceFill([
                        'author_id' => $request->user()->id,
                        'contributor_id' => $request->user()->id,
                        'slug' => 'aporte-'.Str::uuid(),
                    ]);
                }
                if ($isStory) {
                    $entry->forceFill([
                        'testimony_date' => $entry->testimony_date ?? today(),
                        'image_requested_at' => $submit && $entry->media_id ? now() : null,
                        'contributor_withdrawn_at' => $submit ? null : $entry->contributor_withdrawn_at,
                    ]);
                    if ($entry->exists) {
                        $entry->consent()->whereNull('withdrawn_at')->update([
                            'withdrawn_at' => now(),
                            'withdrawal_reason' => 'La persona autora cambió su envío. Se requiere revisar el consentimiento del nuevo texto.',
                        ]);
                    }
                }
                $entry->save();

                if ($oldMedia && $oldMedia->id !== $entry->media_id) {
                    $oldMedia->delete();
                    DB::afterCommit(fn () => Storage::disk('local')->delete($oldMedia->path));
                }

            });
        } catch (Throwable $error) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            throw $error;
        }

        return redirect()->route('community.index', ['tipo' => $type])->with(
            'success', $submit ? 'Enviamos tu texto a revisión editorial.' : 'Guardamos tu borrador.'
        );
    }

    public function withdrawal(Request $request, string $type, int $id)
    {
        $entry = $this->owned($request, $type)->findOrFail($id);

        return view('community.withdraw', compact('entry', 'type'));
    }

    public function withdraw(Request $request, string $type, int $id)
    {
        $request->validate(['confirm' => ['accepted']]);

        DB::transaction(function () use ($request, $type, $id) {
            $entry = $this->owned($request, $type)->lockForUpdate()->findOrFail($id);

            if ($entry instanceof Testimonial) {
                $entry->consent()->whereNull('withdrawn_at')->update([
                    'withdrawn_at' => now(),
                    'withdrawal_reason' => 'Retiro solicitado directamente por la persona autora.',
                ]);
                $entry->forceFill(['contributor_withdrawn_at' => now(), 'image_requested_at' => null]);
            }

            $entry->forceFill([
                'status' => 'hidden',
                'submitted_at' => null,
                'reviewed_at' => null,
                'reviewer_id' => null,
                'published_at' => null,
            ])->save();
        });

        return redirect()->route('community.index', ['tipo' => $type])->with('success', 'Retiramos tu publicación. Ya no está disponible en el sitio público.');
    }

    public function photo(Request $request, int $id)
    {
        $story = $this->owned($request, 'testimonio')->with('media')->findOrFail($id);
        $media = $story->media;
        abort_unless($media?->path && Storage::disk('local')->exists($media->path), 404);

        return response()->file(Storage::disk('local')->path($media->path), [
            'Content-Type' => $media->mime_type,
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function opinions(?string $slug = null)
    {
        $published = Opinion::published();

        return $slug
            ? view('community.opinion', ['entry' => $published->where('slug', $slug)->firstOrFail()])
            : view('community.opinions', ['entries' => $published->latest('published_at')->paginate(12)]);
    }

    private function owned(Request $request, string $type): Builder
    {
        return self::TYPES[$type]::where('contributor_id', $request->user()->id);
    }
}
