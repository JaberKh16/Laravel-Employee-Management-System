<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta-description', '')">
    <meta name="author" content="@yield('meta-author', '')">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Anti-FOUC (inline, must run before CSS) --}}
    @include('admin.partials.js.anti-fouc')

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @stack('dashboard_style')

    @stack('styles')
</head>

<body id="page-top"
      class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    {{-- Skip link (accessibility) --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[100]
              focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg">
        {{ __('Skip to content') }}
    </a>

    {{-- Topbar --}}
    @include('admin.partials._navbar')

    {{-- Sidebar --}}
    @include('admin.partials._sidebar')

    {{-- Content wrapper --}}
    <div id="main-content"
         class="pt-16 lg:ml-64 min-h-screen flex flex-col
                transition-[margin-left] duration-200 ease-out">

        {{-- Main content --}}
        <main class="flex-1 p-4 md:p-6">
            @yield('dashboard_content')
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-200
                       dark:bg-gray-900 dark:border-gray-800">
            <div class="px-4 md:px-6 py-4
                        flex flex-col sm:flex-row items-center justify-between gap-2
                        text-xs text-gray-500 dark:text-gray-400">
                <span>
                    &copy; {{ date('Y') }} {{ config('app.name', 'Employee Management System') }}.
                    {{ __('All rights reserved.') }}
                </span>
                <span class="flex items-center gap-1.5">
                    {{ __('Crafted with') }}
                    <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                              clip-rule="evenodd"/>
                    </svg>
                    {{ __('using Tailwind + Flowbite') }}
                </span>
            </div>
        </footer>
    </div>

    {{-- Scroll to top button --}}
    <button type="button"
            id="scroll-to-top"
            aria-label="{{ __('Scroll to top') }}"
            class="fixed bottom-5 right-5 z-50 hidden
                   w-10 h-10 rounded-full
                   bg-blue-600 hover:bg-blue-700
                   text-white
                   shadow-lg shadow-blue-600/30
                   focus:outline-none focus:ring-4 focus:ring-blue-300
                   dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
                   transition-all duration-200
                   opacity-0 translate-y-2
                   data-[visible=true]:opacity-100 data-[visible=true]:translate-y-0">
        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/script.js') }}" defer></script>

    @include('admin.partials.js.scroll-top')

    @stack('scripts')
</body>
</html>