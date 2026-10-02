<?php

namespace App\Http\Requests;

use App\Http\Enums\BranchStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('branch', 'code')],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'manager_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(array_column(BranchStatus::cases(), 'value'))],
        ];
    }

    public function attributes(): array
    {
        return [
            'country_id' => 'country',
            'state_id' => 'state',
            'city_id' => 'city',
            'zip_code' => 'zip code',
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->except(['_token']);

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
                $data[$key] = $value === '' ? null : $value;
            }
        }

        // Uppercase the branch code for consistency
        if (!empty($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        $this->merge($data);
    }
}