{{-- Topbar --}}
<nav class="fixed top-0 z-50 w-full h-16
            bg-white/70 backdrop-blur-xl backdrop-saturate-150
            border-b border-gray-200/70
            shadow-[0_1px_2px_rgba(0,0,0,0.02),0_4px_16px_-8px_rgba(0,0,0,0.06)]
            dark:bg-gray-900/70 dark:border-gray-800/70
            dark:shadow-[0_1px_2px_rgba(0,0,0,0.2),0_4px_16px_-8px_rgba(0,0,0,0.4)]">
    <div class="flex items-center justify-between h-full px-4 lg:px-6">

        {{-- Left --}}
        <div class="flex items-center gap-3">
            <button data-drawer-target="sidebar"
                    data-drawer-toggle="sidebar"
                    aria-controls="sidebar"
                    type="button"
                    class="tb-icon-btn lg:hidden">
                <span class="sr-only">{{ __('Open sidebar') }}</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                          d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"/>
                </svg>
            </button>

            <a href="{{ route('home') }}"
               class="flex items-center gap-2.5 lg:hidden group">
                <span class="relative inline-flex items-center justify-center w-8 h-8 rounded-xl
                             bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600 text-white
                             shadow-md shadow-blue-500/20
                             transition-transform duration-300 group-hover:scale-105">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                    </svg>
                </span>
                <span class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white truncate">
                    {{ config('app.name', 'OMS') }}
                </span>
            </a>

            <div class="hidden lg:flex items-center gap-2">
                <span class="text-[13px] font-medium text-gray-400 dark:text-gray-500">{{ __('Pages') }}</span>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <h1 class="text-[13px] font-semibold text-gray-900 dark:text-white">
                    @yield('page-title', __('Dashboard'))
                </h1>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-1">

            {{-- SEARCH — self-contained inline panel --}}
            <div id="topbar-search-wrap" class="relative">
                <button type="button" id="topbar-search-trigger" class="tb-search group"
                        aria-haspopup="listbox" aria-expanded="false"
                        aria-controls="topbar-search-panel">
                    <svg class="w-4 h-4 flex-shrink-0 text-gray-400 transition-colors duration-200
                                group-hover:text-blue-500 dark:group-hover:text-blue-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <span class="flex-1 text-left">{{ __('Search…') }}</span>
                    <kbd class="tb-kbd">⌘K</kbd>
                </button>

                <button type="button" id="topbar-search-trigger-mobile"
                        class="tb-icon-btn md:hidden"
                        aria-haspopup="listbox" aria-expanded="false"
                        aria-controls="topbar-search-panel">
                    <span class="sr-only">{{ __('Search') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </button>

                <div id="topbar-search-panel"
                     class="tb-search-panel hidden"
                     role="listbox"
                     aria-label="{{ __('Available pages') }}">
                    <div class="tb-search-panel-header">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                        <input type="text" id="topbar-search-input" autocomplete="off"
                               placeholder="{{ __('Type to filter…') }}"
                               class="tb-search-panel-input">
                        <kbd class="tb-search-panel-kbd">ESC</kbd>
                    </div>
                    <div class="tb-search-panel-body" id="topbar-search-results"></div>
                    <div class="tb-search-panel-footer">
                        <span><kbd class="tb-mini-kbd">↑</kbd><kbd class="tb-mini-kbd">↓</kbd> {{ __('navigate') }}</span>
                        <span><kbd class="tb-mini-kbd">↵</kbd> {{ __('open') }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="relative">
                <button type="button" id="quick-actions-button"
                        data-dropdown-toggle="quick-actions-menu"
                        data-dropdown-placement="bottom-end"
                        class="tb-icon-btn group">
                    <span class="sr-only">{{ __('Quick actions') }}</span>
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>

                <div id="quick-actions-menu" class="tb-menu hidden w-60">
                    <div class="tb-menu-header">
                        <p class="tb-menu-title">{{ __('Quick actions') }}</p>
                    </div>
                    <ul class="tb-menu-list">
                        @if (Route::has('employees.index'))
                            <li>
                                <a href="{{ route('employees.index') }}" class="tb-menu-item">
                                    <span class="tb-menu-icon bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </span>
                                    {{ __('New employee') }}
                                </a>
                            </li>
                        @endif
                        @if (Route::has('users.create'))
                            <li>
                                <a href="{{ route('users.create') }}" class="tb-menu-item">
                                    <span class="tb-menu-icon bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </span>
                                    {{ __('New user') }}
                                </a>
                            </li>
                        @endif
                        @if (Route::has('branches.create'))
                            <li>
                                <a href="{{ route('branches.create') }}" class="tb-menu-item">
                                    <span class="tb-menu-icon bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </span>
                                    {{ __('New branch') }}
                                </a>
                            </li>
                        @endif
                        <li class="border-t border-gray-100 dark:border-gray-700/60 mt-1 pt-1">
                            <a href="{{ route('home') }}" class="tb-menu-item">
                                <span class="tb-menu-icon bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                {{ __('Reports') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Notifications --}}
            @auth
                <div class="relative">
                    <button type="button"
                            id="notifications-button"
                            data-dropdown-toggle="notifications-menu"
                            data-dropdown-placement="bottom-end"
                            class="tb-icon-btn relative group">
                        <span class="sr-only">{{ __('Notifications') }}</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-rotate-12"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notifications-badge"
                              class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1
                                     rounded-full bg-red-500 text-white text-[10px] font-bold
                                     flex items-center justify-center
                                     ring-2 ring-white dark:ring-gray-900
                                     shadow-sm shadow-red-500/40">
                            <span id="notifications-badge-count">0</span>
                        </span>
                    </button>

                    <div id="notifications-menu" class="tb-menu hidden w-80">
                        <div class="flex items-center justify-between px-4 py-3
                                    border-b border-gray-100 dark:border-gray-700/60">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Notifications') }}</p>
                                <span id="notifications-menu-count"
                                      class="hidden text-[10px] font-semibold px-2 py-0.5 rounded-full
                                             bg-blue-50 text-blue-700 ring-1 ring-blue-200/50
                                             dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20">
                                    0 {{ __('new') }}
                                </span>
                            </div>
                            <button type="button"
                                    id="notifications-mark-all"
                                    class="text-[11px] font-semibold text-blue-600 hover:text-blue-700
                                           dark:text-blue-400 dark:hover:text-blue-300
                                           opacity-0 pointer-events-none transition-opacity duration-150">
                                {{ __('Mark all read') }}
                            </button>
                        </div>

                        <ul id="notifications-list" class="max-h-80 overflow-y-auto tb-scroll"></ul>

                        <div class="border-t border-gray-100 dark:border-gray-700/60">
                            <a href="{{ Route::has('notifications.list') ? route('notifications.list') : '#' }}"
                               class="block px-4 py-2.5 text-center text-xs font-semibold
                                      text-blue-600 hover:bg-blue-50/60
                                      dark:text-blue-400 dark:hover:bg-blue-500/5
                                      transition-colors duration-150">
                                {{ __('View all notifications') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endauth

            {{-- Theme toggle --}}
            <button id="theme-toggle" type="button"
                    aria-label="{{ __('Toggle dark mode') }}"
                    class="tb-icon-btn group">
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 transition-transform duration-300 group-hover:rotate-12"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 transition-transform duration-300 group-hover:rotate-45"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                </svg>
            </button>

            {{-- Settings --}}
            <div class="relative">
                <button type="button" id="settings-button"
                        data-dropdown-toggle="settings-menu"
                        data-dropdown-placement="bottom-end"
                        class="tb-icon-btn group">
                    <span class="sr-only">{{ __('Settings') }}</span>
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>

                <div id="settings-menu" class="tb-menu hidden w-64">
                    <div class="tb-menu-header">
                        <p class="tb-menu-title">{{ __('Settings') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ __('Customize your workspace') }}
                        </p>
                    </div>

                    <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-700/60">
                        <p class="tb-menu-section">{{ __('Appearance') }}</p>

                        <button type="button" id="open-theme-drawer-topbar" class="tb-menu-item w-full">
                            <span class="tb-menu-icon bg-gray-50 text-gray-500 dark:bg-gray-700/50 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                            </span>
                            <span class="flex-1">{{ __('Theme options') }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <button type="button" id="compact-mode-toggle" class="tb-menu-item w-full">
                            <span class="tb-menu-icon bg-gray-50 text-gray-500 dark:bg-gray-700/50 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </span>
                            <span class="flex-1">{{ __('Compact mode') }}</span>
                            <span id="compact-mode-indicator"
                                  class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                         bg-gray-100 text-gray-600 ring-1 ring-gray-200/50
                                         dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600/50">
                                {{ __('Off') }}
                            </span>
                        </button>
                    </div>

                    <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-700/60">
                        <p class="tb-menu-section">{{ __('Language') }}</p>
                        <div class="flex gap-1.5 px-1">
                            <button type="button" data-lang="en" class="lang-btn">EN</button>
                            <button type="button" data-lang="bn" class="lang-btn">বাংলা</button>
                            <button type="button" data-lang="ar" class="lang-btn">عربي</button>
                        </div>
                    </div>

                    <ul class="py-1.5">
                        <li>
                            <label class="tb-menu-item cursor-pointer">
                                <input type="checkbox" id="pref-notifications" checked class="tb-checkbox">
                                <span class="flex-1 text-sm">{{ __('Notifications') }}</span>
                            </label>
                        </li>
                        <li>
                            <label class="tb-menu-item cursor-pointer">
                                <input type="checkbox" id="pref-sounds" class="tb-checkbox">
                                <span class="flex-1 text-sm">{{ __('Sound alerts') }}</span>
                            </label>
                        </li>
                        <li>
                            <a href="{{ Route::has('users.profile') ? route('users.profile') : '#' }}" class="tb-menu-item">
                                <span class="tb-menu-icon bg-gray-50 text-gray-500 dark:bg-gray-700/50 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 text-sm">{{ __('Account settings') }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="border-t border-gray-100 dark:border-gray-700/60">
                        <a href="#" class="tb-menu-item text-xs">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Help & support') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block w-px h-6 bg-gray-200/80 dark:bg-gray-700/60 mx-1"></div>

            @auth
                <button id="user-menu-button"
                        data-dropdown-toggle="user-menu"
                        data-dropdown-placement="bottom-end"
                        type="button"
                        class="group flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl
                               hover:bg-gray-100/70 dark:hover:bg-gray-800/70
                               focus:outline-none focus:ring-2 focus:ring-blue-500/30
                               transition-all duration-200">
                    <span class="relative inline-flex items-center justify-center
                                 w-8 h-8 rounded-full
                                 bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600
                                 text-white text-xs font-semibold
                                 ring-2 ring-white dark:ring-gray-900
                                 shadow-sm shadow-blue-500/20
                                 transition-transform duration-300 group-hover:scale-105">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full
                                     bg-emerald-500 ring-2 ring-white dark:ring-gray-900"></span>
                    </span>
                    <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ Auth::user()->username }}
                    </span>
                    <svg class="hidden md:block w-4 h-4 text-gray-400 transition-transform duration-300
                                group-aria-expanded:rotate-180"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="user-menu" class="tb-menu hidden w-56">
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold text-gray-900 truncate dark:text-white">
                            {{ Auth::user()->username }}
                        </p>
                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                            {{ Auth::user()->email ?? '' }}
                        </p>
                    </div>

                    <ul class="py-1.5 border-t border-gray-100 dark:border-gray-700/60">
                        <li>
                            <a href="{{ route('home') }}" class="tb-menu-item">
                                <span class="tb-menu-icon bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                </span>
                                {{ __('Dashboard') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('users.profile') ? route('users.profile') : '#' }}" class="tb-menu-item">
                                <span class="tb-menu-icon bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                {{ __('Profile') }}
                            </a>
                        </li>
                    </ul>

                    <ul class="py-1.5 border-t border-gray-100 dark:border-gray-700/60">
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="tb-menu-item text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                <span class="tb-menu-icon bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </span>
                                {{ __('Logout') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex text-sm font-medium
                          text-gray-700 hover:text-blue-700 dark:text-gray-300 dark:hover:text-blue-500
                          px-3 py-2 transition-colors">
                    {{ __('Login') }}
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex text-sm font-medium
                              text-white bg-gradient-to-br from-blue-600 to-indigo-600
                              hover:from-blue-700 hover:to-indigo-700
                              shadow-sm shadow-blue-500/30
                              focus:ring-4 focus:outline-none focus:ring-blue-300/50
                              rounded-lg px-4 py-2
                              transition-all duration-200 hover:shadow-md hover:shadow-blue-500/40">
                        {{ __('Register') }}
                    </a>
                @endif
            @endguest
        </div>
    </div>
</nav>

{{-- Notification endpoints --}}
@auth
<script>
    window.NOTIFICATION_ENDPOINTS = {
        index:      @json(route('notifications.index')),
        list:       @json(route('notifications.list')),
        unread:     @json(route('notifications.unread-count')),
        markAll:    @json(route('notifications.mark-all-read')),
        markOne:    @json(route('notifications.read',    ['notification' => '__ID__'])),
        dismissOne: @json(route('notifications.dismiss', ['notification' => '__ID__'])),
        bulkAction: @json(route('notifications.bulk.action', ['action' => '__ACTION__'])),
        csrf:       @json(csrf_token()),
    };
</script>
@endauth

{{-- ========================================================= --}}
{{-- TOPBAR JS                                                  --}}
{{-- ========================================================= --}}

@include('admin.partials.resources.js._topbar-scripts')

{{-- ========================================================= --}}
{{-- TOPBAR CSS                                                 --}}
{{-- ========================================================= --}}

@include('admin.partials.resources.css._topbar-styles')