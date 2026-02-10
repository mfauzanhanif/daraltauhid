<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization rules:
     * - Global Admin (no institution_id) can create any role
     * - Scoped user can only create roles in their institution (if accessing via institution route)
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        // Global Admin can manage all roles
        if ($user->isGlobalAdmin()) {
            return true;
        }

        // For institution-scoped roles, check if user has access to that institution
        $institutionId = $this->input('institution_id');

        if ($institutionId) {
            return $user->hasRoleInInstitution((int) $institutionId);
        }

        // Non-admin cannot create global roles (null institution_id)
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ];

        // Edge Case Protection:
        // Jika user yang membuat role BUKAN Global Admin, maka institution_id WAJIB diisi.
        // Ini mencegah "Admin MI" tidak sengaja membuat "Global Role" (institution_id = null).
        if (! $this->user()->isGlobalAdmin()) {
            $rules['institution_id'] = ['required', 'exists:institutions,id'];
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check unique constraint (institution_id, name, guard_name)
            $exists = \App\Models\Role::where('institution_id', $this->institution_id)
                ->where('name', $this->name)
                ->where('guard_name', $this->guard_name)
                ->exists();

            if ($exists) {
                $validator->errors()->add('name', 'Role dengan nama ini sudah ada untuk lembaga yang dipilih.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'institution_id.exists' => 'Lembaga tidak valid.',
        ];
    }
}
