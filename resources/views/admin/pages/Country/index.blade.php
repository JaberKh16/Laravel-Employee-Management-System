@extends('admin.layout.main')

@push('dashboard_style')
<style>
    /* Smooth collapse animation for filter panel */
    .filter-panel {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.35s ease, opacity 0.3s ease;
    }
    .filter-panel.is-open {
        max-height: 900px;
        opacity: 1;
    }

    /* Radio pill styling */
    .criteria-radio:checked + label {
        background-color: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 10px rgba(79,70,229,0.3);
    }
    .criteria-radio + label {
        transition: all 0.15s ease;
    }
    .criteria-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
</style>
@endpush

@section('dashboard_content')

<div class="mx-auto max-w-6xl">

    {{-- ============================================================
         PAGE HEADER
         ============================================================ --}}
    <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Countries
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage all countries in the system
            </p>
        </div>

        <a href="{{ route('countries.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span>Create Country</span>
        </a>
    </header>

    {{-- ============================================================
         TOOLBAR: Filter toggle | Search | Clear | Download
         ============================================================ --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">

        {{-- Filter toggle --}}
        <button type="button"
                id="toggleFilterBtn"
                aria-expanded="false"
                aria-controls="filterPanel"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
            </svg>
            <span>Filter</span>
            <svg id="filterChevron" class="h-3.5 w-3.5 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>

        {{-- Main search --}}
        <form action="{{ route('countries.index') }}" method="GET"
              class="flex flex-1 min-w-[260px] items-center gap-2">

            {{-- Preserve advanced filters when searching --}}
            @foreach (['name','code','status','created_from','created_to','sort','direction'] as $param)
                @if (request($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                @endif
            @endforeach

            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="search"
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search countries…"
                       class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <span class="hidden sm:inline">Search</span>
            </button>

            @if (request()->hasAny(['search','name','code','status','created_from','created_to']))
                <a href="{{ route('countries.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="hidden sm:inline">Clear</span>
                </a>
            @endif
        </form>

        {{-- Download dropdown --}}
        <div class="relative" id="downloadWrap">
            <button type="button" id="downloadToggle"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                <span class="hidden md:inline">Download</span>
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>

            <div id="downloadMenu"
                 class="absolute right-0 z-20 mt-2 hidden w-44 rounded-xl border border-gray-200 bg-white p-1.5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <a href="{{ route('countries.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-csv text-emerald-600"></i> CSV
                </a>
                <a href="{{ route('countries.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-excel text-green-600"></i> Excel (XLSX)
                </a>
                <a href="{{ route('countries.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-pdf text-red-600"></i> PDF
                </a>
                <a href="{{ route('countries.export', array_merge(request()->query(), ['format' => 'json'])) }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-code text-amber-600"></i> JSON
                </a>
                <div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>
                <button type="button" onclick="window.print()"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-print text-slate-600"></i> Print
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ADVANCED FILTER PANEL
         ============================================================ --}}
    <div id="filterPanel" class="filter-panel">
        <form action="{{ route('countries.index') }}" method="GET" id="filterForm"
              class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Preserve main search --}}
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            {{-- Panel header --}}
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Advanced Filters</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Refine by name, code, status, or date range</p>
                    </div>
                </div>
                <button type="button" id="closeFilterBtn"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Radio criteria --}}
            <div class="mb-4">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Search Criteria</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $activeCriteria = request('criteria', 'name');
                        $criterias = [
                            'name'      => 'Name',
                            'code'      => 'Country Code',
                            'status'    => 'Status',
                            'created'   => 'Created Date',
                            'sort'      => 'Sorting',
                        ];
                    @endphp
                    @foreach ($criterias as $key => $label)
                        <div class="relative">
                            <input type="radio" name="criteria" id="criteria_{{ $key }}" value="{{ $key }}"
                                   class="criteria-radio" {{ $activeCriteria === $key ? 'checked' : '' }}>
                            <label for="criteria_{{ $key }}"
                                   class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Dynamic fields --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                {{-- NAME --}}
                <div class="criteria-field" data-criteria="name">
                    <label for="filter_name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Country Name</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z"/>
                            </svg>
                        </span>
                        <input type="text" name="name" id="filter_name" value="{{ request('name') }}"
                               placeholder="e.g. Bangladesh"
                               class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                {{-- CODE --}}
                <div class="criteria-field" data-criteria="code">
                    <label for="filter_code" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Country Code</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                            </svg>
                        </span>
                        <input type="text" name="code" id="filter_code" value="{{ request('code') }}"
                               placeholder="e.g. BD, US, IN"
                               maxlength="5"
                               class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 font-mono text-sm uppercase text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="criteria-field" data-criteria="status">
                    <label for="filter_status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <select name="status" id="filter_status"
                                class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All statuses</option>
                            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- CREATED DATE RANGE --}}
                <div class="criteria-field md:col-span-2" data-criteria="created">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Created Date Range</label>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-gray-400">From</span>
                            <input type="date" name="created_from" id="filter_created_from" value="{{ request('created_from') }}"
                                   class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-14 pr-3 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-gray-400">To</span>
                            <input type="date" name="created_to" id="filter_created_to" value="{{ request('created_to') }}"
                                   class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- SORT --}}
                <div class="criteria-field md:col-span-2" data-criteria="sort">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sort Results</label>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25"/>
                                </svg>
                            </span>
                            <select name="sort" id="filter_sort"
                                    class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Default (Newest first)</option>
                                <option value="name"         {{ request('sort') === 'name'         ? 'selected' : '' }}>Name</option>
                                <option value="country_code" {{ request('sort') === 'country_code' ? 'selected' : '' }}>Country Code</option>
                                <option value="created_at"   {{ request('sort') === 'created_at'   ? 'selected' : '' }}>Created Date</option>
                                <option value="updated_at"   {{ request('sort') === 'updated_at'   ? 'selected' : '' }}>Updated Date</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </span>
                        </div>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                                </svg>
                            </span>
                            <select name="direction" id="filter_direction"
                                    class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Descending (Z → A)</option>
                                <option value="asc"  {{ request('direction') === 'asc'           ? 'selected' : '' }}>Ascending (A → Z)</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel actions --}}
            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                        </svg>
                        Apply Filters
                    </button>
                    <a href="{{ route('countries.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        Reset
                    </a>
                </div>

                {{-- Active filter chips --}}
                @if (request()->hasAny(['name','code','status','created_from','created_to','sort']))
                    <div class="flex flex-wrap gap-1.5">
                        @if (request('criteria'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Criterion: {{ ucfirst(request('criteria')) }}
                            </span>
                        @endif
                        @if (request('name'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Name: {{ request('name') }}
                            </span>
                        @endif
                        @if (request('code'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Code: {{ strtoupper(request('code')) }}
                            </span>
                        @endif
                        @if (request('status'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Status: {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                        @if (request('created_from') || request('created_to'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Created: {{ request('created_from', '…') }} → {{ request('created_to', '…') }}
                            </span>
                        @endif
                        @if (request('sort'))
                            <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                Sorted: {{ request('sort') }} ({{ request('direction', 'desc') }})
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </form>
    </div>

    {{-- ============================================================
         TABLE CARD
         ============================================================ --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table id="countryTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="w-16 px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Country Code</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($countries as $country)
                        <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $countries->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-xs font-bold uppercase text-white">
                                        {{ strtoupper(substr($country->country_code ?? $country->name ?? 'C', 0, 2)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $country->name }}
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-4 py-3.5">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 font-mono text-xs font-semibold uppercase tracking-wider text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $country->country_code }}
                                </span>
                            </td>

                           {{-- Status badge --}}
                            <td class="whitespace-nowrap px-4 py-3.5">
                                @php
                                    $rawStatus = $country->status ?? 'active';

                                    // Handle: enum, string, int, array
                                    if (is_object($rawStatus) && method_exists($rawStatus, 'label')) {
                                        $status = strtolower($rawStatus->label());
                                    } elseif (is_object($rawStatus) && property_exists($rawStatus, 'value')) {
                                        $status = strtolower((string) $rawStatus->value);
                                    } elseif (is_object($rawStatus) && method_exists($rawStatus, '__toString')) {
                                        $status = strtolower((string) $rawStatus);
                                    } elseif (is_array($rawStatus)) {
                                        $status = strtolower($rawStatus['name'] ?? $rawStatus['value'] ?? 'active');
                                    } else {
                                        $status = strtolower((string) $rawStatus);
                                    }

                                    // Normalize numeric enum values → labels
                                    if ($status === '1') $status = 'active';
                                    if ($status === '0') $status = 'inactive';

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

                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                {{ optional($country->created_at)->format('M d, Y') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('countries.edit', $country) }}"
                                       title="Edit country"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>

                                    <button type="button"
                                            title="Delete country"
                                            onclick="deleteCountry({{ $country->id }}, @js($country->name))"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>

                                    <form id="delete-form-{{ $country->id }}"
                                          action="{{ route('countries.destroy', $country) }}"
                                          method="POST"
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No countries found</h3>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            @if (request()->hasAny(['search','name','code','status','created_from','created_to']))
                                                No results match the current filters. Try adjusting them.
                                            @else
                                                Get started by creating your first country.
                                            @endif
                                        </p>
                                    </div>
                                    @if (request()->hasAny(['search','name','code','status','created_from','created_to']))
                                        <a href="{{ route('countries.index') }}"
                                           class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                            Clear filters
                                        </a>
                                    @else
                                        <a href="{{ route('countries.create') }}"
                                           class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                            Create Country
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($countries->hasPages())
            <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                {{ $countries->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Result count --}}
    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
        Showing
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $countries->firstItem() ?? 0 }}</span>
        to
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $countries->lastItem() ?? 0 }}</span>
        of
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $countries->total() }}</span>
        countries
    </p>

</div>
@endsection




@push('dashboard_script')
<script>
(function () {
    'use strict';

    function init() {
        // ══════════════════════════════════════════════════════════
        // 1. FILTER PANEL TOGGLE
        // ══════════════════════════════════════════════════════════
        const filterPanel = document.getElementById('filterPanel');
        const toggleBtn   = document.getElementById('toggleFilterBtn');
        const closeBtn    = document.getElementById('closeFilterBtn');
        const chevron     = document.getElementById('filterChevron');

        function openFilter() {
            if (!filterPanel) return;
            filterPanel.classList.add('is-open');
            toggleBtn?.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }

        function closeFilter() {
            if (!filterPanel) return;
            filterPanel.classList.remove('is-open');
            toggleBtn?.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (filterPanel?.classList.contains('is-open')) {
                    closeFilter();
                } else {
                    openFilter();
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                closeFilter();
            });
        }

        // Auto-open if any filter param present
        @if (request()->hasAny(['name','code','status','created_from','created_to','sort','criteria']))
            openFilter();
        @endif

        // ══════════════════════════════════════════════════════════
        // 2. RADIO CRITERIA — show only matching field
        // ══════════════════════════════════════════════════════════
        const radios = document.querySelectorAll('.criteria-radio');
        const fields = document.querySelectorAll('.criteria-field');

        function syncCriteriaFields() {
            const checked = document.querySelector('.criteria-radio:checked');
            const selected = checked ? checked.value : 'name';
            fields.forEach(function (f) {
                f.style.display = (f.dataset.criteria === selected) ? '' : 'none';
            });
        }

        radios.forEach(function (r) {
            r.addEventListener('change', syncCriteriaFields);
        });
        syncCriteriaFields();

        // ══════════════════════════════════════════════════════════
        // 3. DOWNLOAD DROPDOWN
        // ══════════════════════════════════════════════════════════
        const dlToggle = document.getElementById('downloadToggle');
        const dlMenu   = document.getElementById('downloadMenu');
        const dlWrap   = document.getElementById('downloadWrap');

        if (dlToggle && dlMenu) {
            dlToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dlMenu.classList.toggle('hidden');
            });
        }

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (dlWrap && !dlWrap.contains(e.target)) {
                dlMenu?.classList.add('hidden');
            }
        });

        // ══════════════════════════════════════════════════════════
        // 4. ESC closes filter panel + download menu
        // ══════════════════════════════════════════════════════════
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeFilter();
                dlMenu?.classList.add('hidden');
            }
        });
    }

    // ══════════════════════════════════════════════════════════
    // 5. DELETE CONFIRM — attach to window (not inside init)
    // ══════════════════════════════════════════════════════════
    window.deleteCountry = function (id, name) {
        const form = document.getElementById('delete-form-' + id);
        if (!form) return;

        const label = name ? '"' + name + '"' : 'this country';

        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                icon: 'warning',
                title: 'Delete Country?',
                html: 'Are you sure you want to delete ' + label + '?<br><span style="color:#ef4444;font-size:0.8em;">This action cannot be undone.</span>',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                focusCancel: true,
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        } else {
            if (confirm('Delete ' + label + '? This action cannot be undone.')) {
                form.submit();
            }
        }
    };

    // ══════════════════════════════════════════════════════════
    // 6. BOOT — wait for DOM to be ready
    // ══════════════════════════════════════════════════════════
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM already parsed (script loaded async / deferred)
        init();
    }
})();
</script>
@endpush
