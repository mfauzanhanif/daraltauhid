<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:255'],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $roleId = $this->route('role')->id;
            
            // Check unique constraint (institution_id, name, guard_name)
            $exists = \App\Models\Role::where('institution_id', $this->institution_id)
                ->where('name', $this->name)
                ->where('guard_name', $this->guard_name)
                ->where('id', '!=', $roleId)
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
