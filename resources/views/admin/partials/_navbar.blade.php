{{-- Topbar --}}
<nav class="fixed top-0 z-50 w-full h-16
            bg-white/80 backdrop-blur-md border-b border-gray-200
            dark:bg-gray-900/80 dark:border-gray-800">
    <div class="flex items-center justify-between h-full px-4 lg:px-6">

        {{-- Left: mobile drawer toggle + brand --}}
        <div class="flex items-center gap-3">

            {{-- Mobile sidebar toggle --}}
            <button data-drawer-target="sidebar"
                    data-drawer-toggle="sidebar"
                    aria-controls="sidebar"
                    type="button"
                    class="inline-flex items-center justify-center p-2
                           text-gray-500 rounded-lg
                           hover:bg-gray-100
                           focus:outline-none focus:ring-2 focus:ring-gray-200
                           lg:hidden
                           dark:text-gray-400 dark:hover:bg-gray-800 dark:focus:ring-gray-700">
                <span class="sr-only">{{ __('Open sidebar') }}</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                          d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"/>
                </svg>
            </button>

            {{-- Brand (mobile only — desktop has it in sidebar) --}}
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 lg:hidden">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                             bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                    </svg>
                </span>
                <span class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ config('app.name', 'Employee Management System') }}
                </span>
            </a>

            {{-- Breadcrumb / page title slot (desktop) --}}
            <div class="hidden lg:block">
                <h1 class="text-sm font-semibold text-gray-900 dark:text-white">
                    @yield('page-title', __('Dashboard'))
                </h1>
            </div>
        </div>

        {{-- Right: actions --}}
        <div class="flex items-center gap-1.5">

            {{-- Global search hint (desktop) --}}
            <button type="button"
                    class="hidden md:flex items-center gap-3 px-3 py-1.5
                           text-sm rounded-lg
                           bg-gray-50 border border-gray-200
                           text-gray-500 hover:bg-gray-100 hover:text-gray-700
                           dark:bg-gray-800/60 dark:border-gray-700 dark:text-gray-400
                           dark:hover:bg-gray-800 dark:hover:text-gray-200
                           transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <span>{{ __('Search') }}</span>
                <kbd class="text-[10px] font-semibold px-1.5 py-0.5 rounded
                            bg-white border border-gray-200 text-gray-500
                            dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">⌘K</kbd>
            </button>

            {{-- Notifications --}}
            <button type="button"
                    class="relative inline-flex items-center justify-center p-2
                           text-gray-500 rounded-lg
                           hover:bg-gray-100
                           focus:outline-none focus:ring-2 focus:ring-gray-200
                           dark:text-gray-400 dark:hover:bg-gray-800 dark:focus:ring-gray-700">
                <span class="sr-only">{{ __('Notifications') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                {{-- Unread dot --}}
                <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
            </button>

            {{-- Theme toggle --}}
            <button id="theme-toggle" type="button"
                    aria-label="{{ __('Toggle dark mode') }}"
                    class="inline-flex items-center justify-center p-2
                           text-gray-500 rounded-lg
                           hover:bg-gray-100
                           focus:outline-none focus:ring-2 focus:ring-gray-200
                           dark:text-gray-400 dark:hover:bg-gray-800 dark:focus:ring-gray-700">
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                </svg>
            </button>

            {{-- Divider --}}
            <div class="hidden sm:block w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

            {{-- User dropdown --}}
            @auth
                <button id="user-menu-button"
                        data-dropdown-toggle="user-menu"
                        data-dropdown-placement="bottom-end"
                        type="button"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-lg
                               hover:bg-gray-100
                               focus:outline-none focus:ring-2 focus:ring-gray-200
                               dark:hover:bg-gray-800 dark:focus:ring-gray-700">
                    <span class="inline-flex items-center justify-center
                                 w-8 h-8 rounded-full
                                 bg-gradient-to-br from-blue-500 to-indigo-600
                                 text-white text-xs font-semibold
                                 ring-2 ring-white dark:ring-gray-900">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                    </span>
                    <span class="hidden md:block text-sm font-medium
                                 text-gray-700 dark:text-gray-200">
                        {{ Auth::user()->username }}
                    </span>
                    <svg class="hidden md:block w-4 h-4 text-gray-500 dark:text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div id="user-menu"
                     class="hidden z-50 my-2 text-base list-none
                            bg-white divide-y divide-gray-100 rounded-lg shadow-lg
                            ring-1 ring-black/5
                            dark:bg-gray-800 dark:divide-gray-700 dark:ring-white/10
                            w-56">
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold text-gray-900 truncate dark:text-white">
                            {{ Auth::user()->username }}
                        </p>
                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                            {{ Auth::user()->email ?? '' }}
                        </p>
                    </div>

                    <ul class="py-1.5 text-sm text-gray-700 dark:text-gray-300">
                        <li>
                            <a href="{{ route('home') }}"
                               class="flex items-center gap-2 px-4 py-2
                                      hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                {{ __('Dashboard') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center gap-2 px-4 py-2
                                      hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ __('Profile') }}
                            </a>
                        </li>
                    </ul>

                    <ul class="py-1.5 text-sm text-gray-700 dark:text-gray-300">
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="flex items-center gap-2 px-4 py-2
                                      text-red-600
                                      hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10 dark:hover:text-red-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Logout') }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Hidden logout form --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth

            {{-- Guest links --}}
            @guest
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex text-sm font-medium
                          text-gray-700 hover:text-blue-700
                          dark:text-gray-300 dark:hover:text-blue-500
                          px-3 py-2">
                    {{ __('Login') }}
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex text-sm font-medium
                              text-white bg-blue-700 hover:bg-blue-800
                              focus:ring-4 focus:outline-none focus:ring-blue-300
                              rounded-lg px-4 py-2
                              dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ __('Register') }}
                    </a>
                @endif
            @endguest
        </div>
    </div>
</nav>