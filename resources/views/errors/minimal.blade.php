<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="{{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Error')) — {{ config('app.name', 'OMS') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">

    {{-- Tailwind (adjust to your build) --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- Mix Setup (adjust to your build) --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

    {{-- Force dark mode from localStorage before paint (no flash) --}}
    <script>
        (function () {
            try {
                const t = localStorage.getItem('sb.theme');
                if (t) {
                    const s = JSON.parse(t);
                    if (s.mode === 'dark') document.documentElement.classList.add('dark');
                    if (s.mode === 'light') document.documentElement.classList.remove('dark');
                }
            } catch (_) {}
        })();
    </script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* ── Animated gradient blob background ──────────────── */
        .err-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59,130,246,0.15), transparent),
                radial-gradient(ellipse 60% 50% at 80% 100%, rgba(139,92,246,0.12), transparent),
                radial-gradient(ellipse 60% 50% at 20% 100%, rgba(236,72,153,0.10), transparent);
        }
        .dark .err-bg {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59,130,246,0.20), transparent),
                radial-gradient(ellipse 60% 50% at 80% 100%, rgba(139,92,246,0.15), transparent),
                radial-gradient(ellipse 60% 50% at 20% 100%, rgba(236,72,153,0.12), transparent);
        }

        .err-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: err-float 20s ease-in-out infinite;
        }
        .err-blob-1 { width: 400px; height: 400px; top: -100px; left: -100px;
                      background: #60a5fa; animation-delay: 0s; }
        .err-blob-2 { width: 350px; height: 350px; bottom: -80px; right: -80px;
                      background: #a78bfa; animation-delay: -7s; }
        .err-blob-3 { width: 300px; height: 300px; top: 40%; left: 60%;
                      background: #f472b6; animation-delay: -14s; }

        @keyframes err-float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(40px, -30px) scale(1.1); }
            66%      { transform: translate(-30px, 30px) scale(0.95); }
        }

        /* ── Floating icon animation ────────────────────────── */
        @keyframes err-float-icon {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }
        .err-icon-float { animation: err-float-icon 4s ease-in-out infinite; }

        /* ── Entry fade-up ──────────────────────────────────── */
        @keyframes err-fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .err-fade-up {
            animation: err-fade-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .err-delay-1 { animation-delay: 0.05s; }
        .err-delay-2 { animation-delay: 0.15s; }
        .err-delay-3 { animation-delay: 0.25s; }
        .err-delay-4 { animation-delay: 0.35s; }

        @media (prefers-reduced-motion: reduce) {
            .err-blob, .err-icon-float, .err-fade-up {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased
             dark:bg-gray-950 dark:text-gray-100">

    {{-- Background layers --}}
    <div class="err-bg" aria-hidden="true">
        <div class="err-blob err-blob-1"></div>
        <div class="err-blob err-blob-2"></div>
        <div class="err-blob err-blob-3"></div>
    </div>

    {{-- Top bar (minimal) --}}
    <header class="relative z-10 w-full px-6 py-5">
        <div class="mx-auto max-w-7xl flex items-center justify-between">
            <a href="{{ url('/') }}"
               class="flex items-center gap-2.5 group err-fade-up">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl
                             bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600
                             text-white shadow-md shadow-blue-500/20
                             transition-transform duration-300 group-hover:scale-105">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 112 0 1 1 0 01-2 0zm0 4a1 1 0 112 0v4a1 1 0 11-2 0v-4z"/>
                    </svg>
                </span>
                <span class="text-sm font-semibold tracking-tight
                             text-gray-900 dark:text-white">
                    {{ config('app.name', 'OMS') }}
                </span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium
                          text-gray-600 hover:text-gray-900
                          dark:text-gray-400 dark:hover:text-white
                          transition-colors px-3 py-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Go back') }}
                </a>

                <button type="button" id="theme-toggle-mini"
                        aria-label="{{ __('Toggle dark mode') }}"
                        class="inline-flex items-center justify-center p-2 rounded-lg
                               text-gray-500 hover:bg-gray-100
                               dark:text-gray-400 dark:hover:bg-gray-800
                               transition-colors">
                    <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="relative z-10 flex items-center justify-center px-6
                 min-h-[calc(100vh-5rem)] pb-16">
        <div class="w-full max-w-2xl text-center">

            {{-- Icon --}}
            <div class="err-fade-up flex justify-center mb-8">
                <div class="err-icon-float relative inline-flex items-center justify-center">
                    {{-- Glow ring --}}
                    <span class="absolute inset-0 rounded-full blur-2xl opacity-40
                                 @yield('glow-class', 'bg-blue-500')"></span>

                    {{-- Icon container --}}
                    <div class="relative inline-flex items-center justify-center
                                w-28 h-28 rounded-3xl
                                @yield('icon-bg-class', 'bg-gradient-to-br from-blue-500 to-indigo-600')
                                text-white
                                shadow-2xl
                                @yield('icon-shadow', 'shadow-blue-500/30')">
                        @yield('icon')
                    </div>
                </div>
            </div>

            {{-- Error code --}}
            <p class="err-fade-up err-delay-1 text-sm font-bold tracking-[0.3em] uppercase
                      text-gray-400 dark:text-gray-500 mb-3">
                @yield('code-label', __('Error'))
            </p>

            {{-- Title --}}
            <h1 class="err-fade-up err-delay-2 text-4xl sm:text-5xl font-extrabold tracking-tight
                       text-gray-900 dark:text-white mb-4">
                @yield('heading')
            </h1>

            {{-- Description --}}
            <p class="err-fade-up err-delay-3 text-base sm:text-lg
                      text-gray-600 dark:text-gray-400
                      max-w-lg mx-auto leading-relaxed">
                @yield('description')
            </p>

            {{-- Extra info slot --}}
            @hasSection('extra')
                <div class="err-fade-up err-delay-3 mt-6">
                    @yield('extra')
                </div>
            @endif

            {{-- Actions --}}
            <div class="err-fade-up err-delay-4 mt-10 flex flex-wrap items-center
                        justify-center gap-3">
                @yield('actions')
            </div>

            {{-- Footer hint --}}
            <p class="err-fade-up err-delay-4 mt-12 text-xs text-gray-400 dark:text-gray-500">
                @yield('footer-hint', __('If this problem persists, contact your administrator.'))
            </p>
        </div>
    </main>

    {{-- Theme toggle script --}}
    <script>
        (function () {
            const btn = document.getElementById('theme-toggle-mini');
            if (!btn) return;
            btn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                try {
                    const stored = JSON.parse(localStorage.getItem('sb.theme') || '{}');
                    stored.mode = isDark ? 'dark' : 'light';
                    localStorage.setItem('sb.theme', JSON.stringify(stored));
                } catch (_) {
                    localStorage.setItem('sb.theme', JSON.stringify({ mode: isDark ? 'dark' : 'light' }));
                }
            });
        })();
    </script>
</body>
</html>