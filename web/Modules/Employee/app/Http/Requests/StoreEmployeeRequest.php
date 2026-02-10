<?php

namespace Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Identitas Nasional
            'nik' => ['required', 'string', 'size:16', 'unique:employees,nik'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:employees,nip'],
            'nuptk' => ['nullable', 'string', 'max:30'],
            'npwp' => ['nullable', 'string', 'max:30'],
            
            // Biodata
            'name' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:employees,email'],
            
            // Pendidikan
            'last_education' => ['nullable', 'string', 'max:50'],
            'major' => ['nullable', 'string', 'max:255'],
            'university' => ['nullable', 'string', 'max:255'],
            
            // Data Bank
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'bank_account_holder' => ['nullable', 'string', 'max:255'],
            
            // Files
            'photo' => ['nullable', 'image', 'max:2048'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'name.required' => 'Nama wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'gender.in' => 'Jenis kelamin harus L atau P.',
        ];
    }
}
