{{-- ══════════════════════════════════════════════════════════════
     BULK ACTION ROW — sits as a full-width <tr> at the top of the table
     Rendered inside <thead> so it scrolls with the header.
     ══════════════════════════════════════════════════════════════ --}}
<tr id="bulkBar" class="hidden bg-indigo-50/80 dark:bg-indigo-950/40">
    <td colspan="{{ count($columns) + 1 }}" class="px-4 py-2.5">
        <div class="flex flex-wrap items-center justify-between gap-3">

            {{-- Left: count --}}
            <div class="flex items-center gap-2 text-sm">
                <i class="ri-checkbox-multiple-line text-indigo-600 dark:text-indigo-400"></i>
                <span class="font-semibold text-indigo-800 dark:text-indigo-200">
                    <span id="bulkCount">0</span> selected
                </span>
            </div>

            {{-- Right: actions --}}
            <div class="flex flex-wrap items-center gap-2">

                {{-- 1. Change status — opens drawer --}}
                <button type="button"
                        id="openBulkDrawer"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-300 bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 dark:border-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    <i class="ri-edit-circle-line"></i>
                    <span>Change status</span>
                </button>

                {{-- 2. Change role — button-based dropdown --}}
                <div class="relative" data-dropdown>
                    <button type="button"
                            data-dropdown-trigger
                            aria-expanded="false"
                            aria-haspopup="true"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-purple-300 bg-white px-3 py-1.5 text-xs font-semibold text-purple-700 shadow-sm transition-colors hover:bg-purple-50 dark:border-purple-800 dark:bg-gray-800 dark:text-purple-300 dark:hover:bg-purple-950/40">
                        <i class="ri-shield-user-line"></i>
                        <span>Change role</span>
                        <svg class="h-3 w-3 opacity-60 transition-transform" data-dropdown-chevron
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div data-dropdown-menu
                         class="absolute right-0 top-full z-50 mt-2 hidden w-56 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-xl shadow-gray-900/10 ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">
                        <div class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            Assign role
                        </div>

                        @forelse ($roles as $role)
                            @php
                                $slug = strtolower($role->name);
                                $dot = match ($slug) {
                                    'super-admin' => 'bg-purple-500',
                                    'admin'       => 'bg-indigo-500',
                                    'manager'     => 'bg-sky-500',
                                    'employee'    => 'bg-emerald-500',
                                    default       => 'bg-slate-400',
                                };
                            @endphp

                            <button type="button"
                                    data-bulk-role="{{ $role->name }}"
                                    class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60">
                                <span class="h-2 w-2 shrink-0 rounded-full {{ $dot }}"></span>
                                <span class="flex-1 capitalize">{{ $role->name }}</span>
                            </button>
                        @empty
                            <div class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                                No roles available.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 3. Clear --}}
                <button type="button"
                        data-bulk-clear
                        class="ml-1 text-xs font-semibold text-gray-500 underline-offset-2 hover:text-gray-700 hover:underline dark:text-gray-400 dark:hover:text-gray-200">
                    Clear
                </button>
            </div>
        </div>
    </td>
</tr>