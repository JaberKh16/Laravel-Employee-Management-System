{{-- Sidebar / Dock --}}
@php
    $isSystemActive  = request()->routeIs('countries.*', 'states.*', 'cities.*', 'departments.*');
    $isUsersActive   = request()->routeIs('users.*', 'roles.*', 'permissions.*');
    $isEmployeeActive = request()->is('employees*');
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
              transition-[transform,width] duration-200 ease-out
              -translate-x-full lg:translate-x-0
              dark:bg-gray-900 dark:border-gray-800"
       aria-label="{{ __('Sidebar') }}">

    <div class="flex flex-col h-full px-3 pb-4 overflow-y-auto
                [scrollbar-width:thin]">

        {{-- Brand --}}
        <a href="{{ route('home') }}"
           class="group flex items-center gap-3 px-2 py-3 mb-2 rounded-lg
                  hover:bg-gray-100 dark:hover:bg-gray-800
                  transition-colors">
            <span class="flex-shrink-0 inline-flex items-center justify-center
                         w-9 h-9 rounded-lg
                         bg-gradient-to-br from-blue-500 to-indigo-600 text-white
                         shadow-sm ring-1 ring-blue-500/20">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                </svg>
            </span>
            <div class="sidebar-label flex flex-col leading-tight min-w-0">
                <span class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                    SB Admin
                </span>
                <span class="text-[11px] uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    v2 · workspace
                </span>
            </div>
        </a>

        {{-- Command / search hint --}}
        <button type="button"
                class="sidebar-label mb-3 flex items-center justify-between gap-2
                       w-full px-3 py-2 text-sm rounded-lg
                       bg-gray-50 border border-gray-200
                       text-gray-500 hover:bg-gray-100 hover:text-gray-700
                       dark:bg-gray-800/60 dark:border-gray-700 dark:text-gray-400
                       dark:hover:bg-gray-800 dark:hover:text-gray-200
                       transition-colors">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                {{ __('Search…') }}
            </span>
            <kbd class="text-[10px] font-semibold px-1.5 py-0.5 rounded
                        bg-white border border-gray-200 text-gray-500
                        dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">⌘K</kbd>
        </button>

        {{-- Nav --}}
        <nav class="flex-1">
            <ul class="space-y-1 text-sm font-medium">

                {{-- Dashboard --}}
                @php $dashboardActive = request()->routeIs('home'); @endphp
                <li>
                    <a href="{{ route('home') }}"
                       title="{{ __('Dashboard') }}"
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-lg
                              transition-colors
                              {{ $dashboardActive
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm10 0a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zM3 14a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm10-4a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        <span class="sidebar-label truncate">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- Employee Management --}}
                <li>
                    <a href="/employees"
                       title="{{ __('Employee Management') }}"
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-lg
                              transition-colors
                              {{ $isEmployeeActive
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                        <span class="sidebar-label truncate">{{ __('Employee Management') }}</span>
                    </a>
                </li>

                {{-- System Management --}}
                <li>
                    <button type="button"
                            data-collapse-toggle="dropdown-system"
                            aria-controls="dropdown-system"
                            title="{{ __('System Management') }}"
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
                        <svg class="sidebar-label flex-shrink-0 w-4 h-4 transition-transform
                                    group-aria-expanded:rotate-180"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul id="dropdown-system"
                        class="{{ $isSystemActive ? '' : 'hidden' }} mt-1 space-y-0.5
                               pl-4 border-l border-gray-200 dark:border-gray-700 ml-4">
                        @foreach ([
                            'countries.index'   => __('Country'),
                            'states.index'      => __('State'),
                            'cities.index'      => __('City'),
                            'departments.index' => __('Department'),
                        ] as $route => $label)
                            @php $active = request()->routeIs($route); @endphp
                            <li>
                                <a href="{{ route($route) }}"
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

                {{-- Users Management --}}
                <li>
                    <button type="button"
                            data-collapse-toggle="dropdown-users"
                            aria-controls="dropdown-users"
                            title="{{ __('Users Management') }}"
                            class="group flex items-center justify-between gap-2 w-full
                                   px-2.5 py-2 rounded-lg transition-colors
                                   {{ $isUsersActive
                                         ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                         : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <span class="sidebar-label truncate">{{ __('Adminstrators') }}</span>
                        </span>
                        <svg class="sidebar-label flex-shrink-0 w-4 h-4 transition-transform
                                    group-aria-expanded:rotate-180"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul id="dropdown-users"
                        class="{{ $isUsersActive ? '' : 'hidden' }} mt-1 space-y-0.5
                               pl-4 border-l border-gray-200 dark:border-gray-700 ml-4">
                        @foreach ([
                            'users.index'       => __('Users'),
                            'roles.index'       => __('Role'),
                            'permissions.index' => __('Permission'),
                        ] as $route => $label)
                            @php $active = request()->routeIs($route); @endphp
                            <li>
                                <a href="{{ route($route) }}"
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

            </ul>
        </nav>

        {{-- Footer: user + collapse toggle --}}
        <div class="pt-3 mt-3 border-t border-gray-200 dark:border-gray-800">
            @auth
                <div class="flex items-center gap-2 px-1">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gradient-to-br from-blue-500 to-indigo-600
                                 text-white text-xs font-semibold
                                 flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                    </span>
                    <div class="sidebar-label flex-1 min-w-0">
                        <div class="text-xs font-medium text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->username }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                            {{ Auth::user()->email ?? '' }}
                        </div>
                    </div>
                </div>
            @endauth

            {{-- Collapse (rail) toggle — desktop only --}}
            <button type="button"
                    id="sidebar-collapse-toggle"
                    class="hidden lg:flex items-center gap-2 w-full mt-2 px-2.5 py-2
                           text-xs font-medium rounded-lg
                           text-gray-500 hover:text-gray-900 hover:bg-gray-100
                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800
                           transition-colors">
                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="sidebar-label">{{ __('Collapse') }}</span>
            </button>
        </div>
    </div>
</aside>