<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstitutionRequest extends FormRequest
{
    /**
     * Institution type constants (matches migration ENUM).
     */
    public const TYPES = [
        'YAYASAN',
        'PONDOK',
        'TK',
        'SD',
        'MI',
        'SMP',
        'MTS',
        'SMA',
        'MA',
        'SMK',
        'SLB',
        'MDTA',
        'TPQ',
        'Madrasah',
        'LKSA',
    ];

    /**
     * Institution category constants (matches migration ENUM).
     */
    public const CATEGORIES = [
        'YAYASAN',
        'PONDOK',
        'FORMAL',
        'NON_FORMAL',
        'SOSIAL',
    ];

    /**
     * Determine if the user is authorized to make this request.
     * Hanya Global Admin (role tanpa institution_id) yang bisa mengelola lembaga.
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
        $institutionId = $this->route('institution')->id;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('institutions')->ignore($institutionId)],
            'slug' => ['required', 'string', 'max:100', Rule::unique('institutions')->ignore($institutionId)],
            'domain' => ['nullable', 'string', 'max:100', Rule::unique('institutions')->ignore($institutionId)],
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'no_statistic' => ['nullable', 'string', 'max:50'],
            'npsn' => ['nullable', 'string', 'max:20'],
            'is_internal' => ['required', 'boolean'],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'type' => ['required', Rule::in(self::TYPES)],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'district' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'letterhead_path' => ['nullable', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'exists:institutions,id',
                function ($attribute, $value, $fail) use ($institutionId) {
                    if ($value == $institutionId) {
                        $fail('Institution cannot be its own parent.');
                    }
                },
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Kode lembaga sudah digunakan.',
            'slug.unique' => 'Slug sudah digunakan.',
            'domain.unique' => 'Domain sudah digunakan.',
            'category.in' => 'Kategori harus salah satu dari: YAYASAN, PONDOK, FORMAL, NON_FORMAL, SOSIAL.',
        ];
    }
}
