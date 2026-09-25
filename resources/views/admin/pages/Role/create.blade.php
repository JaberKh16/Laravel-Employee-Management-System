@extends('admin.layout.main')

@push('dashboard_style')
    {{-- (empty — reserved for page-specific styles) --}}
@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-4xl">

        {{-- ============================================================
             PAGE HEADER
             ============================================================ --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ isset($role) ? 'Edit Role' : 'Create Role' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ isset($role)
                        ? 'Update the role name and its permissions'
                        : 'Define a new role and assign permissions' }}
                </p>
            </div>

            <a href="{{ route('roles.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Back</span>
            </a>
        </header>

        {{-- ============================================================
             FORM CARD
             ============================================================ --}}
        <form method="POST"
              action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @csrf
            @isset($role)
                @method('PUT')
            @endisset

            {{-- Card header strip --}}
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Role Details
                </h2>
            </div>

            {{-- Card body --}}
            <div class="space-y-6 p-6">

                {{-- ============================================================
                     ROLE NAME
                     ============================================================ --}}
                <div>
                    <label for="name"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Role Name <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name', $role->name ?? '') }}"
                           autocomplete="name"
                           autofocus
                           placeholder="e.g. Administrator"
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                  @error('name') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                  @enderror">

                    @error('name')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                {{-- ============================================================
                     PERMISSIONS
                     ============================================================ --}}
                <div>
                    {{-- Header row with label + Select All --}}
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Permissions
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Choose what this role can do
                                </p>
                            </div>
                        </div>

                        {{-- Select All toggle --}}
                        <label class="inline-flex cursor-pointer select-none items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            <input type="checkbox"
                                   id="select-all"
                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900">
                            <span>Select All</span>
                        </label>
                    </div>

                    {{-- Error for permissions --}}
                    @error('permissions')
                        <p class="mb-3 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    {{-- Permission checkbox grid (4 per row on md+, 2 on sm, 1 on mobile) --}}
                    @if ($permissions->isEmpty())
                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center dark:border-gray-600 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                No permissions have been defined yet.
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($permissions->chunk(4) as $chunks)
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-4">
                                    @foreach ($chunks as $permission)
                                        @php
                                            $isChecked = isset($role)
                                                && $rolepermissions->contains('id', $permission->id);
                                            $isChecked = $isChecked || in_array($permission->id, old('permissions', []));
                                        @endphp

                                        <label for="permission-{{ $permission->id }}"
                                               class="group flex cursor-pointer select-none items-center gap-2.5 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm transition-all hover:border-indigo-400 hover:bg-indigo-50/50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-indigo-500 dark:hover:bg-indigo-950/30">
                                            <input type="checkbox"
                                                   id="permission-{{ $permission->id }}"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   @if ($isChecked) checked @endif
                                                   class="permission-checkbox h-4 w-4 shrink-0 rounded border-gray-300 text-indigo-600 transition-colors focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-600 dark:bg-gray-800">
                                            <span class="truncate font-medium" title="{{ $permission->name }}">
                                                {{ $permission->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- ============================================================
                 CARD FOOTER
                 ============================================================ --}}
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <a href="{{ route('roles.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span>{{ isset($role) ? 'Update' : 'Add New' }}</span>
                </button>
            </div>

        </form>

    </div>
@endsection

@push('dashboard_script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAll      = document.getElementById('select-all');
            const permissionBoxes = document.querySelectorAll('.permission-checkbox');

            // ---------- Select All toggle ----------
            selectAll?.addEventListener('change', function () {
                permissionBoxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                syncSelectAllState();
            });

            // ---------- Keep Select All in sync ----------
            function syncSelectAllState() {
                if (! selectAll) return;

                const total    = permissionBoxes.length;
                const checked  = [...permissionBoxes].filter(cb => cb.checked).length;

                selectAll.checked       = total > 0 && checked === total;
                selectAll.indeterminate = checked > 0 && checked < total;
            }

            // Sync on page load (in case some permissions are pre-checked)
            syncSelectAllState();

            // Update whenever any permission checkbox changes
            permissionBoxes.forEach(cb => {
                cb.addEventListener('change', syncSelectAllState);
            });
        });
    </script>
@endpush