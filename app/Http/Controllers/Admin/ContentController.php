<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditorialRequest;
use App\Models\Media;
use App\Services\EditorialRegistry;
use App\Services\EditorialService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(Request $request, string $module)
    {
        $spec = EditorialRegistry::get($module);
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:120'], 'status' => ['nullable', 'in:draft,review,published,hidden,archived']]);
        $query = $spec['model']::query()->when($filters['q'] ?? null, fn ($q, $value) => $q->where('title', 'like', '%'.$value.'%'))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value));

        return view('admin.content-index', ['module' => $module, 'spec' => $spec, 'records' => $query->latest('updated_at')->paginate(15)->withQueryString()]);
    }

    public function create(string $module)
    {
        $spec = EditorialRegistry::get($module);

        return $this->editor($module, new $spec['model']);
    }

    public function edit(string $module, int $id)
    {
        $spec = EditorialRegistry::get($module);

        return $this->editor($module, $spec['model']::findOrFail($id));
    }

    private function editor(string $module, $record)
    {
        return view('admin.content-edit', ['module' => $module, 'record' => $record, 'spec' => EditorialRegistry::get($module),
            'mediaOptions' => Media::orderBy('title')->get(), 'states' => EditorialRegistry::STATES]);
    }

    public function store(EditorialRequest $request, string $module, EditorialService $service)
    {
        $spec = EditorialRegistry::get($module);
        $record = $service->save($module, new $spec['model'], $request->validated(), $request->user(), $request->file('asset_file'));

        return redirect()->route('admin.content.edit', [$module, $record->id])->with('success', 'Contenido guardado.');
    }

    public function update(EditorialRequest $request, string $module, int $id, EditorialService $service)
    {
        $spec = EditorialRegistry::get($module);
        $service->save($module, $spec['model']::findOrFail($id), $request->validated(), $request->user(), $request->file('asset_file'));

        return redirect()->route('admin.content.edit', [$module, $id])->with('success', 'Cambios guardados.');
    }

    public function destroy(string $module, int $id, EditorialService $service)
    {
        $spec = EditorialRegistry::get($module);
        $service->delete($spec['model']::findOrFail($id));

        return redirect()->route('admin.content.index', $module)->with('success', 'Contenido eliminado y archivos privados asociados retirados cuando corresponde.');
    }
}
