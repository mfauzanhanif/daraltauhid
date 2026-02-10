<?php

namespace Modules\Asset\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['required', 'exists:institutions,id'],
            'asset_category_id' => ['required', 'exists:asset_categories,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['required', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'useful_life_years' => ['nullable', 'integer', 'min:1'],
            'condition' => ['required', Rule::in(['GOOD', 'SLIGHTLY_DAMAGED', 'HEAVILY_DAMAGED'])],
            'status' => ['nullable', Rule::in(['ACTIVE', 'BORROWED', 'MAINTENANCE', 'LOST', 'DISPOSED'])],
        ];
    }

    public function messages(): array
    {
        return [
            'institution_id.required' => 'Lembaga wajib dipilih.',
            'asset_category_id.required' => 'Kategori aset wajib dipilih.',
            'room_id.required' => 'Ruangan wajib dipilih.',
            'name.required' => 'Nama aset wajib diisi.',
            'purchase_date.required' => 'Tanggal pembelian wajib diisi.',
            'purchase_price.required' => 'Harga pembelian wajib diisi.',
            'condition.required' => 'Kondisi aset wajib dipilih.',
        ];
    }
}
