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
                    {{ __('Enter your email and we\'ll send you a reset link.') }}
                </p>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6 space-y-5">

                {{-- Status flash (link sent) --}}
                @if (session('status'))
                    <div class="flex items-start gap-3 p-4 text-sm rounded-lg
                                text-green-800 bg-green-50 border border-green-200
                                dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                         role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

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
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('Send Password Reset Link') }}
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