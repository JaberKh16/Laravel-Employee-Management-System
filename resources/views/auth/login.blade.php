@extends('layouts.app')

@section('title', __('Login'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Login') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Sign in to your account to continue') }}
                </p>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
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

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50
                                           focus:ring-3 focus:ring-blue-300
                                           dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                                >
                            </div>
                            <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800
                               focus:ring-4 focus:outline-none focus:ring-blue-300
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center
                               dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        {{ __('Login') }}
                    </button>

                    {{-- Register link --}}
                    @if (Route::has('register'))
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}"
                               class="font-medium text-blue-600 hover:underline dark:text-blue-500">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection