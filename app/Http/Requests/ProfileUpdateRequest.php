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
        // return Auth::check();
        return true; // allow for now, since this is only used in the admin panel
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

            // ✅ Avatar: allow up to 10 MB
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],

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
                    if (!$value) {
                        return;
                    }

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
                    if (!$value) {
                        return;
                    }

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
            'username' => 'username',
            'email' => 'email address',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'middle_name' => 'middle name',
            'phone' => 'phone number',
            'avatar' => 'avatar',
            'birthdate' => 'birthdate',
            'gender' => 'gender',
            'bio' => 'bio',
            'address' => 'street address',
            'zip_code' => 'zip code',
            'country_id' => 'country',
            'state_id' => 'state',
            'city_id' => 'city',
            'website' => 'website URL',
            'linkedin' => 'LinkedIn URL',
            'twitter' => 'Twitter / X URL',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
        ];
    }

    public function messages(): array
    {
        return [

            /* ---------- Username ---------- */
            'username.required' => 'Please enter a username.',
            'username.string' => 'The username must be a valid text value.',
            'username.max' => 'The username may not be longer than :max characters.',
            'username.unique' => 'That username is already taken.',

            /* ---------- Email ---------- */
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address (e.g. name@example.com).',
            'email.max' => 'The email address may not be longer than :max characters.',
            'email.unique' => 'That email is already registered.',

            /* ---------- Names ---------- */
            'first_name.required' => 'Please enter your first name.',
            'first_name.string' => 'The first name must be a valid text value.',
            'first_name.max' => 'The first name may not be longer than :max characters.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.string' => 'The last name must be a valid text value.',
            'last_name.max' => 'The last name may not be longer than :max characters.',

            'middle_name.string' => 'The middle name must be a valid text value.',
            'middle_name.max' => 'The middle name may not be longer than :max characters.',

            /* ---------- Phone ---------- */
            'phone.string' => 'The phone number must be a valid text value.',
            'phone.max' => 'The phone number may not be longer than :max characters.',

            /* ---------- Avatar (10 MB) ---------- */
            'avatar.image' => 'The avatar must be an image file (JPG, PNG, or WebP).',
            'avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png, webp.',
            'avatar.max' => 'The avatar may not be larger than 10 MB. Please upload a smaller image.',

            /* ---------- Birthdate ---------- */
            'birthdate.date' => 'Please enter a valid birthdate.',
            'birthdate.before' => 'The birthdate must be a date before today.',

            /* ---------- Gender ---------- */
            'gender.in' => 'The selected gender is invalid. Please choose male, female, or other.',

            /* ---------- Bio ---------- */
            'bio.string' => 'The bio must be a valid text value.',
            'bio.max' => 'The bio may not be longer than :max characters.',

            /* ---------- Address ---------- */
            'address.string' => 'The street address must be a valid text value.',
            'address.max' => 'The street address may not be longer than :max characters.',

            'zip_code.string' => 'The zip code must be a valid text value.',
            'zip_code.max' => 'The zip code may not be longer than :max characters.',

            /* ---------- Location ---------- */
            'country_id.integer' => 'The selected country is invalid.',
            'country_id.exists' => 'The selected country does not exist.',

            'state_id.integer' => 'The selected state is invalid.',
            'state_id.exists' => 'The selected state does not exist.',

            'city_id.integer' => 'The selected city is invalid.',
            'city_id.exists' => 'The selected city does not exist.',

            /* ---------- Social links ---------- */
            'website.url' => 'The website must be a valid URL (e.g. https://example.com).',
            'website.max' => 'The website URL may not be longer than :max characters.',

            'linkedin.url' => 'The LinkedIn link must be a valid URL (e.g. https://linkedin.com/in/yourname).',
            'linkedin.max' => 'The LinkedIn URL may not be longer than :max characters.',

            'twitter.url' => 'The Twitter / X link must be a valid URL (e.g. https://x.com/yourname).',
            'twitter.max' => 'The Twitter / X URL may not be longer than :max characters.',

            /* ---------- Password ---------- */
            'password.string' => 'The password must be a valid text value.',
            'password.min' => 'The password must be at least :min characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one letter and one number.',

            'password_confirmation.string' => 'The password confirmation must be a valid text value.',
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
            'avatar',
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
                $data['birthdate'] = Carbon::parse($data['birthdate'])->format('Y-m-d');
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