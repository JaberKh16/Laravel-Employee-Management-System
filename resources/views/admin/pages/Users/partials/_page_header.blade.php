<header class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-4 dark:border-gray-700">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Users</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage all user accounts in the system</p>
    </div>

    <div class="flex flex-wrap items-center gap-2">

        {{-- ── STATUS FILTER ─────────────────────────────────── --}}
        @php
            $activeStatus = request('status');
            $activeStatusMeta = $activeStatus !== null
                ? ($statusOptions[(int) $activeStatus] ?? null)
                : null;
        @endphp

        <div class="relative" data-dropdown>
            <button type="button" data-dropdown-trigger aria-expanded="false" aria-haspopup="true"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                @if ($activeStatusMeta)
                    <span class="h-2 w-2 rounded-full {{ $activeStatusMeta['dot'] }}"></span>
                    <span>Status: {{ $activeStatusMeta['label'] }}</span>
                @else
                    <i class="ri-filter-3-line text-base opacity-70"></i>
                    <span>Status</span>
                @endif
                <svg class="h-3.5 w-3.5 opacity-60 transition-transform" data-dropdown-chevron
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div data-dropdown-menu
                 class="absolute right-0 top-full z-40 mt-2 hidden w-52 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-xl shadow-gray-900/10 ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">
                <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}"
                   class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-500 transition-colors hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/60">
                    <span class="h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                    <span class="flex-1">All statuses</span>
                    @if ($activeStatus === null)
                        <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @endif
                </a>

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                @foreach ($statusOptions as $option)
                    @php 
                        $isActive = (string) $activeStatus === (string) $option['value']; 
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['status' => $option['value'], 'page' => null]) }}"
                       class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60 {{ $isActive ? 'bg-indigo-50/70 dark:bg-indigo-950/40' : '' }}">
                        <span class="h-2 w-2 shrink-0 rounded-full {{ $option['dot'] }}"></span>
                        <span class="flex-1">{{ $option['label'] }}</span>
                        @if ($isActive)
                            <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── ROLE FILTER ───────────────────────────────────── --}}
        @php
            $activeRole = request('role');
            $roleDotColors = [
                'super-admin' => 'bg-purple-500',
                'admin'       => 'bg-indigo-500',
                'manager'     => 'bg-sky-500',
                'employee'    => 'bg-emerald-500',
                'user'        => 'bg-gray-400',
            ];
        @endphp

        <div class="relative" data-dropdown>
            <button type="button" data-dropdown-trigger aria-expanded="false" aria-haspopup="true"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                @if ($activeRole)
                    <span class="h-2 w-2 rounded-full {{ $roleDotColors[strtolower($activeRole)] ?? 'bg-slate-400' }}"></span>
                    <span class="capitalize">Role: {{ $activeRole }}</span>
                @else
                    <i class="ri-shield-user-line text-base opacity-70"></i>
                    <span>Role</span>
                @endif
                <svg class="h-3.5 w-3.5 opacity-60 transition-transform" data-dropdown-chevron
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div data-dropdown-menu
                 class="absolute right-0 top-full z-40 mt-2 hidden w-52 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-xl shadow-gray-900/10 ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">
                <a href="{{ request()->fullUrlWithQuery(['role' => null, 'page' => null]) }}"
                   class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-500 transition-colors hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/60">
                    <span class="h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                    <span class="flex-1">All roles</span>
                    @if (! $activeRole)
                        <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @endif
                </a>

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                @foreach ($roles as $role)
                    @php
                        $isActive = $activeRole === $role->name;
                        $dot = $roleDotColors[strtolower($role->name)] ?? 'bg-slate-400';
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['role' => $role->name, 'page' => null]) }}"
                       class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 capitalize transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60 {{ $isActive ? 'bg-indigo-50/70 dark:bg-indigo-950/40' : '' }}">
                        <span class="h-2 w-2 shrink-0 rounded-full {{ $dot }}"></span>
                        <span class="flex-1">{{ $role->name }}</span>
                        @if ($isActive)
                            <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             SINGLE ACTIONS DROPDOWN — all bulk operations
             ══════════════════════════════════════════════════════ --}}
        <div class="relative" data-dropdown>
            <button type="button" data-dropdown-trigger aria-expanded="false" aria-haspopup="true"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="ri-flashlight-line text-base opacity-70"></i>
                <span>Bulk Actions</span>
                <svg class="h-3.5 w-3.5 opacity-60 transition-transform" data-dropdown-chevron
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div data-dropdown-menu
                 class="absolute right-0 top-full z-40 mt-2 hidden w-64 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-xl shadow-gray-900/10 ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">

                {{-- ═══════ STATUS ═══════ --}}
                <div class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Status
                </div>

                <button type="button" data-open-bulk-status
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-gray-200 dark:hover:bg-indigo-950/40 dark:hover:text-indigo-300">
                    <i class="ri-edit-circle-line text-base text-indigo-500"></i>
                    <span class="flex-1">Change status…</span>
                </button>

                <button type="button" data-open-bulk-role
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-purple-50 hover:text-purple-700 dark:text-gray-200 dark:hover:bg-purple-950/40 dark:hover:text-purple-300">
                    <i class="ri-shield-user-line text-base text-purple-500"></i>
                    <span class="flex-1">Change role</span>
                </button>

                <button type="button" data-bulk-operation="activate"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-emerald-50 hover:text-emerald-700 dark:text-gray-200 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-300">
                    <i class="ri-checkbox-circle-line text-base text-emerald-500"></i>
                    <span class="flex-1">Set Active</span>
                </button>

                <button type="button" data-bulk-operation="deactivate"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700/60">
                    <i class="ri-forbid-2-line text-base text-gray-500"></i>
                    <span class="flex-1">Set Inactive</span>
                </button>

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                {{-- ═══════ EMPLOYEE ═══════ --}}
                <div class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Employee
                </div>

                <button type="button" data-bulk-operation="make-employee"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-sky-50 hover:text-sky-700 dark:text-gray-200 dark:hover:bg-sky-950/40 dark:hover:text-sky-300">
                    <i class="ri-briefcase-4-line text-base text-sky-500"></i>
                    <span class="flex-1">Make employees</span>
                </button>

                <button type="button" data-bulk-operation="view-employees"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-sky-50 hover:text-sky-700 dark:text-gray-200 dark:hover:bg-sky-950/40 dark:hover:text-sky-300">
                    <i class="ri-eye-line text-base text-sky-600"></i>
                    <span class="flex-1">View employees</span>
                </button>

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                {{-- ═══════ DATA ═══════ --}}
                <div class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Data
                </div>

                <button type="button" data-bulk-operation="export"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-gray-200 dark:hover:bg-indigo-950/40 dark:hover:text-indigo-300">
                    <i class="ri-download-2-line text-base text-indigo-500"></i>
                    <span class="flex-1">Export selected</span>
                </button>

                @if (\Illuminate\Support\Facades\Route::has('users.import'))
                    <a href="{{ route('users.import') }}"
                       class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60">
                        <i class="ri-upload-2-line text-base text-sky-500"></i>
                        <span class="flex-1">Import users</span>
                    </a>
                @endif

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                {{-- ═══════ DANGEROUS ═══════ --}}
                <div class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-red-500">
                    Dangerous
                </div>

                <button type="button" data-bulk-operation="delete"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40">
                    <i class="ri-delete-bin-6-line text-base"></i>
                    <span class="flex-1">Delete selected</span>
                </button>

                <button type="button" data-bulk-operation="force-delete"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-red-700 transition-colors hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/40">
                    <i class="ri-delete-bin-7-line text-base"></i>
                    <span class="flex-1">Force delete selected</span>
                </button>

                <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                {{-- ═══════ VIEW ═══════ --}}
                <button type="button" data-action="refresh"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60">
                    <i class="ri-refresh-line text-base text-gray-500"></i>
                    <span class="flex-1">Refresh list</span>
                </button>
            </div>
        </div>

        {{-- ── IMPORT LINK ───────────────────────────────────── --}}
        @if (\Illuminate\Support\Facades\Route::has('users.import'))
            <a href="{{ route('users.import') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="ri-upload-2-line text-base"></i>
                <span>Import</span>
            </a>
        @endif

        {{-- ── CREATE USER ───────────────────────────────────── --}}
        {{-- <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0">
            <i class="ri-add-line text-lg"></i>
            <span>Create User</span>
        </a> --}}

        {{-- ── CREATE USER (opens modal in-place) ───────────── --}}
        <button type="button" onclick="window.openCreateUserModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:brightness-105">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span>Create User</span>
        </button>
    </div>
</header>