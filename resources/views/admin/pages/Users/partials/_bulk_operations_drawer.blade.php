{{-- ══════════════════════════════════════════════════════════════
     BULK OPERATIONS DRAWER — slides in from the right
     ══════════════════════════════════════════════════════════════ --}}
<div id="bulkOpsDrawer"
     class="fixed inset-0 z-[70] hidden"
     role="dialog"
     aria-modal="true"
     aria-labelledby="bulkOpsTitle">

    {{-- Backdrop --}}
    <div id="bulkOpsBackdrop"
         class="absolute inset-0 bg-slate-900/50 opacity-0 backdrop-blur-sm transition-opacity duration-300"></div>

    {{-- Panel --}}
    <aside id="bulkOpsPanel"
           class="absolute right-0 top-0 flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out dark:bg-gray-900">

        {{-- Header --}}
        <header class="flex items-start justify-between gap-3 border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-md shadow-amber-500/30">
                    <i class="ri-tools-line text-xl"></i>
                </span>
                <div>
                    <h2 id="bulkOpsTitle" class="text-lg font-extrabold text-gray-900 dark:text-white">
                        Bulk operations
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Choose an operation to apply to the selected users.
                    </p>
                </div>
            </div>

            <button type="button" id="bulkOpsClose"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <i class="ri-close-line text-xl"></i>
            </button>
        </header>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-5 py-5">

            {{-- Selection preview --}}
            <div class="mb-5 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Selected
                    </span>
                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                        <span id="bulkOpsCount">0</span>
                    </span>
                </div>

                <ul id="bulkOpsUsers"
                    class="max-h-32 space-y-1 overflow-y-auto text-xs text-gray-600 dark:text-gray-300">
                    {{-- JS fills --}}
                </ul>
            </div>

            {{-- Operation cards (radio) --}}
            <fieldset>
                <legend class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Operation
                </legend>

                <div class="space-y-2" role="radiogroup" aria-label="Bulk operation">

                    {{-- Activate --}}
                    <label data-bulk-op-option data-operation="activate"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-emerald-300 hover:bg-emerald-50/40 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/20 dark:has-[:checked]:border-emerald-500 dark:has-[:checked]:bg-emerald-950/40">
                        <input type="radio" name="bulk_operation" value="activate" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                            <i class="ri-checkbox-circle-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Activate</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Set all selected users to Active status.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- Deactivate --}}
                    <label data-bulk-op-option data-operation="deactivate"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-gray-300 hover:bg-gray-50 has-[:checked]:border-gray-500 has-[:checked]:bg-gray-100 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:has-[:checked]:bg-gray-700">
                        <input type="radio" name="bulk_operation" value="deactivate" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <i class="ri-forbid-2-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Deactivate</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Set all selected users to Inactive status.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- Make employees --}}
                    <label data-bulk-op-option data-operation="make-employee"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-sky-300 hover:bg-sky-50/40 has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:border-sky-700 dark:hover:bg-sky-950/20 dark:has-[:checked]:border-sky-500 dark:has-[:checked]:bg-sky-950/40">
                        <input type="radio" name="bulk_operation" value="make-employee" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                            <i class="ri-briefcase-4-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Make employees</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Convert users without an employee record into employees.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- View employees --}}
                    <label data-bulk-op-option data-operation="view-employees"
                        class="group flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-sky-300 hover:bg-sky-50/40 has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:has-[:checked]:bg-sky-950/40">
                        <input type="radio" name="bulk_operation" value="view-employees" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                            <i class="ri-eye-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">View employees</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Open the filtered employee view.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- Export selected --}}
                    <label data-bulk-op-option data-operation="export"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 transition-all hover:border-indigo-300 hover:bg-indigo-50/40 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/20 dark:has-[:checked]:border-indigo-500 dark:has-[:checked]:bg-indigo-950/40">
                        <input type="radio" name="bulk_operation" value="export" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                            <i class="ri-download-2-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Export selected</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Download a CSV file with the selected users.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- Separator --}}
                    <div class="my-1 flex items-center gap-2">
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-red-500">
                            Dangerous
                        </span>
                        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    {{-- Delete --}}
                    <label data-bulk-op-option data-operation="delete"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-red-200 bg-white p-3 transition-all hover:border-red-300 hover:bg-red-50 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:shadow-sm dark:border-red-900 dark:bg-gray-800 dark:hover:border-red-700 dark:hover:bg-red-950/20 dark:has-[:checked]:border-red-500 dark:has-[:checked]:bg-red-950/40">
                        <input type="radio" name="bulk_operation" value="delete" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-300">
                            <i class="ri-delete-bin-6-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Delete</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Soft-delete the selected users. Cannot be undone from here.
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>

                    {{-- Force delete --}}
                    <label data-bulk-op-option data-operation="force-delete"
                           class="group flex cursor-pointer items-start gap-3 rounded-xl border border-red-300 bg-white p-3 transition-all hover:border-red-400 hover:bg-red-50 has-[:checked]:border-red-600 has-[:checked]:bg-red-100 has-[:checked]:shadow-sm dark:border-red-800 dark:bg-gray-800 dark:has-[:checked]:border-red-600 dark:has-[:checked]:bg-red-950/40">
                        <input type="radio" name="bulk_operation" value="force-delete" class="sr-only peer">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-200 text-red-700 dark:bg-red-950/80 dark:text-red-200">
                            <i class="ri-delete-bin-7-line text-base"></i>
                        </span>
                        <span class="flex-1">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-100">Force delete</span>
                            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                Permanently remove the selected users. <strong>Irreversible.</strong>
                            </span>
                        </span>
                        <span class="hidden h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-700 text-white peer-checked:flex">
                            <i class="ri-check-line text-xs"></i>
                        </span>
                    </label>
                </div>
            </fieldset>

            {{-- Warning box --}}
            <div id="bulkOpsWarning"
                 class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-medium text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <i class="ri-alert-line mr-1"></i>
                <span id="bulkOpsWarningText">This operation cannot be undone.</span>
            </div>

            {{-- Error --}}
            <div id="bulkOpsError"
                 class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-medium text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"></div>
        </div>

        {{-- Footer --}}
        <footer class="flex items-center justify-end gap-2 border-t border-gray-200 px-5 py-4 dark:border-gray-700">
            <button type="button" id="bulkOpsCancel"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Cancel
            </button>

            <button type="button" id="bulkOpsApply" disabled
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-500/40 active:translate-y-0 disabled:pointer-events-none disabled:opacity-50">
                <i class="ri-play-circle-line text-base"></i>
                <span>Run on <span id="bulkOpsApplyCount">0</span></span>
            </button>
        </footer>
    </aside>
</div>