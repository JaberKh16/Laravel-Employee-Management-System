{{-- ══════════════════════════════════════════════════════════════
     BULK STATUS DRAWER — slides in from the right
     ══════════════════════════════════════════════════════════════ --}}
<div id="bulkDrawer"
     class="fixed inset-0 z-[70] hidden"
     aria-labelledby="bulkDrawerTitle"
     role="dialog"
     aria-modal="true">

    {{-- Backdrop --}}
    <div id="bulkDrawerBackdrop"
         class="absolute inset-0 bg-slate-900/50 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    {{-- Panel --}}
    <aside id="bulkDrawerPanel"
           class="absolute right-0 top-0 flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out dark:bg-gray-900">

        {{-- Header --}}
        <header class="flex items-start justify-between gap-3 border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <div>
                <h2 id="bulkDrawerTitle" class="text-lg font-extrabold text-gray-900 dark:text-white">
                    Update status
                </h2>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    Choose a new status for the selected users.
                </p>
            </div>

            <button type="button" id="bulkDrawerClose"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <i class="ri-close-line text-xl"></i>
            </button>
        </header>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-5 py-5">

            {{-- Selected users preview --}}
            <div class="mb-5 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Selected
                    </span>
                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-bold text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                        <span id="bulkDrawerCount">0</span>
                    </span>
                </div>

                <ul id="bulkDrawerUsers"
                    class="max-h-32 space-y-1 overflow-y-auto text-xs text-gray-600 dark:text-gray-300">
                    {{-- Filled by JS --}}
                </ul>
            </div>

            {{-- Status options --}}
            <fieldset>
                <legend class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    New status
                </legend>

                <div class="space-y-2" role="radiogroup" aria-label="New status">
                    @foreach ($statusOptions as $option)
                        <label
                            data-bulk-status-option
                            data-status-name="{{ $option['name'] }}"
                            class="group flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-indigo-300 hover:bg-indigo-50/40 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/20 dark:has-[:checked]:border-indigo-500 dark:has-[:checked]:bg-indigo-950/40"
                        >
                            <input
                                type="radio"
                                name="bulk_status"
                                value="{{ $option['name'] }}"
                                class="sr-only peer"
                            >

                            <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $option['dot'] }}"></span>

                            <span class="flex-1 text-sm font-semibold text-gray-800 dark:text-gray-100">
                                {{ $option['label'] }}
                            </span>

                            <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white peer-checked:flex">
                                <i class="ri-check-line text-xs"></i>
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            {{-- Warning for banned --}}
            <div id="bulkDrawerWarning"
                 class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-medium text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <i class="ri-alert-line mr-1"></i>
                <span>Banned users are immediately locked out of the system.</span>
            </div>

            {{-- Error box --}}
            <div id="bulkDrawerError"
                 class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-medium text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></div>
        </div>

        {{-- Footer --}}
        <footer class="flex items-center justify-end gap-2 border-t border-gray-200 px-5 py-4 dark:border-gray-700">
            <button type="button" id="bulkDrawerCancel"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Cancel
            </button>

            <button type="button" id="bulkDrawerApply"
                    disabled
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 active:translate-y-0 disabled:pointer-events-none disabled:opacity-50">
                <i class="ri-check-double-line text-base"></i>
                <span>Apply to <span id="bulkDrawerApplyCount">0</span></span>
            </button>
        </footer>
    </aside>
</div>