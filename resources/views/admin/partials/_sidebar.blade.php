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
                                Route::has('notifications.index')       ? 'notifications.list'       : null => __('Notifacations'),
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
@include('admin.templates._command_pallete')

<!-- Theme Drawer -->
@include('admin.templates._theme_drawer')

@include('admin.partials.resources.js._sidebar-scripts')

@include('admin.partials.resources.css._sidebar-styles')   