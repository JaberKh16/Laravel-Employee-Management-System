<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Employee Management'))</title>

    {{-- Anti-FOUC: apply dark class BEFORE CSS loads --}}
    @include('partials.js.anti-fouc')

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/script.js') }}" defer></script>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

    {{-- Skip to content (accessibility) --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50
              focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg">
        {{ __('Skip to content') }}
    </a>

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand (left) --}}
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-2 text-xl font-semibold text-gray-900 dark:text-white">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                        </svg>
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">

                    {{-- Desktop auth links --}}
                    <div class="hidden md:flex items-center gap-2">
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                   class="text-sm font-medium text-gray-700 hover:text-blue-700
                                          dark:text-gray-300 dark:hover:text-blue-500">
                                    {{ __('Login') }}
                                </a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="text-sm font-medium text-white bg-blue-700 hover:bg-blue-800
                                          focus:ring-4 focus:outline-none focus:ring-blue-300
                                          rounded-lg px-4 py-2 text-center
                                          dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @else
                            {{-- User dropdown --}}
                            <button id="user-menu-button"
                                    data-dropdown-toggle="user-menu"
                                    data-dropdown-placement="bottom-end"
                                    type="button"
                                    class="flex items-center gap-2 text-sm rounded-lg
                                           hover:bg-gray-100 dark:hover:bg-gray-700
                                           focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700
                                           px-3 py-2">
                                <span class="w-8 h-8 rounded-full bg-blue-600 text-white
                                             flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                                </span>
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    {{ Auth::user()->username }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown menu --}}
                            <div id="user-menu"
                                 class="hidden z-50 my-2 text-base list-none
                                        bg-white divide-y divide-gray-100 rounded-lg shadow
                                        dark:bg-gray-700 dark:divide-gray-600 w-48">
                                <div class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ Auth::user()->username }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        {{ Auth::user()->email ?? '' }}
                                    </p>
                                </div>
                                <ul class="py-2">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                           class="block px-4 py-2 text-sm text-red-600
                                                  hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-400 dark:hover:text-white">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Hidden logout form --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @endguest
                    </div>

                    {{-- Theme toggle --}}
                    <button id="theme-toggle" type="button"
                            aria-label="{{ __('Toggle dark mode') }}"
                            class="text-gray-500 dark:text-gray-400
                                   hover:bg-gray-100 dark:hover:bg-gray-700
                                   focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700
                                   rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Mobile menu button --}}
                    <button data-collapse-toggle="mobile-menu" type="button"
                            class="md:hidden inline-flex items-center justify-center p-2
                                   text-gray-500 rounded-lg hover:bg-gray-100
                                   focus:outline-none focus:ring-2 focus:ring-gray-200
                                   dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">{{ __('Open main menu') }}</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div class="hidden md:hidden pb-4" id="mobile-menu">
                @guest
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="block px-3 py-2 rounded-lg text-sm font-medium
                                      text-gray-700 hover:bg-gray-100
                                      dark:text-gray-200 dark:hover:bg-gray-700">
                                {{ __('Login') }}
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="block px-3 py-2 rounded-lg text-sm font-medium text-center
                                      text-white bg-blue-700 hover:bg-blue-800
                                      dark:bg-blue-600 dark:hover:bg-blue-700">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @else
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ Auth::user()->username }}
                        </div>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block px-3 py-2 rounded-lg text-sm font-medium text-red-600
                                  hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700">
                            {{ __('Logout') }}
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main id="main-content" class="py-6">
        @yield('content')
    </main>

</body>
</html>