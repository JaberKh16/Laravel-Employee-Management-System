<?php

namespace App\Http\Requests;

use App\Http\Enums\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('departments', 'name')->ignore($departmentId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'floor'       => ['nullable', 'string', 'max:50'],
            'status'      => ['required', 'integer', Rule::in(ActiveStatus::values())],
            'manager_id'  => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A department with that name already exists.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value) === '' ? null : trim($value);
            }
        }

        // Ensure status is an int (form posts strings)
        if (isset($data['status'])) {
            $data['status'] = (int) $data['status'];
        }

        $this->merge($data);
    }
}