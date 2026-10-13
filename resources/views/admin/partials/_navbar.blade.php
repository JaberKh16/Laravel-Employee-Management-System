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
                            <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}"
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
        unread:     @json(route('notifications.unread-count')),
        markAll:    @json(route('notifications.mark-all-read')),
        markOne:    @json(route('notifications.read',    ['notification' => '__ID__'])),
        dismissOne: @json(route('notifications.dismiss', ['notification' => '__ID__'])),
        csrf:       @json(csrf_token()),
    };
</script>
@endauth

{{-- ========================================================= --}}
{{-- TOPBAR JS                                                  --}}
{{-- ========================================================= --}}
<script>
(function () {
    'use strict';

    function boot() {
        const body = document.body;

        /* =========================================================
           1. MOBILE SIDEBAR DRAWER — delegate to sidebar if present
           ========================================================= */
        const sidebar  = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        const openDrawer = () => {
            if (window.SidebarDrawer?.open) return window.SidebarDrawer.open();
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('-translate-x-0');
            backdrop?.classList.remove('hidden');
            body.classList.add('overflow-hidden');
        };

        const closeDrawer = () => {
            if (window.SidebarDrawer?.close) return window.SidebarDrawer.close();
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('-translate-x-0');
            backdrop?.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        };

        const isDrawerOpen = () =>
            window.SidebarDrawer?.isOpen ? window.SidebarDrawer.isOpen()
            : !!sidebar?.classList.contains('-translate-x-0');

        document.querySelectorAll('[data-drawer-toggle="sidebar"]').forEach(el => {
            el.addEventListener('click', () => isDrawerOpen() ? closeDrawer() : openDrawer());
        });

        backdrop?.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isDrawerOpen()) closeDrawer();
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeDrawer();
        });

        /* =========================================================
           2. GENERIC DROPDOWN SYSTEM
           ========================================================= */
        const dropdowns = new Map();

        document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
            const id   = btn.getAttribute('data-dropdown-toggle');
            const menu = document.getElementById(id);
            if (!menu) return;

            menu.classList.add('tb-menu-fixed');

            const position = () => {
                const rect      = btn.getBoundingClientRect();
                const menuRect  = menu.getBoundingClientRect();
                const placement = btn.getAttribute('data-dropdown-placement') || 'bottom-end';

                const top = rect.bottom + 10;
                let left;

                if (placement.endsWith('end'))         left = rect.right - menuRect.width;
                else if (placement.endsWith('start'))  left = rect.left;
                else left = rect.left + (rect.width / 2) - (menuRect.width / 2);

                const maxLeft = window.innerWidth - menuRect.width - 8;
                left = Math.max(8, Math.min(left, maxLeft));

                menu.style.top  = top + 'px';
                menu.style.left = left + 'px';
            };

            dropdowns.set(btn, { menu, position });

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !menu.classList.contains('hidden');

                dropdowns.forEach(({ menu: m, btn: b }) => {
                    if (m !== menu) { m.classList.add('hidden'); b?.setAttribute('aria-expanded', 'false'); }
                });

                if (!isOpen) {
                    menu.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                    position();
                    menu.style.animation = 'none';
                    void menu.offsetWidth;
                    menu.style.animation = '';
                } else {
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-dropdown-toggle]')) return;
            if (e.target.closest('[id$="-menu"]')) return;
            dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));
        });

        const repositionAll = () => {
            dropdowns.forEach(({ menu, position }) => {
                if (!menu.classList.contains('hidden')) position();
            });
        };
        window.addEventListener('resize', repositionAll);
        window.addEventListener('scroll', repositionAll, true);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));
        });

        /* =========================================================
           3. INLINE TOPBAR SEARCH
           ========================================================= */
        (function initTopbarSearch() {
            const wrap     = document.getElementById('topbar-search-wrap');
            const trigger  = document.getElementById('topbar-search-trigger');
            const triggerM = document.getElementById('topbar-search-trigger-mobile');
            const panel    = document.getElementById('topbar-search-panel');
            const input    = document.getElementById('topbar-search-input');
            const results  = document.getElementById('topbar-search-results');

            if (!wrap || !trigger || !panel || !input || !results) return;

            const items = window.SIDEBAR_PALETTE_ITEMS || [];
            let flatList    = [];
            let activeIndex = -1;

            const ICONS = {
                home:        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5"/>',
                users:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M13 7a4 4 0 11-8 0 4 4 0 018 0zM23 20v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
                'user-plus': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M13 7a4 4 0 11-8 0 4 4 0 018 0zM19 8v6M22 11h-6"/>',
                shield:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                key:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>',
                heart:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>',
                globe:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2a10 10 0 100 20 10 10 0 000-20zM2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
                map:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1 6l7-3 8 3 7-3v15l-7 3-8-3-7 3V6zM8 3v15M16 6v15"/>',
                building:    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/>',
                briefcase:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                'git-branch':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3v12M18 9a3 3 0 100-6 3 3 0 000 6zM6 21a3 3 0 100-6 3 3 0 000 6zM18 9a9 9 0 01-9 9"/>',
                palette:     '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                user:        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>',
                cog:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1.08-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1.08 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001.08 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1.08z"/>',
                'file-text': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
                search:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>',
                'arrow-right':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6"/>',
            };

            const svg = (name, cls = 'w-4 h-4') =>
                `<svg class="${cls}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${ICONS[name] || ICONS['file-text']}</svg>`;

            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const render = (query = '') => {
                const q = query.trim().toLowerCase();
                const filtered = q ? items.filter(i => i.label.toLowerCase().includes(q)) : items.slice();

                flatList    = filtered;
                activeIndex = filtered.length ? 0 : -1;

                if (!filtered.length) {
                    results.innerHTML = `
                        <div class="tb-search-empty">
                            ${svg('search', 'w-5 h-5 opacity-50')}
                            <p class="mt-2">No results for "<span class="font-medium">${escapeHtml(query)}</span>"</p>
                        </div>`;
                    return;
                }

                const grouped = {};
                filtered.forEach(item => {
                    const g = item.group || 'Other';
                    (grouped[g] = grouped[g] || []).push(item);
                });

                let html = '';
                let idx  = 0;

                Object.keys(grouped).forEach(groupName => {
                    html += `<div class="tb-search-group-label">${escapeHtml(groupName)}</div>`;
                    grouped[groupName].forEach(item => {
                        const isActive = idx === activeIndex ? 'is-active' : '';
                        html += `
                            <a href="${escapeHtml(item.url || '#')}"
                               class="tb-search-item ${isActive}"
                               data-index="${idx}"
                               data-url="${escapeHtml(item.url || '#')}"
                               data-action="${escapeHtml(item.action || '')}">
                                <span class="tb-search-item-icon">${svg(item.icon || 'file-text', 'w-4 h-4')}</span>
                                <span class="tb-search-item-label">${escapeHtml(item.label)}</span>
                                <span class="tb-search-item-arrow">${svg('arrow-right', 'w-3.5 h-3.5')}</span>
                            </a>`;
                        idx++;
                    });
                });

                results.innerHTML = html;

                results.querySelectorAll('.tb-search-item').forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        activeIndex = parseInt(el.dataset.index, 10);
                        highlight();
                    });
                    el.addEventListener('click', (e) => {
                        const action = el.dataset.action;
                        const url    = el.dataset.url;
                        if (action) {
                            e.preventDefault();
                            handleAction(action);
                            closePanel();
                        } else if (!url || url === '#') {
                            e.preventDefault();
                        }
                    });
                });

                scrollActiveIntoView();
            };

            const highlight = () => {
                results.querySelectorAll('.tb-search-item').forEach(el => {
                    el.classList.toggle('is-active', parseInt(el.dataset.index, 10) === activeIndex);
                });
                scrollActiveIntoView();
            };

            const scrollActiveIntoView = () => {
                results.querySelector('.tb-search-item.is-active')?.scrollIntoView({ block: 'nearest' });
            };

            const handleAction = (action) => {
                if (action === 'theme-drawer') {
                    const t = document.getElementById('open-theme-drawer')
                        || document.getElementById('open-theme-drawer-topbar');
                    t?.click();
                }
            };

            const openPanel = () => {
                panel.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                triggerM?.setAttribute('aria-expanded', 'true');
                render(input.value);
                requestAnimationFrame(() => input.focus());
            };

            const closePanel = () => {
                panel.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                triggerM?.setAttribute('aria-expanded', 'false');
                input.value = '';
                activeIndex = -1;
            };

            const isOpen = () => !panel.classList.contains('hidden');

            trigger.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? closePanel() : openPanel(); });
            triggerM?.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? closePanel() : openPanel(); });

            input.addEventListener('input', (e) => render(e.target.value));

            input.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault(); if (!flatList.length) return;
                    activeIndex = (activeIndex + 1) % flatList.length; highlight();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault(); if (!flatList.length) return;
                    activeIndex = (activeIndex - 1 + flatList.length) % flatList.length; highlight();
                } else if (e.key === 'Enter') {
                    e.preventDefault(); if (activeIndex < 0) return;
                    const item = flatList[activeIndex];
                    if (item.action) { handleAction(item.action); closePanel(); }
                    else if (item.url && item.url !== '#') window.location.href = item.url;
                } else if (e.key === 'Escape') {
                    e.preventDefault(); closePanel(); trigger.focus();
                }
            });

            document.addEventListener('click', (e) => {
                if (!isOpen()) return;
                if (!wrap.contains(e.target)) closePanel();
            });

            window.TopbarSearch = { open: openPanel, close: closePanel, isOpen, render };
        })();

        /* =========================================================
           4. THEME DRAWER (delegate to sidebar)
           ========================================================= */
        document.getElementById('open-theme-drawer-topbar')?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));

            const sidebarTrigger = document.getElementById('open-theme-drawer');
            if (sidebarTrigger) { sidebarTrigger.click(); return; }

            const drawer = document.getElementById('theme-drawer');
            const panel  = document.getElementById('theme-panel');
            if (drawer && panel) {
                drawer.classList.remove('hidden');
                requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
                body.classList.add('overflow-hidden');
            }
        });

        /* =========================================================
           5. THEME TOGGLE (dark mode) — syncs with sidebar drawer
           ========================================================= */
        (function initThemeToggle() {
            const toggle    = document.getElementById('theme-toggle');
            const darkIcon  = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!toggle) return;

            const TKEY = 'sb.theme';
            const root = document.documentElement;

            const readState = () => {
                try { return JSON.parse(localStorage.getItem(TKEY) || '{}'); }
                catch (_) { return {}; }
            };
            const writeState = (s) => {
                try { localStorage.setItem(TKEY, JSON.stringify(s)); } catch (_) {}
            };

            const isDark = () => root.classList.contains('dark');

            const syncIcons = () => {
                const dark = isDark();
                darkIcon?.classList.toggle('hidden', !dark);
                lightIcon?.classList.toggle('hidden', dark);
            };

            syncIcons();

            toggle.addEventListener('click', () => {
                const nextDark = !isDark();
                root.classList.remove('no-theme-transition');
                root.classList.toggle('dark', nextDark);

                const state = readState();
                state.mode = nextDark ? 'dark' : 'light';
                writeState(state);

                syncIcons();

                // Notify the sidebar theme drawer + inline search
                document.dispatchEvent(new CustomEvent('theme:changed', {
                    detail: { mode: state.mode }
                }));
            });

            // React to sidebar drawer's theme mode changes
            document.addEventListener('theme:state', (e) => {
                const nextDark = e.detail?.mode === 'dark';
                if (nextDark !== isDark()) {
                    root.classList.toggle('dark', nextDark);
                    syncIcons();
                }
            });
        })();

        /* =========================================================
           6. COMPACT MODE
           ========================================================= */
        const COMPACT_KEY = 'sb.compact';
        const compactBtn  = document.getElementById('compact-mode-toggle');
        const compactInd  = document.getElementById('compact-mode-indicator');

        const applyCompact = (on) => {
            body.classList.toggle('compact-mode', on);
            body.dataset.density = on ? 'compact' : 'comfortable';
            if (compactInd) {
                compactInd.textContent = on ? 'On' : 'Off';
                compactInd.classList.toggle('bg-blue-50', on);
                compactInd.classList.toggle('text-blue-700', on);
                compactInd.classList.toggle('dark:bg-blue-500/10', on);
                compactInd.classList.toggle('dark:text-blue-400', on);
            }
        };

        let compactOn = localStorage.getItem(COMPACT_KEY) === 'true';
        applyCompact(compactOn);

        compactBtn?.addEventListener('click', () => {
            compactOn = !compactOn;
            localStorage.setItem(COMPACT_KEY, String(compactOn));
            applyCompact(compactOn);
        });

        /* =========================================================
           7. LANGUAGE SELECTOR
           ========================================================= */
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.dataset.lang;
                document.querySelectorAll('.lang-btn').forEach(b =>
                    b.classList.toggle('is-active', b === btn));
                localStorage.setItem('sb.lang', lang);
                document.documentElement.setAttribute('lang', lang);
                document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            });
        });

        const savedLang = localStorage.getItem('sb.lang');
        if (savedLang) {
            document.querySelector(`.lang-btn[data-lang="${savedLang}"]`)?.classList.add('is-active');
            document.documentElement.setAttribute('lang', savedLang);
            document.documentElement.setAttribute('dir', savedLang === 'ar' ? 'rtl' : 'ltr');
        }

        /* =========================================================
           8. PREFERENCES
           ========================================================= */
        const bindPref = (id, key, defaultVal = true) => {
            const el = document.getElementById(id);
            if (!el) return;
            const stored = localStorage.getItem(key);
            el.checked = stored === null ? defaultVal : stored === 'true';
            el.addEventListener('change', () => {
                localStorage.setItem(key, String(el.checked));
                body.dispatchEvent(new CustomEvent('pref:change', {
                    detail: { key, value: el.checked }
                }));
            });
        };

        bindPref('pref-notifications', 'sb.pref.notifications', true);
        bindPref('pref-sounds',        'sb.pref.sounds',        false);

        /* =========================================================
           9. NOTIFICATIONS
           ========================================================= */
        (function initNotifications() {
            const cfg = window.NOTIFICATION_ENDPOINTS;
            if (!cfg) return;

            const list       = document.getElementById('notifications-list');
            const badge      = document.getElementById('notifications-badge');
            const badgeCount = document.getElementById('notifications-badge-count');
            const menuCount  = document.getElementById('notifications-menu-count');
            const markAllBtn = document.getElementById('notifications-mark-all');
            const trigger    = document.getElementById('notifications-button');
            if (!list || !trigger) return;

            let hasLoaded = false;
            let isLoading = false;

            const iconMap = {
                success: { bg: 'bg-emerald-50 dark:bg-emerald-500/10', text: 'text-emerald-600 dark:text-emerald-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' },
                warning: { bg: 'bg-amber-50 dark:bg-amber-500/10',     text: 'text-amber-600 dark:text-amber-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0L3.16 16.25A2 2 0 005 19z"/>' },
                error:   { bg: 'bg-red-50 dark:bg-red-500/10',         text: 'text-red-600 dark:text-red-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' },
                info:    { bg: 'bg-blue-50 dark:bg-blue-500/10',       text: 'text-blue-600 dark:text-blue-400',
                           outline: false, svg: '<path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd" clip-rule="evenodd"/>' },
                action:  { bg: 'bg-violet-50 dark:bg-violet-500/10',   text: 'text-violet-600 dark:text-violet-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>' },
                general: { bg: 'bg-gray-50 dark:bg-gray-700/50',       text: 'text-gray-600 dark:text-gray-400',
                           outline: false, svg: '<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>' },
            };

            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const render = (items) => {
                if (!items.length) {
                    list.innerHTML = `
                        <li class="px-4 py-10 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full
                                        bg-gray-100 dark:bg-gray-700/50 mb-3 mx-auto">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('All caught up') }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ __('No new notifications') }}</p>
                        </li>`;
                    return;
                }

                list.innerHTML = items.map(n => {
                    const c = iconMap[n.category] || iconMap.general;
                    const icon = c.outline
                        ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">${c.svg}</svg>`
                        : `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">${c.svg}</svg>`;

                    const groupBadge = (n.group_count > 1)
                        ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full
                                        bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300
                                        ml-1.5 align-middle">×${n.group_count}</span>`
                        : '';

                    const unreadClass = !n.is_read ? 'bg-blue-50/40 dark:bg-blue-500/5' : '';

                    const unreadDot = !n.is_read
                        ? `<span class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-blue-500"></span>`
                        : '';

                    return `
                    <li>
                        <div class="tb-notification-item group relative ${unreadClass}"
                             data-notification-id="${n.id}"
                             data-action-url="${escapeHtml(n.action_url || '')}"
                             data-is-read="${n.is_read ? '1' : '0'}">
                            ${unreadDot}
                            <span class="tb-notification-icon ${c.bg} ${c.text}">
                                ${icon}
                            </span>
                            <div class="flex-1 min-w-0 cursor-pointer" data-open>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                    ${escapeHtml(n.title)}${groupBadge}
                                </p>
                                ${n.message ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">${escapeHtml(n.message)}</p>` : ''}
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                                    ${escapeHtml(n.time_ago || '')}
                                </p>
                            </div>
                            <button type="button"
                                    data-dismiss
                                    aria-label="{{ __('Dismiss') }}"
                                    class="flex-shrink-0 self-start p-1 rounded text-gray-300
                                           hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </li>`;
                }).join('');
            };

            const updateCounts = (unreadCount) => {
                if (badge && badgeCount) {
                    if (unreadCount > 0) {
                        badgeCount.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    } else {
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                }

                if (menuCount) {
                    if (unreadCount > 0) {
                        menuCount.textContent = `${unreadCount} {{ __('new') }}`;
                        menuCount.classList.remove('hidden');
                    } else {
                        menuCount.classList.add('hidden');
                    }
                }

                if (markAllBtn) {
                    markAllBtn.classList.toggle('opacity-0', unreadCount === 0);
                    markAllBtn.classList.toggle('pointer-events-none', unreadCount === 0);
                }
            };

            const fetchList = async () => {
                if (isLoading) return;
                isLoading = true;
                try {
                    const res = await fetch(`${cfg.index}?per_page=10`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const json = await res.json();
                    render(json.data || []);
                    updateCounts(json.meta?.unread_count ?? 0);
                    hasLoaded = true;
                } catch (e) {
                    console.warn('[notifications] fetch failed', e);
                    list.innerHTML = `
                        <li class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Could not load notifications.') }}
                        </li>`;
                } finally {
                    isLoading = false;
                }
            };

            const fetchCount = async () => {
                try {
                    const res = await fetch(cfg.unread, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) return;
                    const json = await res.json();
                    updateCounts(json.count ?? 0);
                } catch (_) {}
            };

            const markRead = async (id) => {
                try {
                    await fetch(cfg.markOne.replace('__ID__', id), {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                } catch (_) {}
            };

            const markAllRead = async () => {
                try {
                    await fetch(cfg.markAll, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                    await fetchList();
                } catch (_) {}
            };

            const dismiss = async (id) => {
                try {
                    await fetch(cfg.dismissOne.replace('__ID__', id), {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                } catch (_) {}
            };

            list.addEventListener('click', async (e) => {
                const row = e.target.closest('[data-notification-id]');
                if (!row) return;
                const id = row.dataset.notificationId;

                if (e.target.closest('[data-dismiss]')) {
                    e.preventDefault(); e.stopPropagation();
                    row.closest('li')?.remove();
                    await dismiss(id);
                    await fetchCount();
                    return;
                }

                if (e.target.closest('[data-open]')) {
                    if (row.dataset.isRead !== '1') {
                        await markRead(id);
                        row.classList.remove('bg-blue-50/40', 'dark:bg-blue-500/5');
                        row.querySelector('.bg-blue-500')?.remove();
                        row.dataset.isRead = '1';
                        await fetchCount();
                    }
                    const url = row.dataset.actionUrl;
                    if (url) window.location.href = url;
                }
            });

            markAllBtn?.addEventListener('click', (e) => {
                e.preventDefault(); e.stopPropagation();
                markAllRead();
            });

            trigger.addEventListener('click', () => {
                setTimeout(() => { if (!hasLoaded) fetchList(); }, 0);
            });

            fetchCount();
            setInterval(() => {
                if (document.visibilityState === 'visible') fetchCount();
            }, 60_000);
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') fetchCount();
            });

            window.refreshNotifications = () => { fetchList(); fetchCount(); };
        })();

        /* =========================================================
           10. GLOBAL ⌘K / Ctrl+K — topbar search (single owner)
           ========================================================= */
        document.addEventListener('keydown', (e) => {
            const isMac = navigator.platform.toLowerCase().includes('mac');
            const cmdK  = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'k';
            if (!cmdK) return;

            // Ignore if user is typing somewhere that isn't our search
            const tag = (e.target.tagName || '').toLowerCase();
            if (['input', 'textarea', 'select'].includes(tag) &&
                !e.target.closest('#topbar-search-panel, #sidebar-search-panel, #command-palette')) {
                return;
            }

            e.preventDefault();

            // If the sidebar's inline search is open, close it first.
            if (window.SidebarSearch?.isOpen?.()) window.SidebarSearch.close();

            const api = window.TopbarSearch;
            if (!api) return;
            api.isOpen() ? api.close() : api.open();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>

<style>
    .tb-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 0.75rem;
        color: #6b7280;
        background: transparent;
        transition: background-color 0.2s ease,
                    color 0.2s ease,
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
    }
    .tb-icon-btn:hover { background-color: rgba(243, 244, 246, 0.8); color: #111827; }
    .tb-icon-btn:active { transform: scale(0.95); }
    .tb-icon-btn:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.35); }
    .dark .tb-icon-btn { color: #9ca3af; }
    .dark .tb-icon-btn:hover { background-color: rgba(31, 41, 55, 0.7); color: #f9fafb; }

    .tb-search {
        display: none;
        align-items: center;
        gap: 0.625rem;
        padding: 0.5rem 0.75rem;
        min-width: 240px;
        font-size: 0.8125rem;
        border-radius: 0.75rem;
        background: rgba(249, 250, 251, 0.9);
        border: 1px solid rgba(229, 231, 235, 0.9);
        color: #6b7280;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @media (min-width: 768px) { .tb-search { display: inline-flex; } }
    .tb-search:hover {
        background: #ffffff;
        border-color: rgba(191, 219, 254, 1);
        color: #374151;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08),
                    0 4px 12px -6px rgba(59, 130, 246, 0.15);
    }
    .tb-search:focus-visible {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }
    .dark .tb-search { background: rgba(31, 41, 55, 0.6); border-color: rgba(55, 65, 81, 0.8); color: #9ca3af; }
    .dark .tb-search:hover { background: rgba(31, 41, 55, 0.9); border-color: rgba(59, 130, 246, 0.4); color: #e5e7eb; }

    .tb-kbd {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 6px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        font-family: inherit;
        letter-spacing: 0.02em;
    }
    .dark .tb-kbd { background: rgba(17, 24, 39, 0.9); border-color: #374151; color: #9ca3af; }

    .tb-search-panel {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        width: 22rem;
        max-width: calc(100vw - 2rem);
        z-index: 60;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 40px -12px rgba(0,0,0,0.18);
        overflow: hidden;
        animation: tb-search-in 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top right;
    }
    .dark .tb-search-panel {
        background: rgba(17, 24, 39, 0.98);
        box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 4px 6px -1px rgba(0,0,0,0.3), 0 20px 40px -12px rgba(0,0,0,0.5);
    }
    @keyframes tb-search-in {
        from { opacity: 0; transform: translateY(-6px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0)    scale(1); }
    }

    .tb-search-panel-header {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.625rem 0.75rem;
        border-bottom: 1px solid rgba(243, 244, 246, 1);
    }
    .dark .tb-search-panel-header { border-bottom-color: rgba(55, 65, 81, 0.6); }

    .tb-search-panel-input {
        flex: 1; border: 0; background: transparent;
        font-size: 0.8125rem; color: #111827; outline: none; font-family: inherit;
    }
    .dark .tb-search-panel-input { color: #f9fafb; }
    .tb-search-panel-input::placeholder { color: #9ca3af; }

    .tb-search-panel-kbd, .tb-mini-kbd {
        font-size: 10px; font-weight: 600;
        padding: 2px 6px; border-radius: 6px;
        background: #f3f4f6; border: 1px solid #e5e7eb; color: #6b7280;
        font-family: inherit;
    }
    .dark .tb-search-panel-kbd, .dark .tb-mini-kbd { background: #1f2937; border-color: #374151; color: #9ca3af; }

    .tb-search-panel-body { max-height: 20rem; overflow-y: auto; padding: 0.25rem; scrollbar-width: thin; }
    .tb-search-panel-body::-webkit-scrollbar { width: 6px; }
    .tb-search-panel-body::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.4); border-radius: 9999px; }

    .tb-search-group-label {
        padding: 0.5rem 0.625rem 0.25rem;
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #9ca3af;
    }
    .dark .tb-search-group-label { color: #6b7280; }

    .tb-search-item {
        display: flex; align-items: center; gap: 0.625rem;
        padding: 0.5rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        color: #374151;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.12s ease, color 0.12s ease;
    }
    .tb-search-item:hover, .tb-search-item.is-active { background: #eff6ff; color: #1d4ed8; }
    .dark .tb-search-item { color: #d1d5db; }
    .dark .tb-search-item:hover, .dark .tb-search-item.is-active { background: rgba(59, 130, 246, 0.12); color: #93c5fd; }

    .tb-search-item-icon { display: inline-flex; color: #6b7280; flex-shrink: 0; }
    .tb-search-item.is-active .tb-search-item-icon { color: #3b82f6; }
    .tb-search-item-label { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tb-search-item-arrow { opacity: 0; transform: translateX(-2px); transition: all 0.15s ease; }
    .tb-search-item.is-active .tb-search-item-arrow { opacity: 1; transform: translateX(0); }

    .tb-search-panel-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.5rem 0.75rem;
        border-top: 1px solid rgba(243, 244, 246, 1);
        font-size: 0.6875rem; color: #9ca3af;
    }
    .dark .tb-search-panel-footer { border-top-color: rgba(55, 65, 81, 0.6); }

    .tb-search-empty { display: flex; flex-direction: column; align-items: center; padding: 1.75rem 1rem; text-align: center; color: #9ca3af; font-size: 0.8125rem; }

    .tb-menu {
        z-index: 50;
        margin-top: 0.5rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 40px -12px rgba(0,0,0,0.12);
        overflow: hidden;
    }
    .dark .tb-menu {
        background: rgba(17, 24, 39, 0.95);
        box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 4px 6px -1px rgba(0,0,0,0.3), 0 20px 40px -12px rgba(0,0,0,0.5);
    }
    .tb-menu.tb-menu-fixed { position: fixed !important; }
    .tb-menu:not(.hidden) {
        animation: tb-menu-in 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top right;
        will-change: opacity, transform;
    }
    @keyframes tb-menu-in {
        from { opacity: 0; transform: translateY(-6px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0)    scale(1); }
    }

    .tb-menu-header { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(243, 244, 246, 1); }
    .dark .tb-menu-header { border-bottom-color: rgba(55, 65, 81, 0.6); }
    .tb-menu-title { font-size: 0.8125rem; font-weight: 600; color: #111827; letter-spacing: -0.01em; }
    .dark .tb-menu-title { color: #ffffff; }

    .tb-menu-section {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #9ca3af; padding: 0 0.25rem; margin-bottom: 0.5rem;
    }
    .dark .tb-menu-section { color: #6b7280; }

    .tb-menu-list { padding: 0.375rem 0; }
    .tb-menu-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.8125rem; font-weight: 500;
        color: #374151; text-align: left;
        transition: background-color 0.15s ease, color 0.15s ease;
        cursor: pointer;
    }
    .tb-menu-item:hover { background-color: rgba(249, 250, 251, 1); color: #111827; }
    .tb-menu-item:active { background-color: rgba(243, 244, 246, 1); }
    .dark .tb-menu-item { color: #d1d5db; }
    .dark .tb-menu-item:hover { background-color: rgba(31, 41, 55, 0.6); color: #ffffff; }

    .tb-menu-icon {
        flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center;
        width: 1.75rem; height: 1.75rem; border-radius: 0.5rem;
        transition: transform 0.2s ease;
    }
    .tb-menu-item:hover .tb-menu-icon { transform: scale(1.08); }

    .tb-notification-item { display: flex; gap: 0.75rem; padding: 0.75rem 1rem; transition: background-color 0.15s ease; }
    .tb-notification-item:hover { background-color: rgba(249, 250, 251, 0.8); }
    .dark .tb-notification-item:hover { background-color: rgba(31, 41, 55, 0.5); }
    .tb-notification-icon {
        flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 9999px;
    }

    .tb-scroll::-webkit-scrollbar { width: 6px; }
    .tb-scroll::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.4); border-radius: 9999px; }
    .tb-scroll::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.6); }

    .lang-btn {
        flex: 1; padding: 0.375rem 0;
        font-size: 0.6875rem; font-weight: 600;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        color: #374151; background: transparent;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lang-btn:hover { border-color: #93c5fd; background: rgba(239, 246, 255, 0.6); }
    .lang-btn.is-active {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        box-shadow: 0 2px 6px -2px rgba(59, 130, 246, 0.3);
    }
    .dark .lang-btn { border-color: #374151; color: #d1d5db; }
    .dark .lang-btn:hover { border-color: rgba(59, 130, 246, 0.5); background: rgba(59, 130, 246, 0.08); }
    .dark .lang-btn.is-active {
        border-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
        color: #93c5fd;
    }

    .tb-checkbox {
        width: 1rem; height: 1rem; border-radius: 0.375rem;
        border: 1.5px solid #d1d5db; color: #3b82f6; background: #ffffff;
        cursor: pointer; transition: all 0.15s ease;
    }
    .tb-checkbox:checked {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        border-color: #3b82f6;
    }
    .tb-checkbox:focus { outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); }
    .dark .tb-checkbox { background: #374151; border-color: #4b5563; }

    #sidebar > div {
        overflow-y: auto; overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.35) transparent;
    }
    #sidebar > div::-webkit-scrollbar { width: 6px; }
    #sidebar > div::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.3); border-radius: 9999px; }
    .dark #sidebar > div { scrollbar-color: rgba(75, 85, 99, 0.5) transparent; }

    #sidebar-backdrop { opacity: 0; transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    #sidebar-backdrop:not(.hidden) { opacity: 1; }

    body.compact-mode nav a,
    body.compact-mode nav button { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
    body.compact-mode table td,
    body.compact-mode table th { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }

    @media (prefers-reduced-motion: reduce) {
        .tb-menu, .tb-menu:not(.hidden),
        .tb-search-panel, .tb-icon-btn, .tb-search, #sidebar-backdrop {
            animation: none !important;
            transition: none !important;
        }
    }
</style>