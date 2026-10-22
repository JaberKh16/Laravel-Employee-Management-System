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