<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private function file(Media $media)
    {
        abort_unless($media->path && Storage::disk('local')->exists($media->path), 404);

        return response()->file(Storage::disk('local')->path($media->path), ['Content-Type' => $media->mime_type, 'Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }

    public function show(Media $media)
    {
        abort_unless($media->isAuthorized() && ! $media->restricted_to_testimonial, 404);

        return $this->file($media);
    }

    public function testimonial(string $slug)
    {
        $story = Testimonial::authorized()->with(['consent', 'media'])->where('slug', $slug)->firstOrFail();
        abort_unless($story->consent->allow_image && $story->media?->isAuthorized() && Storage::disk('local')->exists($story->consent->evidence_path), 404);

        return $this->file($story->media);
    }

    public function admin(Media $media)
    {
        return $this->file($media);
    }
}
