<?php

namespace Modules\Asset\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['required', 'exists:institutions,id'],
            'asset_id' => ['required', 'exists:assets,id'],
            'issue_description' => ['required', 'string', 'max:2000'],
            'evidence_photo' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required' => 'Aset wajib dipilih.',
            'issue_description.required' => 'Deskripsi kerusakan wajib diisi.',
        ];
    }
}
