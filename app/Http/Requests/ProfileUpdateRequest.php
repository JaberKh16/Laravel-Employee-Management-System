<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $user = $this->route('user') ?? $this->user();
        $userId = $user?->id;

        return [
            /* ---------- User table ---------- */
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore($userId)
                    ->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($userId)
                    ->whereNull('deleted_at'),
            ],

            /* ---------- Profile table ---------- */
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],

            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],

            // Match JS counter: strip tags BEFORE length check
            'bio' => ['nullable', 'string', 'max:1000'],

            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],

            /* ---------- Cascading location (Laravel 8 safe) ---------- */
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],

            'state_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$value)
                        return;

                    $q = DB::table('states')->where('id', $value);

                    if ($this->filled('country_id')) {
                        $q->where('country_id', $this->input('country_id'));
                    }

                    if (!$q->exists()) {
                        $fail('The selected state does not belong to the chosen country.');
                    }
                },
            ],

            'city_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$value)
                        return;

                    $q = DB::table('cities')->where('id', $value);

                    if ($this->filled('state_id')) {
                        $q->where('state_id', $this->input('state_id'));
                    }

                    if (!$q->exists()) {
                        $fail('The selected city does not belong to the chosen state.');
                    }
                },
            ],

            /* ---------- Social links ---------- */
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],

            /* ---------- Password ---------- */
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Za-z]/',
                'regex:/\d/',
            ],
            'password_confirmation' => ['nullable', 'string'],
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

    public function messages(): array
    {
        return [
            'username.unique' => 'That username is already taken.',
            'email.unique' => 'That email is already registered.',
            'password.regex' => 'The password must contain at least one letter and one number.',
        ];
    }

    public function validatedUserFields(): array
    {
        return array_intersect_key($this->validated(), array_flip([
            'username',
            'email',
        ]));
    }

    public function validatedProfileFields(): array
    {
        // avatar handled separately in controller — exclude it here
        return array_intersect_key($this->validated(), array_flip([
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            'birthdate',
            'gender',
            'bio',
            'address',
            'zip_code',
            'country_id',
            'state_id',
            'city_id',
            'website',
            'linkedin',
            'twitter',
        ]));
    }

    protected function prepareForValidation(): void
    {
        // 1. Only pull fields that actually exist in the form
        $data = $this->only([
            'username',
            'email',
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            'birthdate',
            'gender',
            'bio',
            'address',
            'zip_code',
            'country_id',
            'state_id',
            'city_id',
            'website',
            'linkedin',
            'twitter',
        ]);

        // 2. Trim strings + convert empty strings → null
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
                $data[$key] = $value === '' ? null : $value;
            }
        }

        // 3. Bio: strip HTML tags emitted by CKEditor/TinyMCE
        if (isset($data['bio']) && is_string($data['bio'])) {
            $data['bio'] = strip_tags($data['bio']);
            $data['bio'] = html_entity_decode($data['bio'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $data['bio'] = preg_replace('/\s+/u', ' ', $data['bio']);
            $data['bio'] = trim($data['bio']);
            if ($data['bio'] === '') {
                $data['bio'] = null;
            }
        }

        // 4. Birthdate: normalize any format the browser might send
        if (!empty($data['birthdate'])) {
            try {
                $data['birthdate'] = Carbon::parse($data['birthdate'])
                    ->format('Y-m-d');
            } catch (Throwable $e) {
                // let validation fail with the raw value
            }
        }

        // 5. Social links: auto-prepend https:// if missing scheme
        foreach (['website', 'linkedin', 'twitter'] as $field) {
            if (!empty($data[$field]) && !preg_match('~^https?://~i', $data[$field])) {
                $data[$field] = 'https://' . $data[$field];
            }
        }

        $this->merge($data);
    }
}