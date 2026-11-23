<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSiteController;
use App\Models\Media;
use App\Models\SiteLayout;
use App\Models\SiteLayoutRevision;
use App\Services\SiteStudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SiteStudioController extends Controller
{
    public function edit(string $area)
    {
        $schema = SiteStudio::schema($area);
        $layout = SiteLayout::where('area', $area)->first();
        $snapshot = $layout?->draft ?? $layout?->published;
        $blocks = SiteStudio::arrange($area, $snapshot);
        $revisions = $layout ? SiteLayoutRevision::where('site_layout_id', $layout->id)->with('user')->latest('id')->take(10)->get() : collect();
        $gallery = in_array($area, ['portada', 'interiores']) ? Media::published()->where('kind', 'photo')->where('restricted_to_testimonial', false)
            ->orderBy('title')->get()->filter->isAuthorized() : collect();
        $version = $this->version($layout);

        return view('admin.studio', compact('area', 'schema', 'blocks', 'revisions', 'gallery', 'version', 'layout'));
    }

    public function update(Request $request, string $area)
    {
        $schema = SiteStudio::schema($area);
        $keys = array_keys($schema);
        $rules = [
            'intent' => ['required', Rule::in(['draft', 'publish'])],
            'version' => ['required', 'string', 'size:64'],
            'order' => ['required', 'array', 'size:'.count($keys)],
            'order.*' => ['required', 'distinct', Rule::in($keys)],
        ];

        foreach ($schema as $key => $block) {
            foreach ($block['fields'] as $field => [, $default]) {
                $rules["blocks.{$key}.{$field}"] = $default === null
                    ? ['present', 'nullable', 'integer', Rule::exists('media', 'id')->where('kind', 'photo')->where('restricted_to_testimonial', false)]
                    : ['required', 'string', 'max:'.max(180, mb_strlen($default) * 3)];
            }
        }
        $data = $request->validate($rules);
        $snapshot = ['order' => $data['order'], 'blocks' => $data['blocks']];
        $publish = $data['intent'] === 'publish';

        SiteLayout::firstOrCreate(['area' => $area]);
        DB::transaction(function () use ($area, $data, $snapshot, $publish, $request) {
            $layout = SiteLayout::where('area', $area)->lockForUpdate()->firstOrFail();
            $this->assertVersion($layout, $data['version']);

            $layout->draft = $snapshot;
            if ($publish && $layout->published !== $snapshot) {
                if ($layout->published === null) {
                    $defaults = SiteStudio::arrange($area);
                    SiteLayoutRevision::create([
                        'site_layout_id' => $layout->id,
                        'snapshot' => ['order' => array_keys($defaults), 'blocks' => $defaults],
                    ]);
                }
                $layout->published = $snapshot;
                SiteLayoutRevision::create([
                    'site_layout_id' => $layout->id,
                    'user_id' => $request->user()->id,
                    'snapshot' => $snapshot,
                ]);
            }

            $layout->save();
        });

        return redirect()->route('admin.studio.edit', $area)
            ->with('success', $publish ? 'Cambios publicados en el sitio.' : 'Borrador guardado. Puedes abrir su vista previa.');
    }

    public function preview(Request $request, PublicSiteController $site)
    {
        $area = $request->query('area', 'portada');
        abort_unless(is_string($area), 404);
        SiteStudio::schema($area);
        $layout = SiteLayout::where('area', $area)->first();
        $draft = SiteStudio::arrange($area, $layout?->draft ?? $layout?->published);

        if ($area === 'interiores') {
            return view('admin.studio-interior-preview', ['draft' => $draft, 'schema' => SiteStudio::schema($area)]);
        }

        return $site->renderHome(
            $area === 'portada' ? $draft : SiteStudio::published('portada'),
            $area === 'menu' ? $draft : SiteStudio::published('menu'),
            $area === 'pie' ? $draft : SiteStudio::published('pie'),
            $area
        );
    }

    public function restore(Request $request, string $area, SiteLayoutRevision $revision)
    {
        SiteStudio::schema($area);
        $data = $request->validate(['version' => ['required', 'string', 'size:64']]);

        DB::transaction(function () use ($area, $revision, $data) {
            $layout = SiteLayout::where('area', $area)->whereKey($revision->site_layout_id)->lockForUpdate()->firstOrFail();
            $this->assertVersion($layout, $data['version']);
            $layout->update(['draft' => $revision->snapshot]);
        });

        return back()->with('success', 'Recuperamos la versión como borrador. Publícala cuando esté lista.');
    }

    private function version(?SiteLayout $layout): string
    {
        return hash('sha256', json_encode([$layout?->draft, $layout?->published], JSON_THROW_ON_ERROR));
    }

    private function assertVersion(SiteLayout $layout, string $version): void
    {
        if (! hash_equals($this->version($layout), $version)) {
            throw ValidationException::withMessages(['version' => 'Otra persona guardó esta sección mientras editabas. Recarga para ver la última versión.']);
        }
    }
}
