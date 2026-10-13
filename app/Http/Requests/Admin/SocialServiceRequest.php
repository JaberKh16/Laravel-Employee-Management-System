<?php

namespace App\Http\Requests\Admin;

use App\Models\RegisteredSocialService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // For update we want to ignore the current row on unique provider
        $model = $this->route('social_service');
        $ignoreId = $model instanceof RegisteredSocialService ? $model->id : null;

        return [
            'provider' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('registered_social_services', 'provider')->ignore($ignoreId),
            ],
            'label' => ['required', 'string', 'max:100'],
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['required', 'string', 'max:255'],
            'redirect_uri' => ['required', 'string', 'max:255'],
            'scopes' => ['nullable', 'string'],   // comma-separated input
            'icon' => ['nullable', 'string', 'max:100'],
            'brand_color' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_enabled' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize scopes: "a, b, c" → ["a","b","c"]
        $scopes = $this->input('scopes');
        if (is_string($scopes)) {
            $scopes = array_values(array_filter(array_map('trim', explode(',', $scopes))));
        }

        $this->merge([
            'provider' => strtolower((string) $this->input('provider')),
            'scopes' => $scopes ?: null,
            'is_enabled' => $this->boolean('is_enabled'),
            'is_default' => $this->boolean('is_default'),
            'sort_order' => (int) ($this->input('sort_order') ?? 0),
        ]);
    }

    public function attributes(): array
    {
        return [
            'provider' => __('Provider'),
            'label' => __('Label'),
            'client_id' => __('Client ID'),
            'client_secret' => __('Client Secret'),
            'redirect_uri' => __('Redirect URI'),
        ];
    }
}