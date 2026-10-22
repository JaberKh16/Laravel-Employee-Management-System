@extends('admin.layout.main')

@push('dashboard_style')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css" rel="stylesheet">
<style>
    .ts-wrapper .ts-control {
        border-radius: 0.5rem;
        border: 1px solid rgb(209 213 219);
        background: rgb(249 250 251);
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        min-height: 42px;
        box-shadow: none;
    }
    .dark .ts-wrapper .ts-control {
        border-color: rgb(75 85 99);
        background: rgb(55 65 81);
        color: rgb(243 244 246);
    }
    .ts-wrapper.focus .ts-control {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .ts-dropdown {
        border-radius: 0.5rem;
        border: 1px solid rgb(229 231 235);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        z-index: 50;
    }
    .dark .ts-dropdown {
        background: rgb(31 41 55);
        border-color: rgb(55 65 81);
        color: rgb(243 244 246);
    }
    .dark .ts-dropdown .option.active { background: rgb(55 65 81); }
    .ts-dropdown .option { padding: 0.5rem 0.75rem; }

    /* Row selection ring — visible while table has a selection */
    tr.row-selected {
        background: rgba(59, 130, 246, 0.08) !important;
        outline: 1px solid rgba(59, 130, 246, 0.35);
        outline-offset: -1px;
    }
    .dark tr.row-selected {
        background: rgba(59, 130, 246, 0.12) !important;
        outline-color: rgba(59, 130, 246, 0.5);
    }
    #notifications-table.has-selection tbody tr:not(.row-selected):not(.hidden) {
        opacity: 0.55;
    }
    #notifications-table.has-selection tbody tr.row-selected {
        opacity: 1;
    }

    /* Loader overlay for table */
    .table-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
    }
    .table-loading::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        z-index: 20;
    }
    .dark .table-loading::after {
        background: rgba(17, 24, 39, 0.5);
    }

    /* Header pill auto-hide transition */
    #header-unread-pill {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }
    #header-unread-pill.is-hidden {
        opacity: 0;
        transform: scale(0.95);
        pointer-events: none;
    }
</style>
@endpush


@section('dashboard_content')

<div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6" id="notifications-page">

    {{-- ============ PAGE HEADER ============ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        {{-- Title + breadcrumb --}}
        <div class="min-w-0">
            <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mb-2"
                 aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route('home') ?? '#' }}"
                   class="inline-flex items-center gap-1 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
                <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ __('Notifications') }}</span>
            </nav>

            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('Notifications') }}
                </h1>

                <span id="header-unread-pill"
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-blue-50 text-blue-700 ring-1 ring-blue-200/60
                             dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20
                             {{ ($meta['unread_count'] ?? 0) === 0 ? 'is-hidden' : '' }}">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span id="header-unread-pill-count">{{ $meta['unread_count'] ?? 0 }}</span>
                    {{ __('unread') }}
                </span>
            </div>

            <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                {{ __('Stay on top of system activity. Review, mark, or dismiss notifications from one place.') }}
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <span id="header-last-updated"
                  class="hidden sm:inline-flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 mr-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span data-updated-text>{{ __('Just now') }}</span>
            </span>

            <button id="header-refresh" type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg
                           bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-200
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           focus:ring-2 focus:ring-blue-500 focus:outline-none
                           transition-all active:scale-95">
                <svg id="header-refresh-icon" class="w-4 h-4 transition-transform duration-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('Refresh') }}
            </button>

            <button id="header-mark-all" type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg
                           bg-blue-600 hover:bg-blue-700 text-white
                           focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                           dark:focus:ring-offset-gray-900
                           disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600
                           transition-all active:scale-95"
                    @if (($meta['unread_count'] ?? 0) === 0) disabled @endif>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Mark all read') }}
            </button>
        </div>
    </div>

    {{-- ============ STATUS CARDS ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- All --}}
        <div data-card="all"
             class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5
                    shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md
                                     bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </span>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('All Notifications') }}</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">
                        <span data-all-count>{{ $meta['total'] ?? 0 }}</span>
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ __('Total in your inbox') }}</p>
                </div>
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg shrink-0
                             bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400
                             group-hover:bg-gray-100 dark:group-hover:bg-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </span>
            </div>
        </div>

        {{-- Unread --}}
        <div data-card="unread"
             class="group bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-800 p-5
                    shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/40 to-transparent dark:from-blue-500/5 pointer-events-none"></div>
            <div class="relative flex items-start justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md
                                     bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                        </span>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('Unread') }}</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-blue-700 dark:text-blue-300">
                        <span data-unread-count>{{ $meta['unread_count'] ?? 0 }}</span>
                    </p>
                    <p class="mt-1 text-xs text-blue-500/80 dark:text-blue-400/80">{{ __('Needs your attention') }}</p>
                </div>
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg shrink-0
                             bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400
                             group-hover:bg-blue-100 dark:group-hover:bg-blue-500/20 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
        </div>

        {{-- Read --}}
        <div data-card="read"
             class="group bg-white dark:bg-gray-800 rounded-xl border border-emerald-200 dark:border-emerald-800 p-5
                    shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/40 to-transparent dark:from-emerald-500/5 pointer-events-none"></div>
            <div class="relative flex items-start justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md
                                     bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ __('Read') }}</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-emerald-700 dark:text-emerald-300">
                        <span data-read-count>{{ $meta['read_count'] ?? 0 }}</span>
                    </p>
                    <p class="mt-1 text-xs text-emerald-500/80 dark:text-emerald-400/80">{{ __('Already reviewed') }}</p>
                </div>
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg shrink-0
                             bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                             group-hover:bg-emerald-100 dark:group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    {{-- ============ FILTER BAR + TABLE ============ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        {{-- Filter bar --}}
        <div class="p-4 border-b border-gray-100 dark:border-gray-700/60">
            <form id="filter-form" method="GET" action="{{ route('notifications.list') }}"
                  class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="filter-search" name="search" type="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('Search title or message…') }}"
                           class="w-full pl-9 pr-3 py-2.5 text-sm rounded-lg border border-gray-300
                                  dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                </div>

                <select id="filter-category" name="category" class="w-full md:w-48" placeholder="{{ __('All categories') }}">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach (['system', 'message', 'alert', 'billing', 'employee', 'update', 'announcement', 'success', 'warning', 'error', 'info', 'action', 'general'] as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>

                <select id="filter-status" name="status" class="w-full md:w-40" placeholder="{{ __('All status') }}">
                    <option value="">{{ __('All status') }}</option>
                    <option value="unread" @selected($unreadOnly)>{{ __('Unread') }}</option>
                    <option value="read" @selected($statusFilter === 'read')>{{ __('Read') }}</option>
                </select>

                <button id="filter-clear" type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg
                               border border-gray-300 dark:border-gray-600
                               text-gray-700 dark:text-gray-200
                               hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Clear') }}
                </button>
            </form>

            <div id="active-filters"
                 class="mt-3 flex flex-wrap items-center gap-2 {{ ($search || $category || $unreadOnly || $statusFilter) ? '' : 'hidden' }}">
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Active:') }}</span>

                @if ($search)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                                 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ $search }}
                    </span>
                @endif
                @if ($category)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                                 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        {{ ucfirst($category) }}
                    </span>
                @endif
                @if ($unreadOnly)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                                 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                        {{ __('Unread only') }}
                    </span>
                @endif
                @if ($statusFilter === 'read')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                                 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                        {{ __('Read only') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Bulk bar --}}
        <div id="bulk-bar"
             class="hidden px-4 py-3 bg-blue-50 dark:bg-blue-500/10
                    border-b border-blue-200 dark:border-blue-800
                    flex items-center justify-between">
            <p class="text-sm text-blue-800 dark:text-blue-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                </svg>
                <span id="bulk-count" class="font-semibold">0</span> {{ __('selected') }}
            </p>
            <div class="flex gap-2">
                <button id="bulk-mark-read" type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md
                               bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-700
                               text-blue-700 dark:text-blue-300
                               hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Mark as read') }}
                </button>
                <button id="bulk-dismiss" type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md
                               bg-white dark:bg-gray-800 border border-red-300 dark:border-red-700
                               text-red-700 dark:text-red-300
                               hover:bg-red-50 dark:hover:bg-red-500/20 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Dismiss') }}
                </button>
            </div>
        </div>

        {{-- Compact table --}}
        <div class="overflow-x-auto relative" id="table-wrapper">
            <table id="notifications-table" class="min-w-full text-sm">
                <thead class="bg-gray-50/80 dark:bg-gray-900/40 sticky top-0 backdrop-blur z-10">
                    <tr>
                        <th class="px-3 py-2.5 w-10 text-center">
                            <input id="check-all" type="checkbox"
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Notification') }}
                        </th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Category') }}
                        </th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Status') }}
                        </th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Time') }}
                        </th>
                        <th class="px-3 py-2.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-24">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                    @forelse ($notifications as $n)
                        @php
                            $isUnread = !$n->is_read;
                            $catColors = [
                                'success' => 'bg-emerald-500', 'warning' => 'bg-amber-500',
                                'error'   => 'bg-red-500',     'info'    => 'bg-blue-500',
                                'action'  => 'bg-violet-500',  'system'  => 'bg-slate-500',
                                'message' => 'bg-sky-500',     'alert'   => 'bg-orange-500',
                                'billing' => 'bg-pink-500',    'employee'=> 'bg-teal-500',
                                'update'  => 'bg-indigo-500',  'announcement' => 'bg-fuchsia-500',
                                'general' => 'bg-gray-400',
                            ];
                            $dotClass = $catColors[$n->category ?? 'general'] ?? $catColors['general'];
                        @endphp
                        <tr data-id="{{ $n->id }}"
                            data-category="{{ $n->category }}"
                            data-status="{{ $isUnread ? 'unread' : 'read' }}"
                            class="group {{ $isUnread ? 'bg-blue-50/30 dark:bg-blue-500/5' : '' }}
                                   hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" class="row-check w-4 h-4 rounded border-gray-300
                                                            text-blue-600 focus:ring-blue-500 cursor-pointer"
                                       value="{{ $n->id }}">
                            </td>
                            <td class="px-3 py-2.5 cursor-pointer row-toggle">
                                <div class="flex items-start gap-2.5">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-md shrink-0
                                                 {{ $isUnread ? 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate leading-tight">
                                            @if ($isUnread)
                                                <span class="unread-dot inline-block w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 align-middle"></span>
                                            @endif
                                            {{ $n->title ?? __('Notification') }}
                                        </p>
                                        @if ($n->message)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate leading-tight">
                                                {{ $n->message }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300">
                                    <span class="w-2 h-2 rounded-full {{ $dotClass }}"></span>
                                    {{ ucfirst($n->category ?? 'general') }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5">
                                @if ($isUnread)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ __('Unread') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Read') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $n->created_at?->diffForHumans(short: true) }}
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                @if ($isUnread)
                                    <button type="button" data-mark-read="{{ $n->id }}"
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium
                                                   text-blue-600 dark:text-blue-400
                                                   hover:bg-blue-50 dark:hover:bg-blue-500/10
                                                   opacity-0 group-hover:opacity-100 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Read') }}
                                    </button>
                                @else
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr data-empty-row>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full
                                            bg-gray-100 dark:bg-gray-700/50 mb-3">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('No notifications found') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ __('Try adjusting your filters or clear them to see all.') }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-900/20">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection



<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
/* =========================================================
   NOTIFICATIONS LIST PAGE — full wiring
   Depends on:
     - window.NOTIFICATION_ENDPOINTS (topbar partial)
     - window.toastSuccess / toastError / toastWarning / toastInfo (layout)
   ========================================================= */
(function () {
    'use strict';

    /* ---------- Endpoint resolution ---------- */
    const CFG = window.NOTIFICATION_ENDPOINTS || {};
    if (!CFG.index) {
        console.warn('[notifications-page] NOTIFICATION_ENDPOINTS missing — include the topbar partial first.');
    }

    const endpoint = {
        unread:   CFG.unread     || '{{ route("notifications.unread-count") }}',
        markAll:  CFG.markAll    || '{{ route("notifications.mark-all-read") }}',
        markOne:  (id)  => (CFG.markOne    || '/notifications/__ID__/read').replace('__ID__', id),
        bulk:     (act) => (CFG.bulkAction || '/notifications/bulk/__ACTION__').replace('__ACTION__', act),
        csrf:     CFG.csrf || document.querySelector('meta[name="csrf-token"]')?.content,
    };

    /* ---------- Safe toast wrappers ---------- */
    const toastOk   = (msg, opts) => (window.toastSuccess || console.info)(msg, opts);
    const toastErr  = (msg, opts) => (window.toastError   || console.error)(msg, opts);
    const toastWarn = (msg, opts) => (window.toastWarning || console.warn)(msg, opts);

    /* =========================================================
       1. TOM SELECT — filter dropdowns
       ========================================================= */
    (function initTomSelect() {
        const form  = document.getElementById('filter-form');
        const catEl = document.getElementById('filter-category');
        const stEl  = document.getElementById('filter-status');
        if (!form) return;

        const submitForm = () => form.submit();

        const tsOpts = {
            create: false,
            allowEmptyOption: false,
            maxItems: 1,
            plugins: ['clear_button'],
            onChange: submitForm,
        };

        const catTS = catEl ? new TomSelect(catEl, tsOpts) : null;
        const stTS  = stEl  ? new TomSelect(stEl,  tsOpts) : null;

        window.__notifFilters = { catTS, stTS };

        const search = document.getElementById('filter-search');
        let searchTimer;
        search?.addEventListener('input', () => {
            clearTimeout(searchTimer);
            window.__notifApplyClientFilters?.();
            searchTimer = setTimeout(submitForm, 600);
        });

        document.getElementById('filter-clear')?.addEventListener('click', () => {
            if (search) search.value = '';
            catTS?.clear(true);
            stTS?.clear(true);
            window.location.href = form.action;
        });
    })();

    /* =========================================================
       2. TABLE — selection, bulk, per-row actions, header
       ========================================================= */
    (function initTable() {
        const table = document.getElementById('notifications-table');
        if (!table) return;

        const wrapper   = document.getElementById('table-wrapper');
        const tbody     = table.querySelector('tbody');
        const checkAll  = document.getElementById('check-all');
        const bulkBar   = document.getElementById('bulk-bar');
        const bulkCount = document.getElementById('bulk-count');
        const cards     = {
            all:    document.querySelector('[data-card="all"]'),
            unread: document.querySelector('[data-card="unread"]'),
            read:   document.querySelector('[data-card="read"]'),
        };

        /* ---------- Header refs ---------- */
        const headerPill       = document.getElementById('header-unread-pill');
        const headerPillCount  = document.getElementById('header-unread-pill-count');
        const headerMarkAll    = document.getElementById('header-mark-all');
        const headerRefresh    = document.getElementById('header-refresh');
        const headerRefreshIco = document.getElementById('header-refresh-icon');
        const headerUpdated    = document.getElementById('header-last-updated');
        const headerUpdatedTxt = headerUpdated?.querySelector('[data-updated-text]');

        /* ---------- Helpers ---------- */
        const getSelected = () =>
            [...tbody.querySelectorAll('input.row-check:checked')].map(cb => cb.value);

        const rows = () => [...tbody.querySelectorAll('tr[data-id]')];

        const setLoading = (on) => wrapper?.classList.toggle('table-loading', on);

        const touchUpdated = () => {
            if (!headerUpdatedTxt) return;
            headerUpdatedTxt.textContent = '{{ __("Just now") }}';
            headerUpdated?.classList.remove('hidden');
        };

        const syncHeaderCount = (count) => {
            if (headerPillCount) headerPillCount.textContent = count;

            if (headerPill) {
                // Prefer the CSS class over inline style so the transition works
                headerPill.classList.toggle('is-hidden', count === 0);
                // Also handle initial inline style if present
                headerPill.style.display = '';
            }

            if (headerMarkAll) {
                headerMarkAll.disabled = count === 0;
            }
        };

        const syncBulkBar = () => {
            const ids = getSelected();
            const hasSelection = ids.length > 0;

            if (bulkBar)   bulkBar.classList.toggle('hidden', !hasSelection);
            if (bulkCount) bulkCount.textContent = ids.length;

            table.classList.toggle('has-selection', hasSelection);

            rows().forEach(tr => {
                const cb = tr.querySelector('input.row-check');
                tr.classList.toggle('row-selected', !!cb?.checked);
            });

            if (checkAll) {
                const all = rows().length;
                checkAll.checked = all > 0 && ids.length === all;
                checkAll.indeterminate = ids.length > 0 && ids.length < all;
            }
        };

        /* ---------- Selection events ---------- */
        tbody.addEventListener('change', (e) => {
            if (e.target.matches('input.row-check')) syncBulkBar();
        });

        checkAll?.addEventListener('change', () => {
            rows().forEach(tr => {
                const cb = tr.querySelector('input.row-check');
                if (cb) cb.checked = checkAll.checked;
            });
            syncBulkBar();
        });

        tbody.addEventListener('click', (e) => {
            const toggle = e.target.closest('.row-toggle');
            if (!toggle) return;
            // Don't intercept clicks on links or buttons inside the cell
            if (e.target.closest('a, button')) return;
            const tr = toggle.closest('tr');
            const cb = tr?.querySelector('input.row-check');
            if (!cb) return;
            cb.checked = !cb.checked;
            syncBulkBar();
        });

        /* ---------- Counts refresh ---------- */
        const refreshCounts = async (silent = false) => {
            try {
                const res = await fetch(endpoint.unread, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('counts failed');
                const { count, total, read } = await res.json();

                if (cards.unread) cards.unread.querySelector('[data-unread-count]').textContent = count ?? 0;
                if (cards.read)   cards.read.querySelector('[data-read-count]').textContent     = read  ?? 0;
                if (cards.all)    cards.all.querySelector('[data-all-count]').textContent       = total ?? 0;

                syncHeaderCount(count ?? 0);
                touchUpdated();

                if (typeof window.refreshNotifications === 'function') {
                    window.refreshNotifications();
                }
            } catch (err) {
                if (!silent) console.warn('[counts]', err);
            }
        };

        /* ---------- Row → read visual swap ---------- */
        const markRowRead = (row) => {
            row.classList.remove('bg-blue-50/30', 'dark:bg-blue-500/5');
            row.dataset.status = 'read';
            row.querySelector('.unread-dot')?.remove();
            row.querySelector('[data-mark-read]')?.remove();

            const statusCell = row.children[3];
            if (statusCell) {
                statusCell.innerHTML = `
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Read') }}
                    </span>`;
            }
            const actionsCell = row.lastElementChild;
            if (actionsCell) {
                actionsCell.innerHTML = `<span class="text-[11px] text-gray-400 dark:text-gray-500">—</span>`;
            }
        };

        /* ---------- Bulk actions ---------- */
        const bulkAction = async (action) => {
            const ids = getSelected();

            if (!ids.length) {
                toastWarn('No notifications selected. Choose at least one to continue.', {
                    title: 'Nothing selected',
                });
                return;
            }

            if (action === 'dismiss') {
                const confirmed = await confirmDialog(
                    `Dismiss ${ids.length} notification${ids.length > 1 ? 's' : ''}?`,
                    'This will hide them from your list. You can\'t undo this from the UI.'
                );
                if (!confirmed) return;
            }

            setLoading(true);

            const snapshot = ids.map(id => tbody.querySelector(`tr[data-id="${id}"]`)).filter(Boolean);
            const snapshotMap = new Map(snapshot.map(tr => [tr.dataset.id, tr.cloneNode(true)]));

            try {
                const res = await fetch(endpoint.bulk(action), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': endpoint.csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ ids }),
                });
                if (!res.ok) throw new Error(`bulk ${action} failed (${res.status})`);

                ids.forEach(id => {
                    const row = tbody.querySelector(`tr[data-id="${id}"]`);
                    if (!row) return;
                    if (action === 'dismiss') row.remove();
                    else if (action === 'read') markRowRead(row);
                });

                syncBulkBar();
                await refreshCounts(true);

                const verb = action === 'dismiss' ? 'dismissed' : 'marked as read';
                toastOk(`${ids.length} notification${ids.length > 1 ? 's' : ''} ${verb}.`, {
                    title: action === 'dismiss' ? 'Dismissed' : 'Marked as read',
                });
            } catch (err) {
                console.error('[bulk]', err);

                snapshotMap.forEach((clone, id) => {
                    const current = tbody.querySelector(`tr[data-id="${id}"]`);
                    if (current) current.replaceWith(clone);
                    else tbody.appendChild(clone);
                });
                syncBulkBar();
                toastErr('The action could not be completed. Please try again.', {
                    title: 'Action failed',
                });
            } finally {
                setLoading(false);
            }
        };

        document.getElementById('bulk-mark-read')?.addEventListener('click', () => bulkAction('read'));
        document.getElementById('bulk-dismiss')?.addEventListener('click', () => bulkAction('dismiss'));

        /* ---------- Per-row mark read ---------- */
        tbody.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-mark-read]');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            const id  = btn.dataset.markRead;
            const row = btn.closest('tr');
            setLoading(true);

            try {
                const res = await fetch(endpoint.markOne(id), {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': endpoint.csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('mark read failed');

                markRowRead(row);
                await refreshCounts(true);
                toastOk('Notification marked as read.', { title: 'Updated' });
            } catch (err) {
                console.warn('[mark-read]', err);
                toastErr('Could not mark this notification as read.', { title: 'Action failed' });
            } finally {
                setLoading(false);
            }
        });

        /* ---------- Header: mark all read ---------- */
        headerMarkAll?.addEventListener('click', async () => {
            const confirmed = await confirmDialog(
                'Mark every unread notification as read?',
                'You can still see them in the "Read" filter.'
            );
            if (!confirmed) return;

            headerMarkAll.disabled = true;
            setLoading(true);

            try {
                const res = await fetch(endpoint.markAll, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': endpoint.csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('mark-all failed');

                // Flip every unread row to read in place
                rows().forEach(row => {
                    if (row.dataset.status === 'unread') markRowRead(row);
                });

                syncBulkBar();
                await refreshCounts(true);
                toastOk('All notifications marked as read.', { title: 'Done' });
            } catch (err) {
                console.error('[mark-all]', err);
                toastErr('Could not mark all as read. Try again.', { title: 'Action failed' });
                headerMarkAll.disabled = false;
            } finally {
                setLoading(false);
            }
        });

        /* ---------- Header: refresh ---------- */
        headerRefresh?.addEventListener('click', async () => {
            headerRefreshIco?.classList.add('animate-spin');
            await refreshCounts();
            touchUpdated();
            setTimeout(() => headerRefreshIco?.classList.remove('animate-spin'), 500);
        });

        /* ---------- Listen for topbar count updates ---------- */
        document.addEventListener('notifications:count', (e) => {
            const count = e.detail?.count ?? 0;
            if (cards.unread) cards.unread.querySelector('[data-unread-count]').textContent = count;
            syncHeaderCount(count);
        });

        /* ---------- Client-side filter ---------- */
        const searchEl = document.getElementById('filter-search');
        const applyClientFilters = () => {
            const q = (searchEl?.value ?? '').toLowerCase().trim();
            const { catTS, stTS } = window.__notifFilters || {};
            const c = catTS?.getValue() ?? '';
            const s = stTS?.getValue()  ?? '';

            let visible = 0;
            rows().forEach(row => {
                const text     = row.textContent.toLowerCase();
                const matchesQ = !q || text.includes(q);
                const matchesC = !c || row.dataset.category === c;
                const matchesS = !s || row.dataset.status === s;
                const hide = !(matchesQ && matchesC && matchesS);
                row.classList.toggle('hidden', hide);
                if (!hide) visible++;
            });

            const emptyRow = tbody.querySelector('[data-empty-row]');
            if (emptyRow) emptyRow.classList.toggle('hidden', visible > 0);
        };

        window.__notifApplyClientFilters = applyClientFilters;
        searchEl?.addEventListener('input', applyClientFilters);
        window.__notifFilters?.catTS?.on('change', applyClientFilters);
        window.__notifFilters?.stTS?.on('change',  applyClientFilters);

        /* ---------- Initial sync ---------- */
        syncBulkBar();
        // Make sure the header pill respects the initial count on load
        const initialCount = parseInt(headerPillCount?.textContent ?? '0', 10) || 0;
        syncHeaderCount(initialCount);
    })();

    /* =========================================================
       3. CONFIRM DIALOG
       ========================================================= */
    function confirmDialog(text, hint) {
        if (window.Swal) {
            return Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                html: `<p class="text-sm">${text}</p>${hint ? `<p class="text-xs mt-2 text-gray-500">${hint}</p>` : ''}`,
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText:  'Cancel',
                confirmButtonColor: '#ef4444',
                reverseButtons: true,
            }).then(r => r.isConfirmed);
        }
        return Promise.resolve(window.confirm(`${text}\n\n${hint || ''}`));
    }
})();
</script>
