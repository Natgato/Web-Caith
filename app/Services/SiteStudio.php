<?php

namespace App\Services;

use App\Models\Media;
use App\Models\SiteLayout;
use Illuminate\Support\Facades\Storage;

class SiteStudio
{
    public static function schema(string $area): array
    {
        $schema = $area === 'interiores' ? self::interiorSchema() : (config('site-studio')[$area] ?? null);
        abort_unless($schema, 404);

        return $schema;
    }

    public static function arrange(string $area, ?array $snapshot = null): array
    {
        $schema = self::schema($area);
        $order = array_unique(array_merge($snapshot['order'] ?? [], array_keys($schema)));
        $blocks = [];

        foreach ($order as $key) {
            if (! isset($schema[$key])) {
                continue;
            }

            foreach ($schema[$key]['fields'] as $field => [, $default]) {
                $blocks[$key][$field] = $snapshot['blocks'][$key][$field] ?? $default;
            }
        }

        return $blocks;
    }

    public static function published(string $area): array
    {
        return self::arrange($area, SiteLayout::where('area', $area)->first()?->published);
    }

    public static function interior(string $key): array
    {
        return self::published('interiores')[$key];
    }

    private static function interiorSchema(): array
    {
        $blocks = [];
        foreach (config('site-interior-editor') as $key => [$label, $title, $lead]) {
            $blocks[$key] = ['label' => $label, 'fields' => ['title' => ['Titular', $title], 'lead' => ['Bajada', $lead]]];
        }
        foreach (config('interiors.covers') as $key => $cover) {
            $fields = [
                'title' => ['Titular', $cover['title'][0]],
                'accent' => ['Énfasis del titular', $cover['title'][1]],
                'lead' => ['Bajada', $cover['lead']],
                'image' => ['Ilustración de apertura', null],
            ];
            $index = 0;
            foreach ($cover['pillars'] as $title => $lead) {
                $index++;
                $fields['pillartitle_'.$index] = ['Pilar '.$index.' · título', (string) $title];
                $fields['pillarlead_'.$index] = ['Pilar '.$index.' · descripción', $lead];
            }
            $blocks[$key] = ['label' => config('site.pages.'.$key.'.title', $key), 'fields' => $fields];
        }
        foreach (config('interiors.articles') as $key => [, $lead]) {
            $blocks[$key] = ['label' => config('site.pages.'.$key.'.title', $key), 'fields' => [
                'lead' => ['Bajada', $lead],
                'image' => ['Ilustración de apertura', null],
            ]];
        }
        foreach (config('interiors.participation') as $key => [$title, $lead]) {
            $blocks['participa-'.$key] = ['label' => 'Formulario · '.ucfirst($key), 'fields' => [
                'title' => ['Titular', $title], 'lead' => ['Bajada', $lead],
            ]];
        }

        return $blocks;
    }

    public static function imageUrl(array $block, string $file, string $field = 'image'): string
    {
        $media = ($block[$field] ?? null) ? Media::where('kind', 'photo')->where('restricted_to_testimonial', false)->find($block[$field]) : null;

        return $media?->isAuthorized() && $media->path && Storage::disk('local')->exists($media->path) ? route('media.show', $media) : asset($file);
    }
}
