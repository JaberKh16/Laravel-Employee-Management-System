{{-- =========================================================
     SIDEBAR — HTML only
     ========================================================= --}}
@php
    $user = auth()->user();
    $user_role = $user && method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : null;

    $isDashboardActive = request()->routeIs('home');
    $isEmployeeActive  = request()->is('employees*');

    $isSystemActive = request()->routeIs(
        'countries.*', 'states.*', 'cities.*',
        'departments.*', 'branches.*'
    );
    $isAdminActive = request()->routeIs(
        'users.*', 'roles.*', 'permissions.*',
        'social.services.*'
    );
    $isThemeActive = request()->routeIs('settings.theme*');

    $isAdmin = $user && method_exists($user, 'hasRole')
        ? $user->hasRole(['Admin', 'super-admin'])
        : false;

    $isManager = $user && method_exists($user, 'hasAnyRole')
        ? $user->hasAnyRole(['Admin', 'super-admin', 'manager'])
        : $isAdmin;

    $routeIndexes = [
        'countries.index'   => __('Country'),
        'states.index'      => __('State'),
        'cities.index'      => __('City'),
        'departments.index' => __('Department'),
        'branches.index'    => __('Branch'),
    ]
@endphp

{{-- Mobile backdrop --}}
<div id="sidebar-backdrop"
     class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm hidden lg:hidden"
     data-drawer-hide="sidebar"
     aria-hidden="true"></div>

<aside id="sidebar"
       class="fixed top-0 left-0 z-40 h-screen pt-16
              w-64
              bg-white border-r border-gray-200
              transition-[transform,width] duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]
              -translate-x-full lg:translate-x-0
              dark:bg-gray-900 dark:border-gray-800"
       aria-label="{{ __('Sidebar') }}">

    <div class="flex flex-col h-full px-3 pb-4 overflow-y-auto [scrollbar-width:thin]">

        {{-- Brand --}}
        <a href="{{ route('home') }}"
           class="sb-brand group flex items-center gap-3 px-2 py-3 mb-2 rounded-lg
                  hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="sb-brand-icon flex-shrink-0 inline-flex items-center justify-center
                         w-9 h-9 rounded-lg
                         bg-gradient-to-br from-blue-500 to-indigo-600 text-white
                         shadow-sm ring-1 ring-blue-500/20">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                </svg>
            </span>
            <div class="sidebar-label flex flex-col leading-tight min-w-0">
                <span class="text-sm font-semibold text-gray-900 dark:text-white truncate">SB Admin</span>
                <span class="text-[11px] uppercase tracking-wider text-gray-500 dark:text-gray-400">v2 · workspace</span>
            </div>
        </a>

        {{-- Search box + inline palette --}}
        <div class="sidebar-label-wrap relative mb-3"
             id="sidebar-search-wrap">

            <button type="button"
                    id="sidebar-search-trigger"
                    class="sb-search2"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    aria-controls="sidebar-search-panel">
                <span class="sb-search2-ring" aria-hidden="true"></span>

                <span class="sb-search2-inner">
                    <span class="sb-search2-icon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"
                                  d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>

                    <span class="sb-search2-text">
                        <span class="sb-search2-label">{{ __('Search…') }}</span>
                        <span class="sb-search2-hint">{{ __('pages, actions') }}</span>
                    </span>

                    <span class="sb-search2-kbd">
                        <kbd>⌘</kbd><kbd>K</kbd>
                    </span>
                </span>
            </button>

            <div id="sidebar-search-panel"
                 class="sb-panel hidden"
                 role="listbox"
                 aria-label="{{ __('Available pages') }}">

                <div class="sb-panel-header">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <input type="text"
                           id="sidebar-search-input"
                           autocomplete="off"
                           placeholder="{{ __('Type to filter…') }}"
                           class="sb-panel-input">
                    <kbd class="sb-panel-kbd">ESC</kbd>
                </div>

                <div class="sb-panel-body" id="sidebar-search-results"></div>

                <div class="sb-panel-footer">
                    <span><kbd class="sb-mini-kbd">↑</kbd><kbd class="sb-mini-kbd">↓</kbd> {{ __('navigate') }}</span>
                    <span><kbd class="sb-mini-kbd">↵</kbd> {{ __('open') }}</span>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1">
            <ul class="space-y-1 text-sm font-medium">

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('home') }}"
                       title="{{ __('Dashboard') }}"
                       class="sb-nav-item group flex items-center gap-3 px-2.5 py-2 rounded-lg transition-colors
                              {{ $isDashboardActive
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm10 0a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zM3 14a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm10-4a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        <span class="sidebar-label truncate">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- Employees --}}
                <li>
                    <a href="/employees"
                       title="{{ __('Employee Management') }}"
                       class="sb-nav-item group flex items-center gap-3 px-2.5 py-2 rounded-lg transition-colors
                              {{ $isEmployeeActive
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                        <span class="sidebar-label truncate">{{ __('Employee Management') }}</span>
                    </a>
                </li>

                {{-- Administration --}}
                @if ($isManager)
                    <li>
                        <button type="button"
                                data-collapse-toggle="dropdown-admin"
                                aria-controls="dropdown-admin"
                                title="{{ __('Administration') }}"
                                class="sb-nav-item group flex items-center justify-between gap-2 w-full
                                       px-2.5 py-2 rounded-lg transition-colors
                                       {{ $isAdminActive
                                             ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                             : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            <span class="flex items-center gap-3 min-w-0">
                                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="sidebar-label truncate">{{ __('Administration') }}</span>
                            </span>
                            <svg class="sidebar-label flex-shrink-0 w-4 h-4 transition-transform duration-200
                                        group-aria-expanded:rotate-180"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <ul id="dropdown-admin"
                            class="{{ $isAdminActive ? '' : 'hidden' }} sidebar-dropdown mt-1 space-y-0.5
                                   pl-4 border-l border-gray-200 dark:border-gray-700 ml-4">
                            @foreach (array_filter([
                                Route::has('users.index')       ? 'users.index'       : null => __('Users'),
                                Route::has('roles.index')       ? 'roles.index'       : null => __('Roles'),
                                Route::has('permissions.index') ? 'permissions.index' : null => __('Permissions'),
                                Route::has('social.services.index') ? 'social.services.index' : null => __('Social Services'),
                                Route::has('audit.index')       ? 'audit.index'       : null => __('Audit Log'),
                                Route::has('notifications.index')       ? 'notifications.index'       : null => __('Notifacation Log'),
                            ], fn ($k) => $k !== null, ARRAY_FILTER_USE_KEY) as $routeName => $label)
                                @continue(! Route::has($routeName))
                                @php $active = request()->routeIs($routeName); @endphp
                                <li>
                                    <a href="{{ route($routeName) }}"
                                       class="sidebar-label flex items-center gap-2 pl-3 pr-3 py-1.5
                                              text-[13px] rounded-md transition-colors
                                              {{ $active
                                                    ? 'text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-500/10'
                                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-100 dark:hover:bg-gray-800' }}">
                                        <span class="w-1 h-1 rounded-full bg-current opacity-50"></span>
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                {{-- System --}}
                <li>
                    <button type="button"
                            data-collapse-toggle="dropdown-system"
                            aria-controls="dropdown-system"
                            title="{{ __('System') }}"
                            class="sb-nav-item group flex items-center justify-between gap-2 w-full
                                   px-2.5 py-2 rounded-lg transition-colors
                                   {{ $isSystemActive
                                         ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                         : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="sidebar-label truncate">{{ __('System') }}</span>
                        </span>
                        <svg class="sidebar-label flex-shrink-0 w-4 h-4 transition-transform duration-200
                                    group-aria-expanded:rotate-180"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul id="dropdown-system"
                        class="{{ $isSystemActive ? '' : 'hidden' }} sidebar-dropdown mt-1 space-y-0.5
                               pl-4 border-l border-gray-200 dark:border-gray-700 ml-4">
                        @foreach ($routeIndexes as $routeName => $label)
                            @continue(! Route::has($routeName))
                            @php $active = request()->routeIs($routeName); @endphp
                            <li>
                                <a href="{{ route($routeName) }}"
                                   class="sidebar-label flex items-center gap-2 pl-3 pr-3 py-1.5
                                          text-[13px] rounded-md transition-colors
                                          {{ $active
                                                ? 'text-blue-700 bg-blue-50 dark:text-blue-400 dark:bg-blue-500/10'
                                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-100 dark:hover:bg-gray-800' }}">
                                    <span class="w-1 h-1 rounded-full bg-current opacity-50"></span>
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                {{-- Theme Options --}}
                <li>
                    <button type="button"
                            id="open-theme-drawer"
                            title="{{ __('Theme Options') }}"
                            class="sb-nav-item group flex items-center gap-3 w-full px-2.5 py-2 rounded-lg
                                   transition-colors
                                   {{ $isThemeActive
                                         ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                         : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        <span class="sidebar-label truncate">{{ __('Theme Options') }}</span>
                    </button>
                </li>
            </ul>
        </nav>

        {{-- Footer --}}
        <div class="pt-3 mt-3 border-t border-gray-200 dark:border-gray-800">
            @auth
                <div class="sb-user flex items-center gap-2 px-1">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gradient-to-br from-blue-500 to-indigo-600
                                 text-white text-xs font-semibold
                                 flex items-center justify-center">
                        {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                    </span>
                    <div class="sidebar-label flex-1 min-w-0">
                        <div class="text-xs font-medium text-gray-900 dark:text-white truncate">
                            {{ $user->username }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                            {{ $user->email ?? '' }}
                        </div>
                    </div>
                </div>
            @endauth

            {{-- <button type="button"
                    id="sidebar-collapse-toggle"
                    aria-label="{{ __('Collapse sidebar') }}"
                    class="sb-collapse-btn hidden lg:flex items-center gap-2 w-full mt-2 px-2.5 py-2
                           text-xs font-medium rounded-lg
                           text-gray-500 hover:text-gray-900 hover:bg-gray-100
                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800
                           transition-colors">
                <svg id="sidebar-collapse-icon"
                     class="flex-shrink-0 w-4 h-4 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="sidebar-label">{{ __('Collapse') }}</span>
            </button> --}}

            <button type="button"
                    id="sidebar-collapse-toggle"
                    aria-label="{{ __('Collapse sidebar') }}"
                    class="group sb-collapse-btn hidden lg:flex items-center gap-2 w-full mt-2 px-2.5 py-2
                        text-xs font-medium rounded-lg
                        text-gray-500 hover:text-gray-900 hover:bg-gray-100
                        dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800
                        transition-all duration-300 ease-in-out
                        hover:shadow-sm
                        active:scale-[0.98]
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 focus-visible:ring-offset-1
                        dark:focus-visible:ring-offset-gray-900
                        cursor-pointer select-none">
                <svg id="sidebar-collapse-icon"
                    class="flex-shrink-0 w-4 h-4 transition-transform duration-300 ease-in-out
                            group-hover:-translate-x-0.5"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="sidebar-label transition-all duration-300 ease-in-out
                            group-hover:tracking-wide">
                    {{ __('Collapse') }}
                </span>
            </button>
        </div>
    </div>
</aside>

{{-- ========================================================= --}}
{{-- ⌘K COMMAND PALETTE (legacy modal)                           --}}
{{-- ========================================================= --}}
<div id="command-palette"
     class="fixed inset-0 z-[60] hidden"
     aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" data-palette-close></div>
    <div class="relative mx-auto mt-24 max-w-xl mx-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl ring-1 ring-black/5
                    dark:ring-white/10 overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input id="command-input" type="text" autocomplete="off"
                       placeholder="{{ __('Search pages, users, actions…') }}"
                       class="flex-1 bg-transparent border-0 text-sm text-gray-900 dark:text-white
                              placeholder-gray-400 focus:outline-none focus:ring-0">
                <kbd class="text-[10px] font-semibold px-1.5 py-0.5 rounded
                            bg-gray-100 border border-gray-200 text-gray-500
                            dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">ESC</kbd>
            </div>
            <ul id="command-results" class="max-h-80 overflow-y-auto py-2 text-sm"></ul>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- 🎨 THEME OPTIONS DRAWER                                     --}}
{{-- ========================================================= --}}
<div id="theme-drawer" class="fixed inset-0 z-[70] hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" data-theme-close></div>

    <div id="theme-panel"
         class="absolute top-0 right-0 h-full w-full max-w-sm
                bg-white dark:bg-gray-900 shadow-2xl
                translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]
                flex flex-col">

        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Theme Options') }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Personalize your dashboard.') }}</p>
            </div>
            <button type="button" data-theme-close
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100
                           dark:hover:text-gray-200 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-6">
            <section>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('Appearance') }}
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" data-theme-mode="light"
                            class="theme-mode-btn flex flex-col items-center gap-2 p-3 rounded-xl
                                   border border-gray-200 dark:border-gray-800 hover:border-blue-400 transition">
                        <span class="w-full h-12 rounded-lg bg-white border border-gray-200"></span>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Light') }}</span>
                    </button>
                    <button type="button" data-theme-mode="dark"
                            class="theme-mode-btn flex flex-col items-center gap-2 p-3 rounded-xl
                                   border border-gray-200 dark:border-gray-800 hover:border-blue-400 transition">
                        <span class="w-full h-12 rounded-lg bg-gray-900 border border-gray-700"></span>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Dark') }}</span>
                    </button>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('Accent color') }}
                </h3>
                <div id="theme-color-list" class="flex flex-wrap gap-2">
                    @foreach ([
                        'blue' => '#3b82f6', 'indigo' => '#6366f1', 'violet' => '#8b5cf6',
                        'pink' => '#ec4899', 'rose' => '#f43f5e', 'amber' => '#f59e0b',
                        'emerald' => '#10b981', 'teal' => '#14b8a6', 'cyan' => '#06b6d4', 'slate' => '#64748b',
                    ] as $name => $hex)
                        <button type="button"
                                data-theme-color="{{ $name }}"
                                data-theme-hex="{{ $hex }}"
                                class="theme-color-btn w-9 h-9 rounded-full ring-2 ring-transparent
                                       transition-transform hover:scale-110"
                                style="background-color: {{ $hex }}"
                                title="{{ ucfirst($name) }}"></button>
                    @endforeach
                </div>
            </section>

            <section>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('Density') }}
                </h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach (['compact' => __('Compact'), 'comfortable' => __('Comfort'), 'spacious' => __('Spacious')] as $key => $label)
                        <button type="button" data-theme-density="{{ $key }}"
                                class="theme-density-btn px-3 py-2 rounded-lg text-xs font-medium
                                       border border-gray-200 dark:border-gray-800
                                       text-gray-700 dark:text-gray-300 hover:border-blue-400 transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </section>

            <section>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('Layout width') }}
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" data-theme-layout="fluid"
                            class="theme-layout-btn px-3 py-2 rounded-lg text-xs font-medium
                                   border border-gray-200 dark:border-gray-800
                                   text-gray-700 dark:text-gray-300 hover:border-blue-400 transition">
                        {{ __('Fluid') }}
                    </button>
                    <button type="button" data-theme-layout="boxed"
                            class="theme-layout-btn px-3 py-2 rounded-lg text-xs font-medium
                                   border border-gray-200 dark:border-gray-800
                                   text-gray-700 dark:text-gray-300 hover:border-blue-400 transition">
                        {{ __('Boxed') }}
                    </button>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('Sidebar style') }}
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" data-theme-sidebar="default"
                            class="theme-sidebar-btn px-3 py-2 rounded-lg text-xs font-medium
                                   border border-gray-200 dark:border-gray-800
                                   text-gray-700 dark:text-gray-300 hover:border-blue-400 transition">
                        {{ __('Default') }}
                    </button>
                    <button type="button" data-theme-sidebar="gradient"
                            class="theme-sidebar-btn px-3 py-2 rounded-lg text-xs font-medium
                                   border border-gray-200 dark:border-gray-800
                                   text-gray-700 dark:text-gray-300 hover:border-blue-400 transition">
                        {{ __('Gradient') }}
                    </button>
                </div>
            </section>
        </div>

        <div class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-800">
            <button type="button" id="theme-reset"
                    class="px-3 py-2 rounded-lg text-xs font-medium
                           text-gray-600 hover:text-gray-900 hover:bg-gray-100
                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition">
                {{ __('Reset') }}
            </button>
            <button type="button" data-theme-close
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-white
                           bg-blue-600 hover:bg-blue-700 transition">
                {{ __('Done') }}
            </button>
        </div>
    </div>
</div>

{{-- Palette items passed to JS --}}
<script>
    window.SIDEBAR_PALETTE_ITEMS = [
        { label: @json(__('Dashboard')),  url: @json(route('home')),           icon: 'home',     group: @json(__('Main')) },
        { label: @json(__('Employees')),  url: @json('/employees'),            icon: 'users',    group: @json(__('Main')) },

        @if (Route::has('users.index'))
            { label: @json(__('Users')),        url: @json(route('users.index')),        icon: 'user-plus',  group: @json(__('Administration')) },
        @endif
        @if (Route::has('roles.index'))
            { label: @json(__('Roles')),        url: @json(route('roles.index')),        icon: 'shield',     group: @json(__('Administration')) },
        @endif
        @if (Route::has('permissions.index'))
            { label: @json(__('Permissions')),  url: @json(route('permissions.index')),  icon: 'key',        group: @json(__('Administration')) },
        @endif
        @if (Route::has('social.services.index'))
            { label: @json(__('Social Services')), url: @json(route('social.services.index')), icon: 'heart', group: @json(__('Administration')) },
        @endif

        @if (Route::has('countries.index'))
            { label: @json(__('Countries')),    url: @json(route('countries.index')),    icon: 'globe',      group: @json(__('System')) },
        @endif
        @if (Route::has('states.index'))
            { label: @json(__('States')),       url: @json(route('states.index')),       icon: 'map',        group: @json(__('System')) },
        @endif
        @if (Route::has('cities.index'))
            { label: @json(__('Cities')),       url: @json(route('cities.index')),       icon: 'building',   group: @json(__('System')) },
        @endif
        @if (Route::has('departments.index'))
            { label: @json(__('Departments')),  url: @json(route('departments.index')),  icon: 'briefcase',  group: @json(__('System')) },
        @endif
        @if (Route::has('branches.index'))
            { label: @json(__('Branches')),     url: @json(route('branches.index')),     icon: 'git-branch', group: @json(__('System')) },
        @endif

        { label: @json(__('Theme Options')), url: '#theme',   icon: 'palette', group: @json(__('Tools')), action: 'theme-drawer' },
        { label: @json(__('My Profile')),    url: @json(Route::has('users.profile') ? route('users.profile') : '#'), icon: 'user', group: @json(__('Tools')) },
        { label: @json(__('Settings')),      url: '#settings', icon: 'cog', group: @json(__('Tools')), action: 'settings' },
    ];

    /* =========================================================
       SIDEBAR JS — collapse + accordion + inline search + theme
       ========================================================= */
    (function () {
        'use strict';

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
            search:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>',
            'arrow-right':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6"/>',
            'file-text': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
        };

        const svg = (name, cls = 'w-4 h-4') =>
            `<svg class="${cls}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${ICONS[name] || ICONS['file-text']}</svg>`;

        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, m => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;',
                '"': '&quot;', "'": '&#39;'
            }[m]));
        }
        const escapeAttr = escapeHtml;

        function boot() {
            const body    = document.body;
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const toggle      = document.getElementById('sidebar-collapse-toggle');
            const backdrop    = document.getElementById('sidebar-backdrop');
            const mainContent = document.getElementById('main-content');
            const STORAGE_KEY = 'sb.sidebar.collapsed';

            /* =========================================================
            1. SIDEBAR COLLAPSE — bulletproof
            ========================================================= */
            // console.log('[sidebar] collapse init. stored=', localStorage.getItem(STORAGE_KEY));
            if (!mainContent) return;

            const applyCollapsed = (collapsed, skipAnim = false) => {
                if (skipAnim) body.classList.add('no-anim');

                // Use BOTH class on body AND a data-attribute — belt and braces.
                // Some Tailwind preflight resets or third-party CSS may strip classes.
                body.classList.toggle('sidebar-collapsed', collapsed);
                body.setAttribute('data-sidebar-collapsed', collapsed ? 'true' : 'false');

               

                if (skipAnim) {
                    void body.offsetWidth;
                    requestAnimationFrame(() => requestAnimationFrame(() =>
                        body.classList.remove('no-anim')));
                }

                // Auto-close accordion dropdowns when collapsing.
                if (collapsed) {
                    // ✅ Always swap the main-content margin — both directions.
                    mainContent.classList.toggle('lg:ml-64', !collapsed);
                    mainContent.classList.toggle('lg:ml-[6rem]', collapsed);
                    document.querySelectorAll('.sidebar-dropdown').forEach(ul => ul.classList.add('hidden'));
                    document.querySelectorAll('[data-collapse-toggle]').forEach(btn =>
                        btn.setAttribute('aria-expanded', 'false'));
                }

                // console.log('[sidebar] applied collapsed =', collapsed);
            };

            // Restore on load
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                applyCollapsed(true, true);
            }

            // Toggle button
            toggle?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const collapsed = !body.classList.contains('sidebar-collapsed');
                applyCollapsed(collapsed);
                localStorage.setItem(STORAGE_KEY, String(collapsed));
                window.dispatchEvent(new Event('resize'));
                // console.log('[sidebar] toggle clicked, collapsed =', collapsed);
            });

            // Fallback: if `toggle` is null, warn loudly
            if (!toggle) {
                // console.warn('[sidebar] #sidebar-collapse-toggle NOT FOUND. Collapse will not work.');
            }
            toggle?.addEventListener('click', () => {
                const collapsed = !body.classList.contains('sidebar-collapsed');
                applyCollapsed(collapsed);
                localStorage.setItem(STORAGE_KEY, collapsed ? 'true' : 'false');
                window.dispatchEvent(new Event('resize'));
            });

            /* =========================================================
               2. MOBILE DRAWER
               ========================================================= */
            const openDrawer = () => {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('-translate-x-0');
                backdrop?.classList.remove('hidden');
            };
            const closeDrawer = () => {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('-translate-x-0');
                backdrop?.classList.add('hidden');
            };
            const isDrawerOpen = () => sidebar.classList.contains('-translate-x-0');

            // NOTE: The sidebar script already binds the drawer toggle.
            // Do NOT re-bind here — that causes a double-toggle bug.
            // We only expose a fallback if the sidebar script failed to load.

            if (!window.SidebarDrawer) {
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
            }

            window.SidebarDrawer = { open: openDrawer, close: closeDrawer, isOpen: isDrawerOpen };

            /* =========================================================
               3. ACCORDION TOGGLES
               ========================================================= */
            document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
                const target = document.getElementById(btn.getAttribute('data-collapse-toggle'));
                if (!target) return;
                btn.setAttribute('aria-expanded', target.classList.contains('hidden') ? 'false' : 'true');
                btn.addEventListener('click', () => {
                    const opened = !target.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
                });
            });

            /* =========================================================
               4. INLINE SEARCH
               ========================================================= */
            (function initInlineSearch() {
                const wrap    = document.getElementById('sidebar-search-wrap');
                const trigger = document.getElementById('sidebar-search-trigger');
                const panel   = document.getElementById('sidebar-search-panel');
                const input   = document.getElementById('sidebar-search-input');
                const results = document.getElementById('sidebar-search-results');

                if (!trigger || !panel || !input || !results) return;

                const items = window.SIDEBAR_PALETTE_ITEMS || [];
                let activeIndex = -1;
                let flatList    = [];

                const render = (query = '') => {
                    const q = query.trim().toLowerCase();
                    const filtered = q
                        ? items.filter(i => i.label.toLowerCase().includes(q))
                        : items.slice();

                    flatList = filtered;
                    activeIndex = filtered.length ? 0 : -1;

                    if (!filtered.length) {
                        results.innerHTML = `
                            <div class="sb-empty">
                                ${svg('search', 'w-5 h-5')}
                                <p>No results for "<span class="font-medium">${escapeHtml(query)}</span>"</p>
                                <p class="text-[11px] opacity-70 mt-1">Try a different keyword.</p>
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
                        html += `<div class="sb-group-label">${escapeHtml(groupName)}</div>`;
                        grouped[groupName].forEach(item => {
                            const isActive = idx === activeIndex ? 'is-active' : '';
                            html += `
                                <a href="${escapeAttr(item.url || '#')}"
                                   class="sb-item ${isActive}"
                                   data-index="${idx}"
                                   data-url="${escapeAttr(item.url || '#')}"
                                   data-action="${escapeAttr(item.action || '')}">
                                    <span class="sb-item-icon">${svg(item.icon || 'file-text', 'w-4 h-4')}</span>
                                    <span class="sb-item-label">${escapeHtml(item.label)}</span>
                                    <span class="sb-item-arrow">${svg('arrow-right', 'w-3.5 h-3.5')}</span>
                                </a>`;
                            idx++;
                        });
                    });

                    results.innerHTML = html;

                    results.querySelectorAll('.sb-item').forEach(el => {
                        el.addEventListener('mouseenter', () => {
                            activeIndex = parseInt(el.dataset.index, 10);
                            highlight();
                        });
                        el.addEventListener('click', e => {
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
                    results.querySelectorAll('.sb-item').forEach(el => {
                        el.classList.toggle('is-active',
                            parseInt(el.dataset.index, 10) === activeIndex);
                    });
                    scrollActiveIntoView();
                };

                const scrollActiveIntoView = () => {
                    const active = results.querySelector('.sb-item.is-active');
                    active?.scrollIntoView({ block: 'nearest' });
                };

                const handleAction = (action) => {
                    if (action === 'theme-drawer') {
                        document.getElementById('open-theme-drawer')?.click();
                    }
                };

                const openPanel = () => {
                    if (body.classList.contains('sidebar-collapsed')) return;
                    panel.classList.remove('hidden');
                    trigger.setAttribute('aria-expanded', 'true');
                    render(input.value);
                    requestAnimationFrame(() => input.focus());
                    document.dispatchEvent(new CustomEvent('sidebar:opened'));
                };

                const closePanel = () => {
                    panel.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                    input.value = '';
                    activeIndex = -1;
                    document.dispatchEvent(new CustomEvent('sidebar:closed'));
                };

                const isOpen = () => !panel.classList.contains('hidden');

                trigger.addEventListener('click', e => {
                    e.stopPropagation();
                    isOpen() ? closePanel() : openPanel();
                });

                input.addEventListener('input', e => render(e.target.value));

                input.addEventListener('keydown', e => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (!flatList.length) return;
                        activeIndex = (activeIndex + 1) % flatList.length;
                        highlight();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (!flatList.length) return;
                        activeIndex = (activeIndex - 1 + flatList.length) % flatList.length;
                        highlight();
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (activeIndex < 0) return;
                        const item = flatList[activeIndex];
                        if (item.action) { handleAction(item.action); closePanel(); }
                        else if (item.url && item.url !== '#') window.location.href = item.url;
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        closePanel();
                        trigger.focus();
                    } else if (e.key === 'Tab') {
                        closePanel();
                    }
                });

                document.addEventListener('click', e => {
                    if (!isOpen()) return;
                    if (!wrap.contains(e.target)) closePanel();
                });

                // Auto-close when the sidebar collapses
                const observer = new MutationObserver(() => {
                    if (body.classList.contains('sidebar-collapsed') && isOpen()) closePanel();
                });
                observer.observe(body, { attributes: true, attributeFilter: ['class'] });

                // DO NOT bind ⌘K here — the topbar owns the shortcut.
                window.SidebarSearch = { open: openPanel, close: closePanel, isOpen, render };
            })();

            /* =========================================================
               5. LEGACY MODAL PALETTE (kept for compatibility)
               ========================================================= */
            const palette    = document.getElementById('command-palette');
            const palInput   = document.getElementById('command-input');
            const palResults = document.getElementById('command-results');
            const palOpener  = document.getElementById('open-command-palette');

            if (palette && palInput && palResults && palOpener) {
                const items = window.SIDEBAR_PALETTE_ITEMS || [];

                const renderPalette = (filter = '') => {
                    const q = filter.toLowerCase();
                    const list = items.filter(i => i.label.toLowerCase().includes(q));
                    palResults.innerHTML = list.length
                        ? list.map(i => `
                            <li>
                                <a href="${escapeAttr(i.url || '#')}"
                                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200
                                          hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    ${svg(i.icon || 'file-text', 'w-4 h-4 opacity-60')}
                                    <span>${escapeHtml(i.label)}</span>
                                </a>
                            </li>`).join('')
                        : `<li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No matches</li>`;
                };

                const openPalette  = () => {
                    palette.classList.remove('hidden');
                    renderPalette();
                    palInput.value = '';
                    palInput.focus();
                };
                const closePalette = () => palette.classList.add('hidden');

                palOpener.addEventListener('click', openPalette);
                palInput.addEventListener('input', e => renderPalette(e.target.value));
                palette.querySelectorAll('[data-palette-close]').forEach(el =>
                    el.addEventListener('click', closePalette));

                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !palette.classList.contains('hidden')) closePalette();
                });
            }

            /* =========================================================
               6. THEME DRAWER
               ========================================================= */
            const drawer  = document.getElementById('theme-drawer');
            const panel   = document.getElementById('theme-panel');
            const opener2 = document.getElementById('open-theme-drawer');

            if (drawer && panel) {
                const TKEY = 'sb.theme';

                const defaults = {
                    mode: 'light', accent: 'blue', density: 'comfortable',
                    layout: 'fluid', sidebar: 'default'
                };

                const readState = () => {
                    try { return { ...defaults, ...JSON.parse(localStorage.getItem(TKEY) || '{}') }; }
                    catch (_) { return { ...defaults }; }
                };
                const writeState = (s) => {
                    try { localStorage.setItem(TKEY, JSON.stringify(s)); } catch (_) {}
                };

                const applyState = (s) => {
                    document.documentElement.classList.toggle('dark', s.mode === 'dark');

                    body.dataset.accent  = s.accent;
                    body.dataset.density = s.density;
                    body.dataset.layout  = s.layout;
                    body.dataset.sidebar = s.sidebar;

                    document.querySelectorAll('.theme-mode-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeMode === s.mode));
                    document.querySelectorAll('.theme-color-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeColor === s.accent));
                    document.querySelectorAll('.theme-density-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeDensity === s.density));
                    document.querySelectorAll('.theme-layout-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeLayout === s.layout));
                    document.querySelectorAll('.theme-sidebar-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeSidebar === s.sidebar));

                    // Broadcast so the topbar can sync its dark-mode icon.
                    document.dispatchEvent(new CustomEvent('theme:state', { detail: s }));
                };

                let state = readState();
                applyState(state);

                const openDrawer = () => {
                    drawer.classList.remove('hidden');
                    requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
                    body.classList.add('overflow-hidden');
                };
                const closeDrawer = () => {
                    panel.classList.add('translate-x-full');
                    setTimeout(() => {
                        drawer.classList.add('hidden');
                        body.classList.remove('overflow-hidden');
                    }, 300);
                };

                opener2?.addEventListener('click', openDrawer);
                drawer.querySelectorAll('[data-theme-close]').forEach(el =>
                    el.addEventListener('click', closeDrawer));
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !drawer.classList.contains('hidden')) closeDrawer();
                });

                document.querySelectorAll('.theme-mode-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.mode = b.dataset.themeMode; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-color-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.accent = b.dataset.themeColor; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-density-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.density = b.dataset.themeDensity; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-layout-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.layout = b.dataset.themeLayout; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-sidebar-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.sidebar = b.dataset.themeSidebar; writeState(state); applyState(state);
                    }));

                document.getElementById('theme-reset')?.addEventListener('click', () => {
                    state = { ...defaults };
                    writeState(state);
                    applyState(state);
                });

                // React to topbar's theme toggle
                document.addEventListener('theme:changed', (e) => {
                    const nextMode = e.detail?.mode;
                    if (!nextMode || state.mode === nextMode) return;
                    state.mode = nextMode;
                    writeState(state);
                    applyState(state);
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>

<style>
    /* =========================================================
       SEARCH BOX
       ========================================================= */
    .sb-search2 {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        cursor: pointer;
        text-align: left;
        border-radius: 0.75rem;
        outline: none;
    }

    .sb-search2-ring {
        position: absolute;
        inset: -1px;
        border-radius: 0.85rem;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 25%, #ec4899 50%, #f59e0b 75%, #3b82f6 100%);
        background-size: 300% 300%;
        opacity: 0;
        transition: opacity 0.3s ease;
        animation: sb-search2-gradient 6s ease infinite;
        z-index: 0;
    }

    .sb-search2:hover .sb-search2-ring,
    .sb-search2:focus-visible .sb-search2-ring {
        opacity: 1;
    }

    @keyframes sb-search2-gradient {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .sb-search2-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.5rem 0.6rem;
        border-radius: 0.75rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .dark .sb-search2-inner { background: #111827; border-color: #1f2937; }
    .sb-search2:hover .sb-search2-inner { border-color: transparent; }
    .dark .sb-search2:hover .sb-search2-inner { background: #111827; }

    .sb-search2-icon {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
        color: #4f46e5;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .dark .sb-search2-icon {
        background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        color: #a5b4fc;
    }
    .sb-search2:hover .sb-search2-icon { transform: scale(1.05); }

    .sb-search2-text { flex: 1; min-width: 0; display: flex; flex-direction: column; line-height: 1.1; }
    .sb-search2-label { font-size: 0.8125rem; font-weight: 500; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dark .sb-search2-label { color: #9ca3af; }
    .sb-search2-hint { font-size: 0.6875rem; color: #9ca3af; }
    .dark .sb-search2-hint { color: #6b7280; }

    .sb-search2-kbd { flex-shrink: 0; display: inline-flex; align-items: center; gap: 1px; }
    .sb-search2-kbd kbd {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 1.25rem; height: 1.25rem; padding: 0 0.25rem;
        font-size: 0.6875rem; font-weight: 600; font-family: inherit;
        color: #6b7280; background: #f3f4f6; border: 1px solid #e5e7eb;
        border-bottom-width: 2px; border-radius: 0.375rem;
    }
    .dark .sb-search2-kbd kbd { color: #9ca3af; background: #1f2937; border-color: #374151; }

    /* =========================================================
        SIDEBAR COLLAPSED — data-attribute mirror
        (works even if something strips the class from body)
    ========================================================= */
    body[data-sidebar-collapsed="true"] #sidebar { width: 4.5rem !important; }

    body[data-sidebar-collapsed="true"] .sidebar-label,
    body[data-sidebar-collapsed="true"] .sidebar-label-wrap,
    body[data-sidebar-collapsed="true"] .sidebar-dropdown {
        display: none !important;
    }

    body[data-sidebar-collapsed="true"] .sb-search2-text,
    body[data-sidebar-collapsed="true"] .sb-search2-kbd { display: none !important; }

    body[data-sidebar-collapsed="true"] .sb-search2-inner {
        justify-content: center !important;
        padding: 0.5rem !important;
    }

    body[data-sidebar-collapsed="true"] .sb-brand {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    body[data-sidebar-collapsed="true"] .sb-nav-item,
    body[data-sidebar-collapsed="true"] button.sb-nav-item {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        gap: 0 !important;
    }

    body[data-sidebar-collapsed="true"] .sb-user {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    body[data-sidebar-collapsed="true"] .sb-collapse-btn {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        gap: 0 !important;
    }

    body[data-sidebar-collapsed="true"] #sidebar-collapse-icon {
        transform: rotate(180deg) !important;
    }

    /* =========================================================
       THEME ACTIVE STATES
       ========================================================= */
    .theme-mode-btn.is-active,
    .theme-density-btn.is-active,
    .theme-layout-btn.is-active,
    .theme-sidebar-btn.is-active {
        border-color: #3b82f6;
        background-color: #eff6ff;
        color: #1d4ed8;
    }
    .dark .theme-mode-btn.is-active,
    .dark .theme-density-btn.is-active,
    .dark .theme-layout-btn.is-active,
    .dark .theme-sidebar-btn.is-active {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        color: #93c5fd;
    }
    .theme-color-btn.is-active {
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #3b82f6;
        transform: scale(1.1);
    }

    /* =========================================================
       ACCENT TOKENS
       ========================================================= */
    body[data-accent="blue"]   { --accent: #3b82f6; }
    body[data-accent="indigo"] { --accent: #6366f1; }
    body[data-accent="violet"] { --accent: #8b5cf6; }
    body[data-accent="pink"]   { --accent: #ec4899; }
    body[data-accent="rose"]   { --accent: #f43f5e; }
    body[data-accent="amber"]  { --accent: #f59e0b; }
    body[data-accent="emerald"]{ --accent: #10b981; }
    body[data-accent="teal"]   { --accent: #14b8a6; }
    body[data-accent="cyan"]   { --accent: #06b6d4; }
    body[data-accent="slate"]  { --accent: #64748b; }

    /* =========================================================
       DENSITY
       ========================================================= */
    body[data-density="compact"] nav a,
    body[data-density="compact"] nav button { padding-top: 0.375rem; padding-bottom: 0.375rem; }
    body[data-density="spacious"] nav a,
    body[data-density="spacious"] nav button { padding-top: 0.625rem; padding-bottom: 0.625rem; }

    /* =========================================================
       GRADIENT SIDEBAR STYLE
       ========================================================= */
    body[data-sidebar="gradient"] #sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
    }
    .dark body[data-sidebar="gradient"] #sidebar,
    body[data-sidebar="gradient"].dark #sidebar {
        background: linear-gradient(180deg, #111827 0%, #0b1220 100%);
    }

    /* =========================================================
       NO-ANIM
       ========================================================= */
    .no-anim, .no-anim * { transition: none !important; animation: none !important; }

    /* =========================================================
       INLINE SEARCH PANEL
       ========================================================= */
    .sb-panel {
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 0;
        right: 0;
        z-index: 50;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .dark .sb-panel { background: #111827; border-color: #1f2937; }
    .sb-panel-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-bottom: 1px solid #e5e7eb; }
    .dark .sb-panel-header { border-color: #1f2937; }
    .sb-panel-input { flex: 1; border: 0; background: transparent; font-size: 0.8125rem; color: #111827; outline: none; }
    .dark .sb-panel-input { color: #f9fafb; }
    .sb-panel-kbd, .sb-mini-kbd {
        font-size: 0.625rem; font-weight: 600; padding: 1px 5px; border-radius: 4px;
        background: #f3f4f6; border: 1px solid #e5e7eb; color: #6b7280;
    }
    .dark .sb-panel-kbd, .dark .sb-mini-kbd { background: #1f2937; border-color: #374151; color: #9ca3af; }
    .sb-panel-body { max-height: 20rem; overflow-y: auto; padding: 0.25rem; }
    .sb-group-label {
        padding: 0.375rem 0.5rem 0.125rem;
        font-size: 0.625rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: #9ca3af;
    }
    .sb-item {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 0.5rem; border-radius: 0.5rem;
        font-size: 0.8125rem; color: #374151;
        text-decoration: none; transition: background 0.12s;
    }
    .dark .sb-item { color: #d1d5db; }
    .sb-item:hover, .sb-item.is-active { background: #eff6ff; color: #1d4ed8; }
    .dark .sb-item:hover, .dark .sb-item.is-active { background: rgba(59,130,246,0.12); color: #93c5fd; }
    .sb-item-icon { display: inline-flex; color: #6b7280; flex-shrink: 0; }
    .sb-item.is-active .sb-item-icon { color: #3b82f6; }
    .sb-item-label { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sb-item-arrow { opacity: 0; transform: translateX(-2px); transition: all 0.15s; }
    .sb-item.is-active .sb-item-arrow { opacity: 1; transform: translateX(0); }
    .sb-panel-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.4rem 0.75rem; border-top: 1px solid #e5e7eb;
        font-size: 0.6875rem; color: #9ca3af;
    }
    .dark .sb-panel-footer { border-color: #1f2937; }
    .sb-empty { display: flex; flex-direction: column; align-items: center; padding: 1.5rem 1rem; text-align: center; color: #9ca3af; font-size: 0.8125rem; }
</style>