<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO: Add authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $periodId = $this->route('academic_period')->id;
            
            $exists = \App\Models\AcademicPeriod::where('academic_year_id', $this->academic_year_id)
                ->where('name', $this->name)
                ->where('id', '!=', $periodId)
                ->exists();

            if ($exists) {
                $validator->errors()->add('name', 'Semester ini sudah ada untuk tahun ajaran yang dipilih.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'academic_year_id.exists' => 'Tahun ajaran tidak valid.',
            'name.in' => 'Semester harus Ganjil atau Genap.',
            'end_date.after' => 'Tanggal akhir harus setelah tanggal mulai.',
        ];
    }
}
