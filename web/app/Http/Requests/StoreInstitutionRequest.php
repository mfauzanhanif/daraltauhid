<?php

namespace App\Http\Requests;

use App\Enums\InstitutionCategory;
use App\Enums\InstitutionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstitutionRequest extends FormRequest
{

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
        return [
            'code' => ['required', 'string', 'max:20', 'unique:institutions,code'],
            'slug' => ['required', 'string', 'max:100', 'unique:institutions,slug'],
            'domain' => ['nullable', 'string', 'max:100', 'unique:institutions,domain'],
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'no_statistic' => ['nullable', 'string', 'max:50'],
            'npsn' => ['nullable', 'string', 'max:20'],
            'is_internal' => ['required', 'boolean'],
            'category' => ['required', Rule::enum(InstitutionCategory::class)],
            'type' => ['required', Rule::enum(InstitutionType::class)],
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
                function ($attribute, $value, $fail) {
                    // Prevent self-reference (will be checked in update)
                    if ($this->route('institution') && $value == $this->route('institution')->id) {
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
