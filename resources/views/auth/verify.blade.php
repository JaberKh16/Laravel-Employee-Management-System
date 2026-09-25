@extends('layouts.app')

@section('title', __('Verify Your Email Address'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12">
    <div class="w-full max-w-lg">

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Verify Your Email Address') }}
                </h1>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-6 space-y-4">

                {{-- Success alert (resent) --}}
                @if (session('resent'))
                    <div class="flex items-start gap-3 p-4 text-sm rounded-lg
                                text-green-800 bg-green-50 border border-green-200
                                dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                         role="alert">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </span>
                    </div>
                @endif

                {{-- Info icon + message --}}
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 text-blue-600 dark:text-blue-500 mt-0.5"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                              clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>
                </div>

                {{-- Resend form --}}
                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        {{ __('If you did not receive the email') }}:
                    </p>

                    <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 text-sm font-medium
                                       text-white bg-blue-700 hover:bg-blue-800
                                       focus:ring-4 focus:outline-none focus:ring-blue-300
                                       rounded-lg px-4 py-2
                                       dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Resend verification link') }}
                        </button>
                    </form>
                </div>

                {{-- Logout shortcut --}}
                <div class="pt-2 text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700
                                       dark:text-gray-400 dark:hover:text-gray-200 hover:underline">
                            {{ __('Log out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection