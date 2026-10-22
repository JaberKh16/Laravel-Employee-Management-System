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