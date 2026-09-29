<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => strtolower(trim($this->username ?? '')),
            'email' => strtolower(trim($this->email ?? '')),
            'first_name' => trim($this->first_name ?? ''),
            'last_name' => trim($this->last_name ?? ''),
        ]);
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->withoutTrashed()],
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['nullable', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->withoutTrashed()],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'Username may only contain letters, numbers, dots, underscores, and hyphens.',
            'first_name.regex' => 'First name contains invalid characters.',
            'password.uncompromised' => 'This password has appeared in a data breach. Please choose a different one.',
        ];
    }
}
