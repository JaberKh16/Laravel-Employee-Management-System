@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-lg">

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Register') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Create your account to get started') }}
                </p>
            </div>

            <div class="px-6 py-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-5" autocomplete="off">
                    @csrf

                    {{-- First name + Last name --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('First name') }}
                            </label>
                            <input
                                id="first_name"
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                autocomplete="given-name"
                                placeholder="First Name"
                                class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                       dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                       focus:ring-2 focus:outline-none
                                       @error('first_name')
                                           bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                           focus:ring-red-500 focus:border-red-500
                                           dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                       @else
                                           border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                           dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                       @enderror"
                            >
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('Last name') }}
                            </label>
                            <input
                                id="last_name"
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                autocomplete="family-name"
                                placeholder="Last Name"
                                class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                       dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                       focus:ring-2 focus:outline-none
                                       @error('last_name')
                                           bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                           focus:ring-red-500 focus:border-red-500
                                           dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                       @else
                                           border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                           dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                       @enderror"
                            >
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Username') }}
                        </label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            placeholder="UserName"
                           
                        >
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('E-Mail Address') }}
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="name@company.com"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                   dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                   focus:ring-2 focus:outline-none
                                   @error('email')
                                       bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                       focus:ring-red-500 focus:border-red-500
                                       dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                   @else
                                       border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                   @enderror"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Password') }}
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5 pr-10
                                    dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                    focus:ring-2 focus:outline-none
                                    @error('password')
                                        bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                        focus:ring-red-500 focus:border-red-500
                                        dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                    @else
                                        border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                        dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                    @enderror"
                            >
                            <button
                                type="button"
                                data-toggle-password="password"
                                aria-label="{{ __('Show password') }}"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                            >
                                {{-- Eye (hidden by default) --}}
                                <svg data-eye-icon class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{-- Eye-off (hidden until toggled) --}}
                                <svg data-eye-off-icon class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password-confirm" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Confirm Password') }}
                        </label>
                        <div class="relative">
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 pr-10
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                    dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                            <button
                                type="button"
                                data-toggle-password="password-confirm"
                                aria-label="{{ __('Show password') }}"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                            >
                                <svg data-eye-icon class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg data-eye-off-icon class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800
                               focus:ring-4 focus:outline-none focus:ring-blue-300
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center
                               dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        {{ __('Register') }}
                    </button>

                    @if (Route::has('login'))
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}"
                               class="font-medium text-blue-600 hover:underline dark:text-blue-500">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ---------- Username auto-fill ----------
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput  = document.getElementById('last_name');
        const usernameInput  = document.getElementById('username');

        let userEditedUsername = usernameInput.value.trim() !== '';
        usernameInput.addEventListener('input', () => {
            userEditedUsername = true;
        });

        const slugify = (value) =>
            value.toString()
                .normalize('NFKD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9._-]+/g, '')
                .replace(/^[._-]+|[._-]+$/g, '')
                .substring(0, 30);

        const buildUsername = () => {
            if (userEditedUsername) return;
            const first = slugify(firstNameInput.value || '');
            const last  = slugify(lastNameInput.value  || '');
            let candidate = first;
            if (first && last) candidate = `${first}.${last}`;
            else if (last)     candidate = last;
            usernameInput.value = candidate.substring(0, 30);
        };

        firstNameInput.addEventListener('input', buildUsername);
        lastNameInput.addEventListener('input', buildUsername);

        // ---------- Password show/hide ----------
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.getAttribute('data-toggle-password'));
                if (!input) return;

                const eyeIcon    = button.querySelector('[data-eye-icon]');
                const eyeOffIcon = button.querySelector('[data-eye-off-icon]');
                const isHidden   = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                eyeIcon.classList.toggle('hidden', isHidden);
                eyeOffIcon.classList.toggle('hidden', !isHidden);
                button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                input.focus();
            });
        });
    });
</script>
@endsection