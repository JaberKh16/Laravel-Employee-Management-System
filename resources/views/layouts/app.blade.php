<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Office Management System'))</title>

    {{-- Anti-FOUC: apply dark class BEFORE CSS loads --}}
    @include('partials.js.anti-fouc')

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/script.js') }}" defer></script>

    {{-- Modern aesthetic enhancements --}}
    <style>
        :root {
            --brand-50: #eef2ff;
            --brand-100: #e0e7ff;
            --brand-500: #6366f1;
            --brand-600: #4f46e5;
            --brand-700: #4338ca;
        }
        * { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif; }

        [x-cloak] { display: none !important; }

        /* ============ PREMIUM DARK MODE — FULL SCREEN ============ */
        html, body {
            transition: background-color 0.35s ease, color 0.35s ease;
        }

        /* Light mode base */
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Dark mode base — full screen premium */
        .dark body {
            background-color: #0a0e1a !important;
            color: #e2e8f0 !important;
        }

        /* Ensure the entire viewport is dark */
        .dark {
            background-color: #0a0e1a;
            color-scheme: dark;
        }

        .dark html {
            background-color: #0a0e1a;
        }

        /* Force dark backgrounds on all sections */
        .dark nav,
        .dark main,
        .dark footer,
        .dark section,
        .dark div {
            border-color: rgba(51, 65, 85, 0.5);
        }

        /* Glassmorphism navigation */
        .glass-nav {
            backdrop-filter: saturate(180%) blur(14px);
            -webkit-backdrop-filter: saturate(180%) blur(14px);
            background-color: rgba(255, 255, 255, 0.72);
            border-bottom: 1px solid rgba(229, 231, 235, 0.6);
            transition: background-color 0.35s ease, border-color 0.35s ease;
        }
        .dark .glass-nav {
            background-color: rgba(10, 14, 26, 0.85) !important;
            border-bottom: 1px solid rgba(51, 65, 85, 0.6) !important;
        }

        /* Animated gradient background orbs */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            pointer-events: none;
            z-index: -1;
            transition: opacity 0.5s ease, background 0.5s ease;
        }
        .bg-orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #a5b4fc, transparent 70%);
            top: -180px; right: -120px;
        }
        .bg-orb-2 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #c7d2fe, transparent 70%);
            bottom: -160px; left: -120px;
        }
        .dark .bg-orb-1 {
            background: radial-gradient(circle, #4338ca, transparent 70%);
            opacity: 0.3;
        }
        .dark .bg-orb-2 {
            background: radial-gradient(circle, #312e81, transparent 70%);
            opacity: 0.3;
        }

        /* Button shine effect */
        .btn-shine {
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        .btn-shine:hover::after { left: 100%; }
        .btn-shine:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5);
        }

        /* Icon button hover ring */
        .icon-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .icon-btn:hover {
            transform: scale(1.08);
        }
        .icon-btn:active {
            transform: scale(0.95);
        }

        /* Dropdown animation */
        .dropdown-anim {
            animation: dropdownIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes dropdownIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Card hover */
        .card-hover { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
        }
        .dark .card-hover:hover {
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.6);
        }

        /* Avatar glow */
        .avatar-glow {
            box-shadow: 0 0 0 2px rgba(255,255,255,0.9), 0 0 0 4px rgba(99,102,241,0.25);
        }
        .dark .avatar-glow {
            box-shadow: 0 0 0 2px rgba(10,14,26,0.9), 0 0 0 4px rgba(99,102,241,0.5);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.5); border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,0.7); }
        .dark ::-webkit-scrollbar-thumb { background: rgba(71,85,105,0.6); }
        .dark ::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,0.8); }

        /* ============ DARK MODE TEXT OVERRIDES ============ */
        .dark .text-gray-900 { color: #f1f5f9 !important; }
        .dark .text-gray-800 { color: #e2e8f0 !important; }
        .dark .text-gray-700 { color: #cbd5e1 !important; }
        .dark .text-gray-600 { color: #94a3b8 !important; }
        .dark .text-gray-500 { color: #64748b !important; }
        .dark .text-gray-400 { color: #475569 !important; }

        /* ============ DARK MODE BACKGROUND OVERRIDES ============ */
        .dark .bg-white { background-color: #111827 !important; }
        .dark .bg-gray-50 { background-color: #0f172a !important; }
        .dark .bg-gray-100 { background-color: #1e293b !important; }

        /* Premium dark card */
        .dark .dark-card {
            background: linear-gradient(145deg, #111827, #0f172a);
            border: 1px solid rgba(51, 65, 85, 0.5);
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.5);
        }

        /* Dark mode form elements */
        .dark input,
        .dark textarea,
        .dark select {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b !important;
        }

        /* Dark mode table */
        .dark table {
            color: #e2e8f0;
        }
        .dark thead {
            background-color: #1e293b !important;
        }
        .dark tbody tr {
            border-color: #334155 !important;
        }
        .dark tbody tr:hover {
            background-color: #1e293b !important;
        }

        /* Dark mode borders */
        .dark .border-gray-200 { border-color: #334155 !important; }
        .dark .border-gray-100 { border-color: #1e293b !important; }
        .dark .divide-gray-100 > * + * { border-color: #1e293b !important; }
        .dark .divide-gray-200 > * + * { border-color: #334155 !important; }

        /* Dark mode ring */
        .dark .ring-black\/5 { --tw-ring-color: rgba(255, 255, 255, 0.05) !important; }

        /* Dark mode shadow */
        .dark .shadow-sm,
        .dark .shadow,
        .dark .shadow-md,
        .dark .shadow-lg,
        .dark .shadow-xl,
        .dark .shadow-2xl {
            --tw-shadow-color: rgba(0, 0, 0, 0.6) !important;
        }

        /* Ensure full-height dark coverage */
        .dark #app,
        .dark .min-h-screen {
            background-color: #0a0e1a;
        }

        /* Smooth theme transition for all elements */
        .dark *,
        .dark *::before,
        .dark *::after {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Prevent transition flash on load */
        .no-transition * {
            transition: none !important;
        }
    </style>
</head>
<body class="antialiased min-h-screen selection:bg-indigo-500/20 selection:text-indigo-900 dark:selection:bg-indigo-500/30 dark:selection:text-indigo-100">

    {{-- Decorative background orbs --}}
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    {{-- Skip to content (accessibility) --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[60]
              focus:px-5 focus:py-2.5 focus:bg-indigo-600 focus:text-white focus:rounded-xl focus:shadow-xl focus:font-semibold">
        {{ __('Skip to content') }}
    </a>

    {{-- Navbar --}}
    <nav class="glass-nav sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">

                {{-- Brand (left) --}}
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-3 text-xl font-extrabold tracking-tight group">
                        <div class="relative w-10 h-10 bg-gradient-to-br from-indigo-500 via-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300 group-hover:scale-105">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-white dark:border-slate-900"></span>
                        </div>
                        <span class="hidden sm:inline-block bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 dark:from-white dark:via-indigo-100 dark:to-violet-200 bg-clip-text text-transparent">
                            {{ config('app.name', 'OfficeMS') }}
                        </span>
                    </a>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-1.5 sm:gap-2">

                    {{-- Desktop auth links --}}
                    <div class="hidden md:flex items-center gap-2">
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                   class="group flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400 transition-colors px-3.5 py-2 rounded-xl hover:bg-gray-100/70 dark:hover:bg-slate-800/60">
                                    <svg class="w-4 h-4 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                    {{ __('Login') }}
                                </a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="btn-shine text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-violet-600
                                          focus:ring-4 focus:outline-none focus:ring-indigo-300/50
                                          rounded-xl px-5 py-2.5 text-center shadow-lg shadow-indigo-500/25
                                          inline-flex items-center gap-2 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                    </svg>
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @else
                            {{-- User dropdown --}}
                            <button id="user-menu-button"
                                    data-dropdown-toggle="user-menu"
                                    data-dropdown-placement="bottom-end"
                                    type="button"
                                    class="flex items-center gap-3 text-sm rounded-2xl
                                           hover:bg-gray-100/80 dark:hover:bg-slate-800/70
                                           focus:ring-4 focus:outline-none focus:ring-indigo-200/40 dark:focus:ring-indigo-900/40
                                           px-2.5 py-2 transition-all duration-200">
                                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white
                                             flex items-center justify-center font-bold text-sm shadow-md avatar-glow">
                                    {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                                </span>
                                <span class="font-semibold text-gray-700 dark:text-slate-200 hidden lg:inline-block">
                                    {{ Auth::user()->username }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none"
                                     stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>

                            {{-- Dropdown menu --}}
                            <div id="user-menu"
                                 class="hidden z-50 my-2 text-base list-none
                                        bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl divide-y divide-gray-100 dark:divide-slate-700/80
                                        rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/5 w-60 overflow-hidden dropdown-anim">
                                <div class="px-4 py-4 bg-gradient-to-r from-indigo-50/70 to-violet-50/70 dark:from-slate-800 dark:to-slate-800">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                            {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate dark:text-white">
                                                {{ Auth::user()->username }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate dark:text-slate-400 mt-0.5">
                                                {{ Auth::user()->email ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <ul class="py-2">
                                    <li>
                                        <a href="#"
                                           class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700
                                                  hover:bg-indigo-50/70 dark:text-slate-300 dark:hover:bg-slate-700/60 dark:hover:text-white transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                            {{ __('Profile') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                           class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700
                                                  hover:bg-indigo-50/70 dark:text-slate-300 dark:hover:bg-slate-700/60 dark:hover:text-white transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ __('Settings') }}
                                        </a>
                                    </li>
                                </ul>
                                <ul class="py-2 border-t border-gray-100 dark:border-slate-700/80">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                           class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-red-600
                                                  hover:bg-red-50 dark:hover:bg-red-950/40 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
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
                        @endguest
                    </div>

                    {{-- Theme toggle --}}
                    <button id="theme-toggle" type="button"
                            aria-label="{{ __('Toggle dark mode') }}"
                            class="icon-btn text-gray-500 dark:text-slate-400
                                   hover:bg-gray-100/80 dark:hover:bg-slate-800/70
                                   hover:text-indigo-600 dark:hover:text-indigo-400
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200/40 dark:focus:ring-indigo-900/40
                                   rounded-xl p-2.5 transition-all duration-200">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Mobile menu button --}}
                    <button data-collapse-toggle="mobile-menu" type="button"
                            class="md:hidden icon-btn inline-flex items-center justify-center p-2.5
                                   text-gray-500 rounded-xl hover:bg-gray-100/80 hover:text-indigo-600
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200/40
                                   dark:text-slate-400 dark:hover:bg-slate-800/70 dark:hover:text-indigo-400 dark:focus:ring-indigo-900/40 transition-all duration-200"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">{{ __('Open main menu') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div class="hidden md:hidden pb-5" id="mobile-menu">
                @guest
                    <div class="flex flex-col gap-2 pt-3 border-t border-gray-200/70 dark:border-slate-700/70">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold
                                      text-gray-700 hover:bg-gray-100/80
                                      dark:text-slate-200 dark:hover:bg-slate-800/70 transition-colors">
                                <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                {{ __('Login') }}
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-center
                                      text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700
                                      shadow-lg shadow-indigo-500/25 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @else
                    <div class="pt-3 border-t border-gray-200/70 dark:border-slate-700/70">
                        <div class="flex items-center gap-3 px-4 py-3">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                            </span>
                            <div>
                                <div class="text-sm font-bold text-gray-800 dark:text-slate-200">
                                    {{ Auth::user()->username }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-slate-400">
                                    {{ Auth::user()->email ?? '' }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600
                                  hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            {{ __('Logout') }}
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main id="main-content" class="relative py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        {{-- Optional page header slot --}}
        @hasSection('header')
            <div class="mb-10">
                @yield('header')
            </div>
        @endif

        @yield('content')

        {{-- Sweet Alert --}}
        @include('components.sweet-alert')
    </main>

    {{-- Footer --}}
    <footer class="relative border-t border-gray-200/60 dark:border-slate-800/60 py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400 dark:text-slate-500">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'OfficeMS') }}. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-5">
                <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Privacy</a>
                <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Terms</a>
                <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Support</a>
            </div>
        </div>
    </footer>

    {{-- ============ DARK MODE FULL SCREEN ENFORCEMENT ============ --}}
    <script>
        (function() {
            // Ensure full screen dark mode coverage
            function enforceDarkMode() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    // Force dark background on html, body
                    document.documentElement.style.backgroundColor = '#0a0e1a';
                    document.body.style.backgroundColor = '#0a0e1a';
                    document.body.style.color = '#e2e8f0';
                    
                    // Ensure any direct child of body is dark
                    document.body.querySelectorAll(':scope > *').forEach(el => {
                        if (el.tagName !== 'SCRIPT' && el.tagName !== 'STYLE') {
                            el.style.backgroundColor = el.style.backgroundColor || 'transparent';
                        }
                    });
                } else {
                    document.documentElement.style.backgroundColor = '';
                    document.body.style.backgroundColor = '';
                    document.body.style.color = '';
                }
            }

            // Run on load
            enforceDarkMode();

            // Watch for class changes on html element (theme toggle)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        enforceDarkMode();
                    }
                });
            });

            observer.observe(document.documentElement, { attributes: true });

            // Also handle theme toggle button if present
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    // Small delay to let the class change
                    setTimeout(enforceDarkMode, 50);
                });
            }

            // Prevent white flash on dark mode reload
            if (document.documentElement.classList.contains('dark')) {
                document.body.classList.add('no-transition');
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        document.body.classList.remove('no-transition');
                    });
                });
            }
        })();
    </script>

</body>
</html>