@extends('admin.layout.main')

@push('dashboard_style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 — Flowbite skin (same as your create/edit view) */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 6px 10px;
            border-radius: 0.75rem;
            border: 1px solid #d1d5db;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #111827;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            outline: none;
        }
        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -8px rgba(15, 23, 42, 0.15);
            overflow: hidden;
            margin-top: 4px;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #6366f1;
            color: #fff;
        }
        .dark .select2-container--default .select2-selection--single {
            background: #111827;
            border-color: #4b5563;
            color: #f9fafb;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f9fafb;
        }
        .dark .select2-dropdown {
            background: #1f2937;
            border-color: #374151;
        }
        .dark .select2-container--default .select2-results__option { color: #e5e7eb; }
        .dark .select2-search--dropdown .select2-search__field {
            background: #111827; border-color: #4b5563; color: #f9fafb;
        }
    </style>
@endpush

@section('dashboard_content')
@php
    $profile = $user->profile;
@endphp

<div class="mx-auto max-w-5xl">

    {{-- ============================================================
         PAGE HEADER
         ============================================================ --}}
    <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                My Profile
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage your account, personal information, and links
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Back</span>
        </a>
    </header>

    <form method="POST"
          action="{{ route('users.profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ============================================================
             HERO CARD — Avatar + name + email
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="h-32 bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600"></div>

            <div class="relative px-6 pb-6">
                <div class="-mt-16 flex flex-wrap items-end justify-between gap-4">
                    <div class="flex items-end gap-5">
                        {{-- Avatar --}}
                        <div class="relative">
                            <img id="avatarPreview"
                                 src="{{ $profile && $profile->avatar ? asset('storage/' . $profile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name) . '&background=6366f1&color=fff&size=128' }}"
                                 alt="Avatar"
                                 class="h-28 w-28 rounded-2xl border-4 border-white object-cover shadow-lg dark:border-gray-800">

                            <label for="avatarInput"
                                   class="absolute -bottom-2 -right-2 cursor-pointer rounded-full bg-indigo-600 p-2 text-white shadow-md transition-colors hover:bg-indigo-700"
                                   title="Change avatar">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                                </svg>
                                <input id="avatarInput" type="file" name="avatar" accept="image/*" class="hidden">
                            </label>
                        </div>

                        <div class="pb-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                @error('avatar')
                    <p class="mt-3 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ============================================================
             ACCOUNT INFO
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Account Information
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                {{-- Username --}}
                <div>
                    <label for="username" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input id="username" type="text" name="username"
                           value="{{ old('username', $user->username) }}" required
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white
                                  @error('username') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600
                                  @enderror">
                    @error('username')
                        <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $user->email) }}" required
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white
                                  @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600
                                  @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- First name --}}
                <div>
                    <label for="first_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input id="first_name" type="text" name="first_name"
                           value="{{ old('first_name', $user->first_name) }}" required
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                {{-- Last name --}}
                <div>
                    <label for="last_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input id="last_name" type="text" name="last_name"
                           value="{{ old('last_name', $user->last_name) }}" required
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        {{-- ============================================================
             PERSONAL INFO
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Personal Information
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                {{-- Phone --}}
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input id="phone" type="text" name="phone"
                           value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+1 555 000 0000"
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                {{-- Birthdate --}}
                <div>
                    <label for="birthdate" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
                    <input id="birthdate" type="date" name="birthdate"
                           value="{{ old('birthdate', optional($profile->birthdate ?? null)->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                {{-- Gender --}}
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border px-4 py-2 text-sm font-medium transition-colors
                                          peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                          {{ old('gender', $profile->gender ?? '') === $val ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'border-gray-300 text-gray-700 dark:border-gray-600 dark:text-gray-300' }}">
                                <input type="radio" name="gender" value="{{ $val }}" class="sr-only"
                                       {{ old('gender', $profile->gender ?? '') === $val ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Bio --}}
                <div class="sm:col-span-2">
                    <label for="bio" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                    <textarea id="bio" name="bio" rows="3" maxlength="1000"
                              placeholder="Tell us a bit about yourself..."
                              class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ old('bio', $profile->bio ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ============================================================
             ADDRESS
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Address
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                {{-- Country --}}
                <div>
                    <label for="country_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                    <select id="country_id" name="country_id" class="js-select w-full">
                        <option value="">— Select country —</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}" @selected(old('country_id', $profile->country_id ?? '') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- State --}}
                <div>
                    <label for="state_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                    <select id="state_id" name="state_id" class="js-select w-full">
                        <option value="">— Select state —</option>
                        @foreach ($states as $s)
                            <option value="{{ $s->id }}" @selected(old('state_id', $profile->state_id ?? '') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- City --}}
                <div>
                    <label for="city_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                    <select id="city_id" name="city_id" class="js-select w-full">
                        <option value="">— Select city —</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c->id }}" @selected(old('city_id', $profile->city_id ?? '') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Zip --}}
                <div>
                    <label for="zip_code" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Zip Code</label>
                    <input id="zip_code" type="text" name="zip_code"
                           value="{{ old('zip_code', $profile->zip_code ?? '') }}" placeholder="12345"
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                {{-- Address --}}
                <div class="sm:col-span-2">
                    <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Street Address</label>
                    <input id="address" type="text" name="address"
                           value="{{ old('address', $profile->address ?? '') }}" placeholder="123 Main St"
                           class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        {{-- ============================================================
             SOCIAL LINKS
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Social Links
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6">
                @foreach ([
                    'website'  => ['label' => 'Website',  'placeholder' => 'https://example.com',      'icon' => 'M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z'],
                    'linkedin' => ['label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/in/…', 'icon' => 'M4.98 3.5a2.5 2.5 0 11.02 5 2.5 2.5 0 01-.02-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.4c0-1.3-.03-3-1.83-3-1.83 0-2.1 1.43-2.1 2.9V21H9z'],
                    'twitter'  => ['label' => 'Twitter / X', 'placeholder' => 'https://x.com/…',        'icon' => 'M18.9 2H22l-7 8 8.2 12h-6.4l-5-7.3L5.9 22H2.8l7.5-8.6L2.3 2h6.6l4.5 6.6L18.9 2z'],
                ] as $field => $meta)
                    <div>
                        <label for="{{ $field }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $meta['label'] }}
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="{{ $meta['icon'] }}"/>
                                </svg>
                            </span>
                            <input id="{{ $field }}" type="url" name="{{ $field }}"
                                   value="{{ old($field, $profile->{$field} ?? '') }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============================================================
             SECURITY / PASSWORD
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Change Password
                </h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank to keep your current password</p>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                {{-- New password --}}
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" autocomplete="new-password"
                               placeholder="••••••••"
                               class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pr-11 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <button type="button" data-toggle-password="password"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg data-eye-open class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg data-eye-closed class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                               placeholder="••••••••"
                               class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pr-11 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <button type="button" data-toggle-password="password_confirmation"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg data-eye-open class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg data-eye-closed class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
             STICKY ACTION BAR
             ============================================================ --}}
        <div class="sticky bottom-4 z-10 flex flex-wrap items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white/95 px-6 py-4 shadow-lg backdrop-blur dark:border-gray-700 dark:bg-gray-800/95">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                <span>Save Changes</span>
            </button>
        </div>

    </form>
</div>
@endsection

@push('dashboard_script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            // Init Select2 on all .js-select dropdowns
            $('.js-select').select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%',
            });
        });

        // Avatar live preview
        document.getElementById('avatarInput')?.addEventListener('change', function (e) {
            const file = e.target.files?.[0];
            if (! file) return;
            const reader = new FileReader();
            reader.onload = ev => document.getElementById('avatarPreview').src = ev.target.result;
            reader.readAsDataURL(file);
        });

        // Password visibility toggles
        document.querySelectorAll('[data-toggle-password]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.togglePassword);
                if (! input) return;
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.querySelector('[data-eye-open]')?.classList.toggle('hidden', isHidden);
                btn.querySelector('[data-eye-closed]')?.classList.toggle('hidden', ! isHidden);
            });
        });
    </script>
@endpush