<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $participation = $this->routeIs('participation.store');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[+0-9 ()-]+$/'],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
            'kind' => ['required', Rule::in($participation ? ['voluntariado', 'actividades', 'patrocinio'] : ['consulta', 'retiro-consentimiento', 'eliminacion', 'acceso-datos'])],
            'availability' => [$participation && $this->input('kind') === 'voluntariado' ? 'required' : 'nullable', 'string', 'max:500'],
            'privacy_consent' => ['required', 'accepted'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return ['privacy_consent.accepted' => 'Necesitamos tu consentimiento para atender esta solicitud.', 'message.min' => 'Describe tu consulta o motivación con al menos 20 caracteres.', 'availability.required' => 'Indica tu disponibilidad para el voluntariado.'];
    }
}
