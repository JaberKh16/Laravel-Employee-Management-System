@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-lg">

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Register') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Create your account to get started') }}
                </p>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

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
                            required
                            autocomplete="username"
                            autofocus
                            placeholder="johndoe"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5
                                   dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                                   focus:ring-2 focus:outline-none
                                   @error('username')
                                       bg-red-50 border-red-500 text-red-900 placeholder-red-700
                                       focus:ring-red-500 focus:border-red-500
                                       dark:bg-gray-700 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500
                                   @else
                                       border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500
                                   @enderror"
                        >
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- First name + Last name (2-column grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- First name --}}
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('First name') }}
                            </label>
                            <input
                                id="first_name"
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required
                                autocomplete="given-name"
                                placeholder="John"
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

                        {{-- Last name --}}
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('Last name') }}
                            </label>
                            <input
                                id="last_name"
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required
                                autocomplete="family-name"
                                placeholder="Doe"
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

                    {{-- Password + Confirm (2-column grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                {{ __('Confirm Password') }}
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
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800
                               focus:ring-4 focus:outline-none focus:ring-blue-300
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center
                               dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        {{ __('Register') }}
                    </button>

                    {{-- Login link --}}
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
@endsection