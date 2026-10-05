@extends('admin.layout.main')

@push('dashboard_style')
<style>
    /* ══════════════════════════════════════════════════════════════
       DataTables + Tailwind alignment
       ══════════════════════════════════════════════════════════════ */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.3);
    }
    .dark .dataTables_wrapper .dataTables_length select,
    .dark .dataTables_wrapper .dataTables_filter input {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #fff;
    }

    /* ══════════════════════════════════════════════════════════════
       Collapsible filter panel
       ══════════════════════════════════════════════════════════════ */
    .filter-panel {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.4s ease, opacity 0.3s ease;
    }
    .filter-panel.is-open {
        max-height: 900px;
        opacity: 1;
    }

    /* ══════════════════════════════════════════════════════════════
       Radio pill
       ══════════════════════════════════════════════════════════════ */
    .criteria-radio:checked + label {
        background-color: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 10px rgba(79,70,229,0.3);
    }
    .criteria-radio + label { transition: all 0.15s ease; }
    .criteria-radio { position: absolute; opacity: 0; pointer-events: none; }

    /* ══════════════════════════════════════════════════════════════
       Toolbar — perfect vertical alignment (force all to 42px)
       ══════════════════════════════════════════════════════════════ */
    .toolbar-row {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        gap: 0.5rem;
    }
    .toolbar-row > * { flex-shrink: 0; }
    .toolbar-row > form { flex: 1 1 auto; min-width: 260px; }

    /* Every toolbar control is forced to the same height */
    .toolbar-btn,
    .toolbar-row .btn-loading,
    .toolbar-row button[type="submit"],
    .toolbar-row > button,
    .toolbar-row > a,
    .toolbar-row > div > button {
        height: 42px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem;
        line-height: 1;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .toolbar-input,
    .toolbar-row input[type="search"] {
        height: 42px !important;
        line-height: 1;
    }

    /* Ensure icons inside toolbar controls render at a consistent size */
    .toolbar-row i[class^="ri-"],
    .toolbar-row i[class*=" ri-"] {
        font-size: 1.125rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ══════════════════════════════════════════════════════════════
       Modal animations
       ══════════════════════════════════════════════════════════════ */
    .modal-backdrop {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease;
    }
    .modal-card { animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(24px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .badge-gradient {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }
</style>
@endpush

@section('dashboard_content')

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
     ══════════════════════════════════════════════════════════════ --}}
<header class="mb-5 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-4 dark:border-gray-700">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Users</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage all user accounts in the system</p>
    </div>

    <a href="{{ route('users.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0">
        <i class="ri-add-line text-lg"></i>
        <span>Create User</span>
    </a>
</header>

{{-- ══════════════════════════════════════════════════════════════
     TOOLBAR
     ══════════════════════════════════════════════════════════════ --}}
<div class="toolbar-row mb-4">

    {{-- 1. Filter toggle --}}
    <button type="button"
            id="toggleFilterBtn"
            aria-expanded="false"
            aria-controls="filterPanel"
            class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
        <i class="ri-filter-3-line text-lg"></i>
        <span>Filter</span>
        <i id="filterChevron" class="ri-arrow-down-s-line text-base transition-transform duration-300"></i>
    </button>

    {{-- 2. Search form --}}
    <form action="{{ route('users.index') }}"
          method="GET"
          id="mainSearchForm"
          class="toolbar-row">

        @foreach (['name','email','date','dob','status','updated_from','updated_to','criteria'] as $param)
            @if (request($param))
                <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
            @endif
        @endforeach

        {{-- Search input --}}
        <div class="relative flex-1 min-w-[200px]">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="ri-search-line text-lg"></i>
            </span>
            <input type="search"
                   name="search"
                   id="search"
                   value="{{ request('search') }}"
                   placeholder="Search users…"
                   class="toolbar-input block w-full rounded-xl border border-gray-300 bg-white pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
        </div>

        {{-- Search button --}}
        @include('components.loading.button', [
            'id'      => 'searchBtn',
            'label'   => 'Search',
            'loading' => 'Searching…',
            'type'    => 'submit',
            'variant' => 'primary',
            'icon'    => 'ri-search-line',
            'class'   => 'toolbar-btn min-w-[110px]',
        ])

        @if (request()->hasAny(['search','name','email','date','dob','status','updated_from','updated_to','criteria']))
            <a href="{{ route('users.index') }}"
               class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="ri-close-line text-lg"></i>
                <span class="hidden sm:inline">Clear</span>
            </a>
        @endif
    </form>

    {{-- 3. Download dropdown --}}
    <div class="relative" id="downloadWrap">
        <button type="button"
                id="downloadToggle"
                class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <i class="ri-download-2-line text-lg text-indigo-500"></i>
            <span class="hidden md:inline">Download</span>
            <i class="ri-arrow-down-s-line text-base text-gray-400"></i>
        </button>

        <div id="downloadMenu"
             class="absolute right-0 z-20 mt-2 hidden w-56 origin-top-right rounded-xl border border-gray-200 bg-white p-1.5 shadow-xl dark:border-gray-700 dark:bg-gray-800">

            @foreach ($downloadOptions as $dl)
                @if ($dl['format'] === 'print')
                    {{-- Print uses JS --}}
                    <button type="button"
                            onclick="window.print()"
                            class="no-loader flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="{{ $dl['icon'] }} {{ $dl['color'] }} w-4 text-center text-lg"></i>
                        <span>{{ $dl['label'] }}</span>
                    </button>
                @else
                    {{-- Download links --}}
                    <a href="{{ route('users.export', array_merge(request()->query(), ['format' => $dl['format']])) }}"
                       data-download
                       data-loading-text="Preparing {{ strtoupper($dl['format']) }}…"
                       class="download-item no-loader flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="{{ $dl['icon'] }} {{ $dl['color'] }} w-4 text-center text-lg"></i>
                        <span class="download-label">{{ $dl['label'] }}</span>
                        <span class="spinner-btn ml-auto hidden">
                            <svg class="h-4 w-4 animate-spin text-indigo-600" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/>
                                <path fill="currentColor" class="opacity-90"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     COLLAPSIBLE FILTER PANEL
     ══════════════════════════════════════════════════════════════ --}}
<div id="filterPanel" class="filter-panel">
    <form action="{{ route('users.index') }}"
          method="GET"
          id="filterForm"
          data-loading="Applying filters…"
          class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        @if (request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                    <i class="ri-filter-3-line text-lg"></i>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Advanced Filters</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Choose a criterion and refine your search</p>
                </div>
            </div>
            <button type="button" id="closeFilterBtn"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        <div class="mb-4">
            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Search Criteria</p>
            <div class="flex flex-wrap gap-2">
                @php
                    $criteria = request('criteria', 'name');
                    $criterias = ['name' => 'Name', 'date' => 'Date', 'dob' => 'DOB', 'status' => 'Status', 'timestamp' => 'Timestamp'];
                @endphp
                @foreach ($criterias as $key => $label)
                    <div class="relative">
                        <input type="radio" name="criteria" id="criteria_{{ $key }}" value="{{ $key }}"
                               class="criteria-radio" {{ $criteria === $key ? 'checked' : '' }}>
                        <label for="criteria_{{ $key }}"
                               class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:border-indigo-500 dark:hover:text-indigo-300">
                            {{ $label }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="criteria-field" data-criteria="name">
                <label for="filter_name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Full Name / Username</label>
                <input type="text" name="name" id="filter_name" value="{{ request('name') }}" placeholder="e.g. John Doe"
                       class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="criteria-field" data-criteria="date">
                <label for="filter_date" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Specific Date</label>
                <input type="date" name="date" id="filter_date" value="{{ request('date') }}"
                       class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="criteria-field" data-criteria="dob">
                <label for="filter_dob" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Date of Birth</label>
                <input type="date" name="dob" id="filter_dob" value="{{ request('dob') }}"
                       class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="criteria-field" data-criteria="status">
                <label for="filter_status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</label>
                <select name="status" id="filter_status"
                        class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All statuses</option>
                    @foreach (['active','inactive','pending','banned'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="criteria-field md:col-span-2" data-criteria="timestamp">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Timestamp Range (Updated)</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <input type="datetime-local" name="updated_from" value="{{ request('updated_from') }}"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <input type="datetime-local" name="updated_to" value="{{ request('updated_to') }}"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
            <div class="flex items-center gap-2">
                @include('components.loading.button', [
                    'id'      => 'applyFiltersBtn',
                    'label'   => 'Apply Filters',
                    'loading' => 'Applying…',
                    'type'    => 'submit',
                    'variant' => 'indigo',
                    'icon'    => 'ri-filter-3-fill',
                ])
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <i class="ri-arrow-go-back-line text-lg"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TABLE CARD
     ══════════════════════════════════════════════════════════════ --}}
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table id="userTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    @foreach ($columns as $key => $label)
                        @php $align = $key === 'actions' ? 'text-right' : 'text-left'; @endphp
                        <th class="px-4 py-3 {{ $align }} text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ $label }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">
                        <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold uppercase text-white">
                                    {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                                </span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->username }}</span>
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ optional($user->profile)->first_name }} {{ optional($user->profile)->last_name }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                            <a href="mailto:{{ $user->email }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $user->email }}</a>
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            @php
                                $rawStatus = $user->status_label ?? 'active';
                                if (is_object($rawStatus) && property_exists($rawStatus, 'value')) {
                                    $status = $rawStatus->value;
                                } elseif (is_object($rawStatus) && method_exists($rawStatus, '__toString')) {
                                    $status = (string) $rawStatus;
                                } elseif (is_array($rawStatus)) {
                                    $status = $rawStatus['name'] ?? $rawStatus['value'] ?? 'active';
                                } else {
                                    $status = (string) $rawStatus;
                                }
                                $status = strtolower($status);
                                $statusClasses = [
                                    'active'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
                                    'inactive' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                    'pending'  => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
                                    'banned'   => 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-300',
                                ];
                                $statusClass = $statusClasses[$status] ?? $statusClasses['active'];
                            @endphp
                            <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->roles->first()?->name ?? 'User' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                            <span title="{{ $user->updated_at }}">{{ $user->updated_at->diffForHumans() }}</span>
                        </td>

                        {{-- ══════════════════════════════════════════════════════
                             ACTIONS — pulled from $actionButtons[$user->id]
                             ══════════════════════════════════════════════════════ --}}
                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                @php $actions = $actionButtons[$user->id] ?? []; @endphp

                                {{-- View Profile --}}
                                @isset($actions['view_profile'])
                                    <button type="button"
                                            title="{{ $actions['view_profile']['label'] }}"
                                            onclick="openProfile({{ $user->id }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['view_profile']['color'] }} transition-colors hover:bg-slate-100 dark:hover:bg-slate-800/40">
                                        <i class="{{ $actions['view_profile']['icon'] }} text-base"></i>
                                    </button>
                                @endisset

                                {{-- Make Employee --}}
                                @isset($actions['make_employee'])
                                    <button type="button"
                                            title="{{ $actions['make_employee']['label'] }}"
                                            onclick="makeEmployee({{ $user->id }}, '{{ addslashes($user->username) }}')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['make_employee']['color'] }} transition-colors hover:bg-emerald-100 dark:hover:bg-emerald-950/40">
                                        <i class="{{ $actions['make_employee']['icon'] }} text-base"></i>
                                    </button>
                                @endisset

                                {{-- Edit --}}
                                @isset($actions['edit'])
                                    <a href="{{ $actions['edit']['route'] }}"
                                       title="{{ $actions['edit']['label'] }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['edit']['color'] }} transition-colors hover:bg-indigo-100 dark:hover:bg-indigo-950/40">
                                        <i class="{{ $actions['edit']['icon'] }} text-base"></i>
                                    </a>
                                @endisset

                                {{-- Delete --}}
                                @isset($actions['delete'])
                                    <button type="button"
                                            title="{{ $actions['delete']['label'] }}"
                                            onclick="deleteData({{ $user->id }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['delete']['color'] }} transition-colors hover:bg-red-100 dark:hover:bg-red-950/40">
                                        <i class="{{ $actions['delete']['icon'] }} text-base"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}"
                                          action="{{ $actions['delete']['route'] }}"
                                          method="POST"
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endisset
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-4 py-14 text-center">
                            <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                    <i class="ri-user-search-line text-3xl"></i>
                                </span>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No users found</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Try adjusting filters or create a new user.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Result count --}}
<p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
    Showing
    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->firstItem() ?? 0 }}</span>
    to
    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->lastItem() ?? 0 }}</span>
    of
    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->total() }}</span>
    users
</p>


{{-- ══════════════════════════════════════════════════════════════
     PROFILE MODAL — Modern Design
     ══════════════════════════════════════════════════════════════ --}}
@include('admin.pages.Users.profile_view_modal')

{{-- ══════════════════════════════════════════════════════════════
     MAKE-EMPLOYEE MODAL
     ══════════════════════════════════════════════════════════════ --}}
@include('admin.pages.Users.make_employee_modal')




{{-- ══════════════════════════════════════════════════════════════
     HIDDEN USER DATA
     ══════════════════════════════════════════════════════════════ --}}
<script id="usersData" type="application/json">
{!! json_encode(
    $users->map(function ($u) {
        return [
            'id'         => $u->id,
            'username'   => $u->username,
            'email'      => $u->email,
            'first_name' => optional($u->profile)->first_name,
            'last_name'  => optional($u->profile)->last_name,
            'phone'      => optional($u->profile)->phone ?? null,
            'address'    => optional($u->profile)->address ?? null,
            'birthdate'  => optional($u->profile)->birthdate ?? null,
            'employee'   => $u->employee ? [
                'department' => optional($u->employee->department)->name,
                'hire_date'  => $u->employee->date_hired,
                'status'     => is_object($u->employee->status) ? ($u->employee->status->value ?? null) : $u->employee->status,
            ] : null,
            'edit_url'     => route('users.edit', $u->id),
            'make_emp_url' => route('users.make-employee', $u->id),
        ];
    })->keyBy('id'),
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
) !!}
</script>

@endsection

{{-- ══════════════════════════════════════════════════════════════
     PAGE SCRIPTS
     ══════════════════════════════════════════════════════════════ --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. SEARCH BUTTON ──
    const searchForm = document.getElementById('mainSearchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function () {
            if (typeof window.setButtonLoading === 'function') {
                window.setButtonLoading('searchBtn', true);
            }
        });
    }

    // ── 2. DOWNLOADS ──
    document.querySelectorAll('[data-download]').forEach(item => {
        item.addEventListener('click', function () {
            const spinner  = this.querySelector('.spinner-btn');
            const label    = this.querySelector('.download-label');
            const original = label.textContent;

            spinner.classList.remove('hidden');
            label.textContent = 'Downloading…';
            if (typeof window.showLoader === 'function') {
                window.showLoader(this.dataset.loadingText || 'Preparing download…');
            }

            setTimeout(() => {
                if (typeof window.hideLoader === 'function') window.hideLoader();
                spinner.classList.add('hidden');
                label.textContent = original;
            }, 2500);
        });
    });

    // ── 3. FILTER PANEL toggle ──
    const filterPanel = document.getElementById('filterPanel');
    const toggleBtn   = document.getElementById('toggleFilterBtn');
    const closeBtn    = document.getElementById('closeFilterBtn');
    const chevron     = document.getElementById('filterChevron');

    function openFilter() {
        filterPanel.classList.add('is-open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
    function closeFilter() {
        filterPanel.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
    toggleBtn.addEventListener('click', () =>
        filterPanel.classList.contains('is-open') ? closeFilter() : openFilter()
    );
    closeBtn.addEventListener('click', closeFilter);

    @if (request()->hasAny(['name','email','date','dob','status','updated_from','updated_to']))
        openFilter();
    @endif

    // ── 4. CRITERIA radio ──
    const radios = document.querySelectorAll('.criteria-radio');
    const fields = document.querySelectorAll('.criteria-field');

    function syncCriteriaFields() {
        const selected = document.querySelector('.criteria-radio:checked')?.value || 'name';
        fields.forEach(f => {
            f.style.display = (f.dataset.criteria === selected) ? '' : 'none';
        });
    }
    radios.forEach(r => r.addEventListener('change', syncCriteriaFields));
    syncCriteriaFields();

    // ── 5. DOWNLOAD DROPDOWN ──
    const dlToggle = document.getElementById('downloadToggle');
    const dlMenu   = document.getElementById('downloadMenu');
    const dlWrap   = document.getElementById('downloadWrap');

    dlToggle.addEventListener('click', e => {
        e.stopPropagation();
        dlMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', e => {
        if (!dlWrap.contains(e.target)) dlMenu.classList.add('hidden');
    });

    // ── 6. DELETE ──
    window.deleteData = function (id) {
        if (!confirm('Delete this user? This action cannot be undone.')) return;
        if (typeof window.showLoader === 'function') window.showLoader('Deleting user…');
        document.getElementById('delete-form-' + id).submit();
    };

    // ── 7. PROFILE MODAL ──
    const usersData    = JSON.parse(document.getElementById('usersData').textContent || '{}');
    const profileModal = document.getElementById('profileModal');

    window.openProfile = function (id) {
        const user = usersData[id];
        if (!user) return;

        document.getElementById('profileLoading').classList.remove('hidden');
        document.getElementById('profileContent').classList.add('hidden');

        profileModal.classList.remove('hidden');
        profileModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            const initials = (user.username || 'U').substring(0, 2).toUpperCase();
            document.getElementById('profileAvatar').textContent = initials;

            const fullName = [user.first_name, user.last_name].filter(Boolean).join(' ') || user.username;
            document.getElementById('profileName').textContent     = fullName;
            document.getElementById('profileUsername').textContent = '@' + user.username;

            document.getElementById('profileEmail').textContent   = user.email   || '—';
            document.getElementById('profilePhone').textContent   = user.phone   || '—';
            document.getElementById('profileAddress').textContent = user.address || '—';

            const empSection = document.getElementById('employeeSection');
            if (user.employee) {
                empSection.classList.remove('hidden');
                document.getElementById('profileDepartment').textContent = user.employee.department || '—';
                document.getElementById('profileHireDate').textContent   = user.employee.hire_date  || '—';
                document.getElementById('profileBirthdate').textContent  = user.birthdate           || '—';
                document.getElementById('profileEmpStatus').textContent  = user.employee.status
                    ? user.employee.status.charAt(0).toUpperCase() + user.employee.status.slice(1)
                    : '—';
            } else {
                empSection.classList.add('hidden');
            }

            document.getElementById('profileEditLink').href = user.edit_url;

            document.getElementById('profileLoading').classList.add('hidden');
            document.getElementById('profileContent').classList.remove('hidden');
        }, 250);
    };

    window.closeProfile = function () {
        profileModal.classList.add('hidden');
        profileModal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    profileModal.addEventListener('click', e => {
        if (e.target === profileModal) closeProfile();
    });

    // ── 8. MAKE EMPLOYEE modal ──
    const makeEmpModal = document.getElementById('makeEmployeeModal');
    const makeEmpForm  = document.getElementById('makeEmployeeForm');
    const makeEmpName  = document.getElementById('makeEmployeeName');

    window.makeEmployee = function (id, username) {
        const user = usersData[id];
        if (!user) return;
        makeEmpName.textContent = username;
        makeEmpForm.action = user.make_emp_url;
        makeEmpModal.classList.remove('hidden');
        makeEmpModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closeMakeEmployee = function () {
        makeEmpModal.classList.add('hidden');
        makeEmpModal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    makeEmpModal.addEventListener('click', e => {
        if (e.target === makeEmpModal) closeMakeEmployee();
    });

    makeEmpForm.addEventListener('submit', function () {
        if (typeof window.setButtonLoading === 'function') {
            window.setButtonLoading('makeEmployeeSubmit', true);
        }
        if (typeof window.showLoader === 'function') {
            window.showLoader('Creating employee record…');
        }
    });

    // ── 9. ESC ──
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeFilter();
            closeProfile();
            closeMakeEmployee();
        }
    });
});
</script>
