@extends('admin.layout.main')

@push('dashboard_style')
<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 48px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 5px 8px;
        background: #fff;
    }

    .select2-container--default.select2-container--focus
    .select2-selection--multiple {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgb(99 102 241 / 15%);
    }

    .select2-container--default .select2-selection--multiple
    .select2-selection__choice {
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        border-radius: 7px;
        color: #4338ca;
        padding: 3px 7px;
        margin-top: 4px;
    }

    .select2-container--default .select2-selection--multiple
    .select2-selection__choice__remove {
        color: #6366f1;
        margin-right: 5px;
    }

    .select2-dropdown {
        border: 1px solid #c7d2fe;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgb(0 0 0 / 8%);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #4f46e5;
    }

    .dark .select2-container--default .select2-selection--multiple {
        background: #111827;
        border-color: #4b5563;
        color: #f9fafb;
    }

    .dark .select2-container--default .select2-selection--multiple
    .select2-selection__rendered {
        color: #f9fafb;
    }

    .dark .select2-dropdown {
        background: #1f2937;
        border-color: #4b5563;
        color: #f9fafb;
    }

    .dark .select2-container--default .select2-results__option[aria-selected="true"] {
        background: #374151;
    }
</style>

@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-3xl">

        {{-- ============================================================
             PAGE HEADER
             ============================================================ --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ isset($user) ? 'Edit User' : 'Create User' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ isset($user)
                        ? 'Update account details and permissions'
                        : 'Add a new user to the system' }}
                </p>
            </div>

            <a href="{{ route('users.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Back</span>
            </a>
        </header>

        {{-- ============================================================
             FORM CARD
             ============================================================ --}}
        <form method="POST"
              action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @csrf
            @isset($user)
                @method('PUT')
            @endisset

            {{-- Card header strip --}}
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Account Information
                </h2>
            </div>

            {{-- Card body --}}
            <div class="space-y-6 p-6">

                {{-- ============================================================
                     ROLES (multi-select)
                     ============================================================ --}}
                <div>
                    <label for="roleSelect"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Roles <span class="text-red-500">*</span>
                    </label>

                    @php
                        // Precompute the selected role IDs so we can compare
                        // cleanly. Works for both create (empty) and edit.
                        $selectedRoles = old('roles', isset($userRole) ? $userRole : []);

                        // Normalise: if $userRole contains names, keep as-is;
                        // if it contains IDs, keep as-is. Comparison below
                        // handles both by checking name OR id.
                    @endphp

                    <select id="roleSelect"
                            name="roles[]"
                            multiple
                            size="5"
                            class="block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white
                                   @error('roles') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                   @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                   @enderror">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ in_array($role->id, $selectedRoles) || in_array($role->name, $selectedRoles) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Hold <kbd class="rounded border border-gray-300 bg-gray-100 px-1.5 py-0.5 text-[10px] font-mono dark:border-gray-600 dark:bg-gray-700">Ctrl</kbd>
                        (Windows) or <kbd class="rounded border border-gray-300 bg-gray-100 px-1.5 py-0.5 text-[10px] font-mono dark:border-gray-600 dark:bg-gray-700">⌘</kbd> (Mac) to select multiple.
                    </p>

                    @error('roles')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                {{-- ============================================================
                     FIRST + LAST NAME (2-col grid)
                     ============================================================ --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- First name --}}
                    <div>
                        <label for="first_name"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input id="first_name"
                               type="text"
                               name="first_name"
                               value="{{ old('first_name', $user->profile->first_name ?? '') }}"
                               autocomplete="given-name"
                               placeholder="John"
                               class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                      @error('first_name') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                      @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                      @enderror">
                        @error('first_name')
                            <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Last name --}}
                    <div>
                        <label for="last_name"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input id="last_name"
                               type="text"
                               name="last_name"
                               value="{{ old('last_name', $user->profile->last_name ?? '') }}"
                               autocomplete="family-name"
                               placeholder="Doe"
                               class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                      @error('last_name') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                      @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                      @enderror">
                        @error('last_name')
                            <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ============================================================
                     USERNAME
                     ============================================================ --}}
                <div>
                    <label for="username"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input id="username"
                           type="text"
                           name="username"
                           value="{{ old('username', $user->username ?? '') }}"
                           autocomplete="username"
                           autofocus
                           placeholder="e.g. john.doe"
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                  @error('username') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                  @enderror">

                    @error('username')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <div id="username-status"
                        class="mt-2 text-xs font-medium"
                        aria-live="polite"></div>
                </div>

                {{-- ============================================================
                     EMAIL
                     ============================================================ --}}
                <div>
                    <label for="email"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email', $user->email ?? '') }}"
                           required
                           autocomplete="email"
                           placeholder="john.doe@example.com"
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                  @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                  @enderror">
                    @error('email')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <div id="email-status"
                        class="mt-2 text-xs font-medium"
                        aria-live="polite"></div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                {{-- ============================================================
                     PASSWORD
                     ============================================================ --}}
                <div>
                    <label for="password"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                        @if (isset($user))
                            <span class="ml-1 text-xs font-normal text-gray-400">(leave blank to keep current)</span>
                        @else
                            <span class="text-red-500">*</span>
                        @endif
                    </label>

                    <div class="relative">
                        <input id="password"
                               type="password"
                               name="password"
                               {{ isset($user) ? '' : 'required' }}
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="block w-full rounded-xl border bg-white px-3.5 py-2.5 pr-11 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                      @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                      @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                      @enderror">

                        <div id="password-strength" class="mt-3" aria-live="polite">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Password strength
                                </span>
                                <span id="password-strength-label"
                                    class="text-xs font-semibold text-gray-500">
                                    Not entered
                                </span>
                            </div>

                            <div class="grid grid-cols-5 gap-1.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div data-strength-bar="{{ $i }}"
                                        class="h-1.5 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                @endfor
                            </div>

                            <p id="password-feedback"
                            class="mt-2 text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <button type="button"
                                data-toggle-password="password"
                                aria-label="Toggle password visibility"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300">
                            <svg data-eye-open class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg data-eye-closed class="hidden h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- ============================================================
                     CONFIRM PASSWORD
                     ============================================================ --}}
                <div>
                    <label for="password-confirm"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                        @if (! isset($user))
                            <span class="text-red-500">*</span>
                        @endif
                    </label>

                    <div class="relative">
                        <input id="password-confirm"
                               type="password"
                               name="password_confirmation"
                               {{ isset($user) ? '' : 'required' }}
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pr-11 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500 dark:focus:border-indigo-400">
                        

                        <div id="password-confirm-status"
                        class="mt-2 text-xs font-medium"
                        aria-live="polite"></div>
                        <button type="button"
                                data-toggle-password="password-confirm"
                                aria-label="Toggle password visibility"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300">
                            <svg data-eye-open class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg data-eye-closed class="hidden h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            {{-- ============================================================
                 CARD FOOTER (actions)
                 ============================================================ --}}
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span>{{ isset($user) ? 'Update User' : 'Create User' }}</span>
                </button>
            </div>

        </form>

    </div>

@endsection

@push('dashboard_script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const userId = @json(isset($user) ? $user->id : null);

    const endpoints = {
        username: @json(route('validate.username')),
        email: @json(route('validate.email')),
        phone: @json(route('validate.phone')),
        password: @json(route('validate.password')),
    };

    // --------------------------------------------------
    // Select2: searchable multi-select roles
    // --------------------------------------------------
    if (window.jQuery && jQuery.fn.select2) {
        jQuery('#roleSelect').select2({
            placeholder: 'Search and select roles...',
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
        });
    }

    // --------------------------------------------------
    // Reusable status indicator
    // --------------------------------------------------
    function showStatus(id, message, state = 'neutral') {
        const element = document.getElementById(id);
        if (!element) return;

        const styles = {
            neutral: 'text-gray-500 dark:text-gray-400',
            loading: 'text-indigo-500',
            success: 'text-emerald-600 dark:text-emerald-400',
            error: 'text-red-600 dark:text-red-400',
            warning: 'text-amber-600 dark:text-amber-400',
        };

        element.className = 'mt-2 text-xs font-medium ' +
            (styles[state] || styles.neutral);

        element.textContent = message;
    }

    // --------------------------------------------------
    // Debounce helper
    // --------------------------------------------------
    function debounce(callback, delay = 500) {
        let timer;

        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => callback(...args), delay);
        };
    }

    // --------------------------------------------------
    // Generic live availability checker
    // --------------------------------------------------
    async function checkAvailability(field, value, statusId) {
        const params = new URLSearchParams({ [field]: value });

        if (userId) {
            params.set(
                field === 'phone' ? 'ignore_user' : 'ignore',
                userId
            );
        }

        showStatus(statusId, 'Checking availability...', 'loading');

        try {
            const response = await fetch(
                `${endpoints[field]}?${params.toString()}`,
                {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }
            );

            if (!response.ok) {
                throw new Error('Validation request failed');
            }

            return await response.json();
        } catch (error) {
            showStatus(
                statusId,
                'Could not verify availability. Try again.',
                'warning'
            );

            return null;
        }
    }

    // --------------------------------------------------
    // Username auto-generation
    // --------------------------------------------------
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const username = document.getElementById('username');

    let usernameManuallyEdited = Boolean(username?.value.trim());

    const slugify = value => value
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9._-]+/g, '.')
        .replace(/^[._-]+|[._-]+$/g, '')
        .slice(0, 30);

    function generateUsername() {
        if (!username || usernameManuallyEdited) return;

        const first = slugify(firstName?.value || '');
        const last = slugify(lastName?.value || '');

        username.value = [first, last]
            .filter(Boolean)
            .join('.')
            .slice(0, 30);

        username.dispatchEvent(new Event('input', { bubbles: true }));
    }

    firstName?.addEventListener('input', generateUsername);
    lastName?.addEventListener('input', generateUsername);

    const usernameInput = document.getElementById('username');

    const validateUsername = debounce(async () => {
        const value = usernameInput.value.trim();

        if (value.length < 3) {
            showStatus(
                'username-status',
                'Enter at least 3 characters.',
                'neutral'
            );
            return;
        }

        const result = await checkAvailability(
            'username',
            value,
            'username-status'
        );

        // Ignore results if the user has typed something new.
        if (usernameInput.value.trim() !== value || !result) return;

        showStatus(
            'username-status',
            result.message,
            result.valid ? 'success' : 'error'
        );
    });

    usernameInput?.addEventListener('input', () => {
        usernameManuallyEdited = true;
        validateUsername();
    });

    // --------------------------------------------------
    // Email availability
    // --------------------------------------------------
    const emailInput = document.getElementById('email');

    const validateEmail = debounce(async () => {
        const value = emailInput.value.trim();

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            showStatus(
                'email-status',
                'Enter a valid email address.',
                'neutral'
            );
            return;
        }

        const result = await checkAvailability(
            'email',
            value,
            'email-status'
        );

        if (emailInput.value.trim() !== value || !result) return;

        showStatus(
            'email-status',
            result.message,
            result.valid ? 'success' : 'error'
        );
    });

    emailInput?.addEventListener('input', validateEmail);

   

    // --------------------------------------------------
    // Password strength: live server validation
    // --------------------------------------------------
    const passwordInput = document.getElementById('password');
    const strengthLabel = document.getElementById(
        'password-strength-label'
    );
    const passwordFeedback = document.getElementById(
        'password-feedback'
    );
    const strengthBars = document.querySelectorAll(
        '[data-strength-bar]'
    );

    const strengthColors = {
        1: 'bg-red-500',
        2: 'bg-orange-500',
        3: 'bg-amber-500',
        4: 'bg-lime-500',
        5: 'bg-emerald-500',
    };

    let passwordRequest = 0;

    const validatePassword = debounce(async () => {
        const value = passwordInput.value;
        const requestId = ++passwordRequest;

        if (!value) {
            strengthLabel.textContent = 'Not entered';
            passwordFeedback.textContent = '';
            strengthBars.forEach(bar => {
                bar.className =
                    'h-1.5 rounded-full bg-gray-200 dark:bg-gray-700';
            });
            updatePasswordMatch();
            return;
        }

        strengthLabel.textContent = 'Checking...';

        try {
            const response = await fetch(endpoints.password, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ password: value }),
            });

            if (!response.ok) {
                throw new Error('Password validation failed');
            }

            const result = await response.json();

            if (
                requestId !== passwordRequest ||
                passwordInput.value !== value
            ) return;

            strengthLabel.textContent = result.label;
            passwordFeedback.textContent = result.message;

            strengthBars.forEach((bar, index) => {
                bar.className = 'h-1.5 rounded-full ' +
                    (index < result.score
                        ? strengthColors[result.score]
                        : 'bg-gray-200 dark:bg-gray-700');
            });

            strengthLabel.className =
                'text-xs font-semibold ' +
                (result.valid
                    ? 'text-emerald-600 dark:text-emerald-400'
                    : 'text-red-600 dark:text-red-400');

            updatePasswordMatch();
        } catch (error) {
            if (requestId === passwordRequest) {
                passwordFeedback.textContent =
                    'Could not check password strength.';
            }
        }
    }, 250);

    passwordInput?.addEventListener('input', validatePassword);

    // --------------------------------------------------
    // Confirm password match
    // --------------------------------------------------
    const confirmInput = document.getElementById('password-confirm');

    function updatePasswordMatch() {
        if (!passwordInput || !confirmInput) return;

        const password = passwordInput.value;
        const confirmation = confirmInput.value;

        if (!confirmation) {
            showStatus(
                'password-confirm-status',
                '',
                'neutral'
            );
            return;
        }

        if (password === confirmation) {
            showStatus(
                'password-confirm-status',
                '✓ Passwords match.',
                'success'
            );
        } else {
            showStatus(
                'password-confirm-status',
                'Passwords do not match.',
                'error'
            );
        }
    }

    passwordInput?.addEventListener('input', updatePasswordMatch);
    confirmInput?.addEventListener('input', updatePasswordMatch);

    // --------------------------------------------------
    // Show/hide password controls
    // --------------------------------------------------
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(
                button.dataset.togglePassword
            );

            if (!input) return;

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            button.querySelector('[data-eye-open]')
                ?.classList.toggle('hidden', isHidden);

            button.querySelector('[data-eye-closed]')
                ?.classList.toggle('hidden', !isHidden);
        });
    });
});
    </script>
@endpush