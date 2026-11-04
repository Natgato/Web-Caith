<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ParticipationRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InboxController extends Controller
{
    private function model(string $box): string
    {
        return match ($box) {
            'participacion' => ParticipationRequest::class, 'contacto' => ContactMessage::class, default => abort(404)
        };
    }

    private function states(string $box): array
    {
        return $box === 'participacion' ? ['pending' => 'Pendiente', 'review' => 'En revisión', 'accepted' => 'Aceptada', 'rejected' => 'Rechazada'] : ['pending' => 'Pendiente', 'review' => 'En revisión', 'resolved' => 'Resuelta', 'archived' => 'Archivada'];
    }

    public function index(Request $request, string $box)
    {
        $model = $this->model($box);
        $data = $request->validate(['status' => ['nullable', Rule::in(array_keys($this->states($box)))], 'q' => ['nullable', 'string', 'max:100']]);
        $query = $model::query()->when($data['status'] ?? null, fn ($q, $v) => $q->where('status', $v));
        if ($data['q'] ?? null) {
            $query->where('reference', 'like', '%'.$data['q'].'%');
        }

        return view('admin.inbox', ['box' => $box, 'records' => $query->latest()->paginate(15)->withQueryString(), 'states' => $this->states($box)]);
    }

    public function show(string $box, int $id)
    {
        return view('admin.inbox-show', ['box' => $box, 'record' => $this->model($box)::findOrFail($id), 'states' => $this->states($box)]);
    }

    public function update(Request $request, string $box, int $id)
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys($this->states($box)))], 'internal_notes' => ['nullable', 'string', 'max:3000']]);
        $record = $this->model($box)::findOrFail($id);
        $record->forceFill([...$data, 'assigned_to' => $request->user()->id])->save();

        return back()->with('success', 'Solicitud actualizada. No se ha enviado ningún correo.');
    }

    public function destroy(string $box, int $id)
    {
        $this->model($box)::findOrFail($id)->delete();

        return redirect()->route('admin.inbox.index', $box)->with('success', 'Registro y datos personales eliminados.');
    }
}
