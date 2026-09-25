@extends('admin.layout.main')

@section('title', __('Dashboard'))

@section('dashboard_content')
<div class="container mx-auto px-4 py-8">

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Status flash --}}
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

        {{-- Dashboard card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Card header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Dashboard') }}
                </h1>
            </div>

            {{-- Card body --}}
            <div class="px-6 py-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full
                                bg-green-100 dark:bg-green-900
                                flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ __('You are logged in!') }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Welcome back to your dashboard.') }}
                        </p>

                        @auth
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Signed in as') }}
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ Auth::user()->username ?? Auth::user()->email }}
                                </span>
                            </p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection