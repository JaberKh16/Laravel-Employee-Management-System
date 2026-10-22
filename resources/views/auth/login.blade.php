@extends('layouts.app')

@section('title', config('app.name') . ' | ' . __('Login'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700
                    transform transition-all duration-500 hover:shadow-xl animate-fade-in-up">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white animate-fade-in">
                    {{ __('Login') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 animate-fade-in delay-100">
                    {{ __('Sign in to your account to continue') }}
                </p>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6">
                {{-- Social Login --}}
                <div class="mb-6 animate-fade-in delay-200">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                {{ __('Or continue with') }}
                            </span>
                        </div>
                    </div>

                    @php
                        $socialProviders = collect(['google', 'facebook', 'github'])
                            ->filter(fn ($provider) => Route::has("social.login.{$provider}") || Route::has('social.login'));
                    @endphp

                    @if ($socialProviders->isNotEmpty())
                        <div class="mb-6 animate-fade-in delay-200">
                            {{-- Divider --}}
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                        {{ __('Or continue with') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Social Buttons --}}
                            <div class="mt-6 grid grid-cols-3 gap-3">
                                @if ($socialProviders->contains('google'))
                                    <a href="{{ route('social.login', 'google') }}"
                                    aria-label="{{ __('Login with Google') }}"
                                    class="...">
                                        {{-- Google SVG --}}
                                    </a>
                                @endif

                                @if ($socialProviders->contains('facebook'))
                                    <a href="{{ route('social.login', 'facebook') }}"
                                    aria-label="{{ __('Login with Facebook') }}"
                                    class="...">
                                        {{-- Facebook SVG --}}
                                    </a>
                                @endif

                                @if ($socialProviders->contains('github'))
                                    <a href="{{ route('social.login', 'github') }}"
                                    aria-label="{{ __('Login with GitHub') }}"
                                    class="...">
                                        {{-- GitHub SVG --}}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="animate-fade-in delay-300">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200">
                            {{ __('E-Mail Address') }}
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            placeholder="name@company.com"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                   dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                   transition-all duration-300 ease-in-out
                                   focus:ring-2 focus:outline-none focus:scale-[1.02] focus:shadow-lg
                                   @error('email')
                                       bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                       focus:ring-red-500 focus:border-red-500
                                       dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                   @else
                                       border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                   @enderror"
                            aria-describedby="email-error"
                        >
                        <p id="email-error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden">
                            <span class="font-medium"></span>
                        </p>

                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500 animate-fade-in-up">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="animate-fade-in delay-400">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200">
                            {{ __('Password') }}
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                   dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                   transition-all duration-300 ease-in-out
                                   focus:ring-2 focus:outline-none focus:scale-[1.02] focus:shadow-lg
                                   @error('password')
                                       bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                       focus:ring-red-500 focus:border-red-500
                                       dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                   @else
                                       border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                   @enderror"
                            aria-describedby="password-error"
                        >
                        <p id="password-error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden">
                            <span class="font-medium"></span>
                        </p>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500 animate-fade-in-up">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between animate-fade-in delay-500">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50
                                           focus:ring-3 focus:ring-blue-300
                                           transition-all duration-200 hover:scale-110 cursor-pointer
                                           dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                                >
                            </div>
                            <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer transition-colors duration-200 hover:text-blue-600 dark:hover:text-blue-500">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500
                                      transition-all duration-200 hover:scale-105 inline-block">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800
                               focus:ring-4 focus:outline-none focus:ring-blue-300
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center
                               transition-all duration-300 ease-in-out
                               hover:scale-[1.02] active:scale-95 hover:shadow-lg
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100
                               dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                               animate-fade-in delay-600"
                    >
                        <span class="submit-text">{{ __('Login') }}</span>
                        <span class="submit-spinner hidden">
                            <svg class="inline w-4 h-4 me-3 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Signing in...') }}
                        </span>
                    </button>

                    {{-- Register link --}}
                    @if (Route::has('register'))
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center animate-fade-in delay-700">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}"
                               class="font-medium text-blue-600 hover:underline dark:text-blue-500
                                      transition-all duration-200 hover:scale-105 inline-block">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Custom Animations --}}
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
        20%, 40%, 60%, 80% { transform: translateX(4px); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .animate-fade-in {
        opacity: 0;
        animation: fadeIn 0.4s ease-out forwards;
    }

    .animate-shake {
        animation: shake 0.4s ease-in-out forwards;
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }
    .delay-600 { animation-delay: 600ms; }
    .delay-700 { animation-delay: 700ms; }

    /* Input focus glow */
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .dark input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }
</style>

{{-- Live Validation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');

        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');

        // Helper: show error
        function showError(input, errorEl, message) {
            input.classList.add('border-red-500', 'bg-red-50', 'text-red-900', 'placeholder-red-700',
                'focus:ring-red-500', 'focus:border-red-500');
            input.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            errorEl.querySelector('span').textContent = message;
            errorEl.classList.remove('hidden');
            errorEl.classList.add('animate-fade-in-up');

            // Remove shake after animation so it can re-trigger
            setTimeout(() => errorEl.classList.remove('animate-fade-in-up'), 400);
        }

        // Helper: clear error
        function clearError(input, errorEl) {
            input.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'placeholder-red-700',
                'focus:ring-red-500', 'focus:border-red-500');
            input.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            errorEl.classList.add('hidden');
            errorEl.querySelector('span').textContent = '';
        }

        // Helper: validate email format
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Email live validation
        emailInput.addEventListener('input', function () {
            const value = this.value.trim();
            if (value === '') {
                clearError(emailInput, emailError);
            } else if (!isValidEmail(value)) {
                showError(emailInput, emailError, '{{ __("Please enter a valid email address.") }}');
            } else {
                clearError(emailInput, emailError);
            }
        });

        // Password live validation
        passwordInput.addEventListener('input', function () {
            const value = this.value;
            if (value === '') {
                clearError(passwordInput, passwordError);
            } else if (value.length < 8) {
                showError(passwordInput, passwordError, '{{ __("Password must be at least 8 characters.") }}');
            } else {
                clearError(passwordInput, passwordError);
            }
        });

        // Form submit validation + loading state
        form.addEventListener('submit', function (e) {
            let valid = true;

            // Email
            const emailValue = emailInput.value.trim();
            if (emailValue === '') {
                showError(emailInput, emailError, '{{ __("The email field is required.") }}');
                valid = false;
            } else if (!isValidEmail(emailValue)) {
                showError(emailInput, emailError, '{{ __("Please enter a valid email address.") }}');
                valid = false;
            }

            // Password
            const passwordValue = passwordInput.value;
            if (passwordValue === '') {
                showError(passwordInput, passwordError, '{{ __("The password field is required.") }}');
                valid = false;
            } else if (passwordValue.length < 8) {
                showError(passwordInput, passwordError, '{{ __("Password must be at least 8 characters.") }}');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                // Shake the form
                form.classList.add('animate-fade-in-up');
                setTimeout(() => form.classList.remove('animate-fade-in-up'), 400);
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('.submit-text').classList.add('hidden');
            submitBtn.querySelector('.submit-spinner').classList.remove('hidden');
        });

        // Remove server-side error classes on first input (they're rendered as inline classes)
        // The live validation will override them on input.
    });
</script>
@endsection