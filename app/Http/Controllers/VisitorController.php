<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitorRequest;
use App\Models\ContactMessage;
use App\Models\ParticipationRequest;
use Illuminate\Support\Arr;

class VisitorController extends Controller
{
    public function store(VisitorRequest $request)
    {
        abort_unless(config('caith.forms_enabled'), 503, 'Los formularios están pendientes de habilitación institucional.');
        $participation = $request->routeIs('participation.store');
        $class = $participation ? ParticipationRequest::class : ContactMessage::class;
        $fields = ['name', 'email', 'phone', 'message', 'kind'];
        if ($participation) {
            $fields[] = 'availability';
        }
        $record = new $class(Arr::only($request->validated(), $fields));
        $record->consented_at = now();
        $record->privacy_version = config('caith.privacy_version');
        $record->save();

        return redirect()->route($participation ? 'participation.form' : 'contact', $participation ? $request->validated('kind') : [])
            ->with('success', 'Recibimos tu solicitud. Referencia: '.$record->reference.'. Se guardó para revisión; no se envió un correo automático.');
    }
}
