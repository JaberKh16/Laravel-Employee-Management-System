@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Reset Password') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Choose a new password for your account.') }}
                </p>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    {{-- Token (hidden) --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('E-Mail Address') }}
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ $email ?? old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
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
                            {{ __('New Password') }}
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
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
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password-confirm" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input
                            id="password-confirm"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                   dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2
                               text-white bg-blue-700 hover:bg-blue-800
                               focus:ring-4 focus:outline-none focus:ring-blue-300
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center
                               dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        {{ __('Reset Password') }}
                    </button>

                    {{-- Back to login --}}
                    @if (Route::has('login'))
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            {{ __('Remember your password?') }}
                            <a href="{{ route('login') }}"
                               class="font-medium text-blue-600 hover:underline dark:text-blue-500">
                                {{ __('Back to sign in') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection