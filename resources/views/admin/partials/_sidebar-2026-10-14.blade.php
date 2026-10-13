{{-- =========================================================
     SIDEBAR — HTML only (CSS in sidebar.css, JS in sidebar.js)
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
           class="group flex items-center gap-3 px-2 py-3 mb-2 rounded-lg
                  hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="flex-shrink-0 inline-flex items-center justify-center
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

        {{-- ✨ Modern colorful search box --}}
        {{-- ✨ Modern colorful search box with INLINE page palette --}}
<div class="sidebar-label-wrap relative transition-[opacity,height,margin] duration-200 ease-out
            sidebar-collapsed:h-0 sidebar-collapsed:opacity-0 sidebar-collapsed:mb-0
            h-auto opacity-100 mb-3"
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

    {{-- ▼ Inline dropdown panel — appears below the search box --}}
    <div id="sidebar-search-panel"
         class="sb-panel hidden"
         role="listbox"
         aria-label="{{ __('Available pages') }}">

        {{-- Sticky filter input --}}
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

        {{-- Grouped results --}}
        <div class="sb-panel-body" id="sidebar-search-results"></div>

        {{-- Footer hint --}}
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
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-lg transition-colors
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
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-lg transition-colors
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
                                class="group flex items-center justify-between gap-2 w-full
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
                            class="{{ $isAdminActive ? '' : 'hidden' }} mt-1 space-y-0.5
                                   pl-4 border-l border-gray-200 dark:border-gray-700 ml-4">
                            @foreach (array_filter([
                                Route::has('users.index')       ? 'users.index'       : null => __('Users'),
                                Route::has('roles.index')       ? 'roles.index'       : null => __('Roles'),
                                Route::has('permissions.index') ? 'permissions.index' : null => __('Permissions'),
                                Route::has('social.services.index') ? 'social.services.index' : null => __('Social Services'),
                                Route::has('audit.index')       ? 'audit.index'       : null => __('Audit Log'),
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
                            class="group flex items-center justify-between gap-2 w-full
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
                        class="{{ $isSystemActive ? '' : 'hidden' }} mt-1 space-y-0.5
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
                            class="group flex items-center gap-3 w-full px-2.5 py-2 rounded-lg
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
                <div class="flex items-center gap-2 px-1">
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

            <button type="button"
                    id="sidebar-collapse-toggle"
                    aria-label="{{ __('Collapse sidebar') }}"
                    class="hidden lg:flex items-center gap-2 w-full mt-2 px-2.5 py-2
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
            </button>
        </div>
    </div>
</aside>

{{-- ========================================================= --}}
{{-- ⌘K COMMAND PALETTE                                          --}}
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
        // ── Main ───────────────────────────────────────
        { label: @json(__('Dashboard')),  url: @json(route('home')),           icon: 'home',     group: @json(__('Main')) },
        { label: @json(__('Employees')),  url: @json('/employees'),            icon: 'users',    group: @json(__('Main')) },

        // ── Administration ─────────────────────────────
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

        // ── System ─────────────────────────────────────
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

        // ── Tools ──────────────────────────────────────
        { label: @json(__('Theme Options')), url: '#theme',   icon: 'palette', group: @json(__('Tools')), action: 'theme-drawer' },
        { label: @json(__('My Profile')),    url: @json(Route::has('users.profile') ? route('users.profile') : '#'), icon: 'user', group: @json(__('Tools')) },
        { label: @json(__('Settings')),      url: '#settings', icon: 'cog', group: @json(__('Tools')), action: 'settings' },
    ];

    /* =========================================================
       SIDEBAR — collapse + accordion + command palette + theme drawer
       ========================================================= */
    (function () {
        'use strict';

        function boot() {
            const body      = document.body;
            const sidebar   = document.getElementById('sidebar');
            if (!sidebar) return;

            const toggle      = document.getElementById('sidebar-collapse-toggle');
            const backdrop    = document.getElementById('sidebar-backdrop');
            const STORAGE_KEY = 'sb.sidebar.collapsed';

            /* 1. Sidebar collapse */
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                body.classList.add('no-anim', 'sidebar-collapsed');
                requestAnimationFrame(() => requestAnimationFrame(() =>
                    body.classList.remove('no-anim')));
            }

            toggle?.addEventListener('click', () => {
                const collapsed = body.classList.toggle('sidebar-collapsed');
                localStorage.setItem(STORAGE_KEY, collapsed ? 'true' : 'false');
                window.dispatchEvent(new Event('resize'));
            });

            document.addEventListener('keydown', (e) => {
                const tag = (e.target.tagName || '').toLowerCase();
                if (['input', 'textarea', 'select'].includes(tag)) return;
                if (e.key === '[' || e.key === '\\') {
                    e.preventDefault();
                    toggle?.click();
                }
            });

            /* Mobile drawer */
            document.querySelectorAll('[data-drawer-toggle="sidebar"]').forEach(el => {
                el.addEventListener('click', () => {
                    const open = sidebar.classList.toggle('-translate-x-0');
                    sidebar.classList.toggle('-translate-x-full', !open);
                    backdrop?.classList.toggle('hidden', !open);
                });
            });
            backdrop?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('-translate-x-0');
                backdrop.classList.add('hidden');
            });

            /* 2. Accordion toggles */
            document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
                const target = document.getElementById(btn.getAttribute('data-collapse-toggle'));
                if (!target) return;
                btn.setAttribute('aria-expanded', target.classList.contains('hidden') ? 'false' : 'true');
                btn.addEventListener('click', () => {
                    const opened = !target.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
                });
            });

            /* 3. Command palette */
            const palette = document.getElementById('command-palette');
            const input   = document.getElementById('command-input');
            const results = document.getElementById('command-results');
            const opener  = document.getElementById('open-command-palette');

            if (palette && input && results) {
                const items = window.SIDEBAR_PALETTE_ITEMS || [];

                const render = (filter = '') => {
                    const q = filter.toLowerCase();
                    const list = items.filter(i => i.label.toLowerCase().includes(q));
                    results.innerHTML = list.length
                        ? list.map(i => `
                            <li>
                                <a href="${i.url}" class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200
                                                          hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <span class="w-1 h-1 rounded-full bg-current opacity-50"></span>${i.label}
                                </a>
                            </li>`).join('')
                        : `<li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No matches</li>`;
                };

                const openPalette  = () => { palette.classList.remove('hidden'); render(); input.value = ''; input.focus(); };
                const closePalette = () => { palette.classList.add('hidden'); };

                opener?.addEventListener('click', openPalette);
                input.addEventListener('input', e => render(e.target.value));
                palette.querySelectorAll('[data-palette-close]').forEach(el =>
                    el.addEventListener('click', closePalette));

                document.addEventListener('keydown', (e) => {
                    const isMac = navigator.platform.toLowerCase().includes('mac');
                    const cmdK  = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'k';
                    if (cmdK) {
                        e.preventDefault();
                        palette.classList.contains('hidden') ? openPalette() : closePalette();
                    }
                    if (e.key === 'Escape' && !palette.classList.contains('hidden')) closePalette();
                });
            }

            /* 4. Theme drawer */
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
                const writeState = (state) => {
                    try { localStorage.setItem(TKEY, JSON.stringify(state)); } catch (_) {}
                };

                const applyState = (state) => {
                    document.documentElement.classList.toggle('dark', state.mode === 'dark');

                    body.dataset.accent  = state.accent;
                    body.dataset.density = state.density;
                    body.dataset.layout  = state.layout;
                    body.dataset.sidebar = state.sidebar;

                    document.querySelectorAll('.theme-mode-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeMode === state.mode));
                    document.querySelectorAll('.theme-color-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeColor === state.accent));
                    document.querySelectorAll('.theme-density-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeDensity === state.density));
                    document.querySelectorAll('.theme-layout-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeLayout === state.layout));
                    document.querySelectorAll('.theme-sidebar-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeSidebar === state.sidebar));
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
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>