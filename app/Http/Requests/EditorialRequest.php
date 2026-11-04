<?php

namespace App\Http\Requests;

use App\Services\EditorialRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditorialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $module = $this->route('module');
        $spec = EditorialRegistry::get($module);
        $model = new $spec['model'];
        $rules = [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($model->getTable(), 'slug')->ignore($this->route('id'))],
            'status' => ['required', Rule::in(array_keys(EditorialRegistry::STATES))],
            'publication_reviewed' => $this->input('status') === 'published' ? ['required', 'accepted'] : ['nullable', 'boolean'],
        ];
        foreach ($spec['fields'] as $field => [$label, $type]) {
            if (isset($rules[$field])) {
                continue;
            }
            $rules[$field] = match ($field) {
                'year' => ['required', 'integer', 'between:1900,'.now()->year],
                'value' => ['required', 'numeric', 'min:0', 'max:99999999999'],
                'sort_order' => ['nullable', 'integer', 'between:0,100000'],
                'source_locator' => ['nullable', 'string', 'max:180'],
                'media_id' => ['nullable', 'integer', 'exists:media,id'],
                'is_provisional' => ['required', 'boolean'],
                'testimony_date' => ['required', 'date', 'before_or_equal:today'],
                'authorization_expires_at' => ['nullable', 'date', 'after:today'],
                'source_url' => ['required', 'url:https', 'max:2000'],
                'external_url' => ['nullable', 'url:https', 'max:2000', 'regex:~^https://(?:www\.)?(?:youtube\.com|youtu\.be|vimeo\.com)/~i'],
                'kind' => ['required', Rule::in(['photo', 'video'])],
                default => [$type === 'textarea' ? 'nullable' : 'required', 'string', 'max:'.($type === 'textarea' ? 30000 : 250)],
            };
        }
        if ($module === 'galeria') {
            $rules['asset_file'] = ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm', 'max:20480'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return ['publication_reviewed.accepted' => 'Confirma la revisión institucional antes de publicar.', 'slug.regex' => 'Usa letras minúsculas, números y guiones en la dirección.'];
    }
}
