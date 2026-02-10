<?php

namespace Modules\Asset\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLendingRequest extends FormRequest
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
            'borrower_user_id' => ['required', 'exists:users,id'],
            'expected_return_at' => ['required', 'date', 'after:now'],
            'purpose' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required' => 'Aset wajib dipilih.',
            'borrower_user_id.required' => 'Peminjam wajib dipilih.',
            'expected_return_at.required' => 'Tanggal pengembalian wajib diisi.',
            'expected_return_at.after' => 'Tanggal pengembalian harus di masa depan.',
        ];
    }
}
