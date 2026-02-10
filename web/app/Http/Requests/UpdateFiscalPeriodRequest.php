<?php

namespace App\Http\Requests;

use App\Enums\FiscalPeriodStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFiscalPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isGlobalAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fiscalPeriodId = $this->route('fiscal_period')?->id;

        return [
            'name' => ['required', 'string', 'max:20', Rule::unique('fiscal_periods')->ignore($fiscalPeriodId)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['required', 'boolean'],
            'status' => ['required', Rule::enum(FiscalPeriodStatus::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Periode fiskal sudah ada.',
            'end_date.after' => 'Tanggal akhir harus setelah tanggal mulai.',
            'status.in' => 'Status harus salah satu dari: OPEN, CLOSED, AUDITED.',
        ];
    }
}
