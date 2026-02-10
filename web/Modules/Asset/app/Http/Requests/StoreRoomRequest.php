<?php

namespace Modules\Asset\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['required', 'exists:institutions,id'],
            'building_id' => ['required', 'exists:buildings,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'floor_number' => ['nullable', 'integer', 'min:1'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'pic_user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'institution_id.required' => 'Lembaga wajib dipilih.',
            'building_id.required' => 'Gedung wajib dipilih.',
            'name.required' => 'Nama ruangan wajib diisi.',
        ];
    }
}
