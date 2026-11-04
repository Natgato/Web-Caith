<?php

namespace App\Services;

use App\Models\EditorialRecord;
use App\Models\Media;
use App\Models\Statistic;
use App\Models\Testimonial;
use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EditorialService
{
    public function save(string $module, EditorialRecord $record, array $data, User $user, ?UploadedFile $file = null): EditorialRecord
    {
        $newPath = null;
        $oldPath = $record instanceof Media ? $record->path : null;
        try {
            return DB::transaction(function () use ($module, $record, $data, $user, $file, &$newPath, $oldPath) {
                $fields = array_keys(EditorialRegistry::get($module)['fields']);
                if (in_array('sort_order', $fields)) {
                    $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
                }
                $record->fill(Arr::only($data, [...$fields, 'status']));
                if (! $record->exists) {
                    $record->forceFill(['author_id' => $user->id]);
                }
                if ($record instanceof Media) {
                    if ($file) {
                        $newPath = $file->store('media', 'local');
                        $isPhoto = str_starts_with($file->getMimeType(), 'image/');
                        $record->forceFill(['path' => $newPath, 'mime_type' => $file->getMimeType(), 'size' => $file->getSize(), 'kind' => $isPhoto ? 'photo' : 'video', 'external_url' => null]);
                    }
                    if (! $record->path && ! $record->external_url) {
                        $this->fail('asset_file', 'Adjunta una imagen/video o indica un enlace de video autorizado.');
                    }
                    if ($record->kind === 'photo' && ! $record->path) {
                        $this->fail('asset_file', 'Una fotografía necesita un archivo de imagen válido.');
                    }
                    if ($record->kind === 'photo' && ! str_starts_with($record->mime_type ?? '', 'image/')) {
                        $this->fail('kind', 'El archivo no corresponde a una fotografía.');
                    }
                    if ($record->kind === 'video' && $record->path && ! str_starts_with($record->mime_type ?? '', 'video/')) {
                        $this->fail('kind', 'El archivo no corresponde a un video.');
                    }
                }
                if ($record->media_id) {
                    $media = Media::findOrFail($record->media_id);
                    if (! $record instanceof Testimonial && $media->restricted_to_testimonial) {
                        $this->fail('media_id', 'Esta fotografía está reservada a un testimonio y no puede reutilizarse públicamente.');
                    }
                    if ($record instanceof Testimonial && $media->kind !== 'photo') {
                        $this->fail('media_id', 'Selecciona una fotografía para el testimonio.');
                    }
                }
                if ($record->status === 'published') {
                    $this->assertPublication($record);
                    $record->forceFill(['reviewer_id' => $user->id, 'reviewed_at' => now(), 'published_at' => $record->published_at ?? now()]);
                    if ($record instanceof Statistic) {
                        $record->forceFill(['verified_at' => now()]);
                    }
                } else {
                    $record->forceFill(['reviewer_id' => null, 'reviewed_at' => null, 'published_at' => null]);
                }
                $record->save();
                if ($record instanceof Testimonial && $record->media_id) {
                    // This flag is permanent: deleting a story must never make its photograph a public gallery asset.
                    $record->media->forceFill(['restricted_to_testimonial' => true])->save();
                }
                if ($newPath && $oldPath && $oldPath !== $newPath) {
                    DB::afterCommit(fn () => Storage::disk('local')->delete($oldPath));
                }

                return $record;
            });
        } catch (\Throwable $e) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            throw $e;
        }
    }

    private function assertPublication(EditorialRecord $record): void
    {
        if (! filled($record->source)) {
            $this->fail('source', 'Registra la fuente o procedencia antes de publicar.');
        }
        if (! $record instanceof Media && ! filled($record->body)) {
            $this->fail('body', 'Añade el contenido revisado antes de publicar.');
        }
        if ($record instanceof Testimonial) {
            $consent = $record->consent()->active()->first();
            if (! $consent || ! Storage::disk('local')->exists($consent->evidence_path)) {
                $this->fail('status', 'Guarda primero el borrador y registra un consentimiento vigente con evidencia privada.');
            }
            if ($record->media_id && ! $consent->allow_image) {
                $this->fail('media_id', 'El consentimiento no autoriza la fotografía.');
            }
        }
        if ($record instanceof Media && ! filled($record->authorization_reference)) {
            $this->fail('authorization_reference', 'Registra la autorización del material antes de publicarlo.');
        }
        if ($record->media_id && ! $record->media->isAuthorized()) {
            $this->fail('media_id', 'El material adjunto debe estar revisado y autorizado para publicación.');
        }
        if ($record instanceof Statistic && $record->unit === '%' && $record->value > 100) {
            $this->fail('value', 'Un porcentaje debe estar entre 0 y 100.');
        }
        if ($record instanceof TimelineEvent && ! filled($record->authorization_reference)) {
            $this->fail('authorization_reference', 'Registra la autorización de este hito.');
        }
    }

    public function delete(EditorialRecord $record): void
    {
        $paths = [];
        if ($record instanceof Testimonial && $record->consent?->evidence_path) {
            $paths[] = $record->consent->evidence_path;
        }
        if ($record instanceof Media && $record->path) {
            $paths[] = $record->path;
        }
        DB::transaction(fn () => $record->delete());
        Storage::disk('local')->delete($paths);
    }

    private function fail(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }
}
