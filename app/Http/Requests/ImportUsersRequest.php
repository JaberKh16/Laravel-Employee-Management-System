<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create users') ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:10240', // 10 MB
            ],
            'default_role' => ['nullable', 'string', 'exists:roles,name'],
            'send_welcome_email' => ['nullable', 'boolean'],
            'update_existing' => ['nullable', 'boolean'],
            'skip_header' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Only CSV, XLSX, or XLS files are allowed.',
            'file.max' => 'The file must not be larger than 10 MB.',
        ];
    }
}