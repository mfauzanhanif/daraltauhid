<?php

namespace Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'institution_id' => ['required', 'exists:institutions,id'],
            'position' => ['required', 'string', 'max:255'],
            'employment_status' => ['required', Rule::in(['PERMANENT', 'CONTRACT', 'HONORARY', 'INTERN'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'is_active' => ['boolean'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'allowance_fixed' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Pegawai wajib dipilih.',
            'employee_id.exists' => 'Pegawai tidak ditemukan.',
            'institution_id.required' => 'Lembaga wajib dipilih.',
            'institution_id.exists' => 'Lembaga tidak ditemukan.',
            'position.required' => 'Jabatan wajib diisi.',
            'employment_status.required' => 'Status kepegawaian wajib dipilih.',
            'employment_status.in' => 'Status kepegawaian tidak valid.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];
    }
}
