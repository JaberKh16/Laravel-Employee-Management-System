@extends('admin.layout.main')

@push('dashboard_style')
<link rel="stylesheet" href="http://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
    /* Align DataTables controls with Tailwind styling */
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
</style>
@endpush

@section('dashboard_content')

<div class="flex flex-col lg:flex-row gap-6">

    {{-- ============================================================
         SIDEBAR — Name / Email / Late (Date) FILTER FIELDS
         ============================================================ --}}
    <aside class="w-full lg:w-72 shrink-0">
        <div class="sticky top-6 space-y-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Sidebar header --}}
            <div class="flex items-center gap-3 border-b border-gray-200 pb-3 dark:border-gray-700">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Filters</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Refine the user list</p>
                </div>
            </div>

            {{-- Filter form --}}
            <form action="{{ route('users.index') }}" method="GET" id="sidebarFilterForm" class="space-y-5">

                {{-- Preserve main search --}}
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- NAME field --}}
                <div>
                    <label for="filter_name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Name
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="name"
                            id="filter_name"
                            value="{{ request('name') }}"
                            placeholder="Filter by name…"
                            class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500">
                    </div>
                </div>

                {{-- EMAIL field --}}
                <div>
                    <label for="filter_email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Email
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="email"
                            id="filter_email"
                            value="{{ request('email') }}"
                            placeholder="Filter by email…"
                            class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500">
                    </div>
                </div>

                {{-- LATE (DATE) filter field --}}
                <div>
                    <label for="filter_late" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Late (Last Modified)
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </span>
                        <select
                            name="late"
                            id="filter_late"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All dates</option>
                            <option value="7"   {{ request('late') == '7'   ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30"  {{ request('late') == '30'  ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90"  {{ request('late') == '90'  ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('late') == '365' ? 'selected' : '' }}>Last year</option>
                            <option value="older" {{ request('late') == 'older' ? 'selected' : '' }}>Older than 1 year</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">Filter by how recently the user was updated.</p>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2 pt-1">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                        </svg>
                        Apply
                    </button>
                    <a href="{{ route('users.index') }}"
                        title="Reset filters"
                        class="inline-flex h-[42px] w-[42px] items-center justify-center rounded-xl border border-gray-300 bg-white text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                    </a>
                </div>

                {{-- Active filter chips --}}
                @if (request('name') || request('email') || request('late'))
                    <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Active filters</p>
                        <div class="flex flex-wrap gap-1.5">
                            @if (request('name'))
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                    Name: {{ request('name') }}
                                </span>
                            @endif
                            @if (request('email'))
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                    Email: {{ request('email') }}
                                </span>
                            @endif
                            @if (request('late'))
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                                    Late: {{ request('late') === 'older' ? '> 1 year' : 'Last ' . request('late') . ' days' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </aside>

    {{-- ============================================================
         MAIN CONTENT (Users list + search + table)
         ============================================================ --}}
    <div class="flex-1 min-w-0">

        {{-- PAGE HEADER --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Users
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage all user accounts in the system
                </p>
            </div>

            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Create User</span>
            </a>
        </header>

        {{-- SEARCH + TOOLBAR --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-1 min-w-[260px] items-center gap-2">

                {{-- Preserve sidebar filters when using main search --}}
                @if (request('name'))  <input type="hidden" name="name"  value="{{ request('name') }}">  @endif
                @if (request('email')) <input type="hidden" name="email" value="{{ request('email') }}"> @endif
                @if (request('late'))  <input type="hidden" name="late"  value="{{ request('late') }}">  @endif

                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Search users by name, email, username…"
                        class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-indigo-400">
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <span>Search</span>
                </button>

                @if (request('search') || request('name') || request('email') || request('late'))
                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear all
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE CARD --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">
                <table id="userTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Username</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Full Name</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Last Modified</th>
                            <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">

                                {{-- Row number --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>

                                {{-- Username --}}
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold uppercase text-white">
                                            {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $user->username }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Full Name --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $user->getInitialsAttribute() }}
                                </td>

                                {{-- Email --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                    <a href="mailto:{{ $user->email }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $user->email }}
                                    </a>
                                </td>

                                {{-- Last modified --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                    <span title="{{ $user->updated_at }}">
                                        {{ $user->updated_at->diffForHumans() }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('users.edit', $user) }}"
                                           title="Edit user"
                                           aria-label="Edit {{ $user->username }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 transition-colors hover:bg-indigo-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </a>

                                        <button
                                            type="button"
                                            title="Delete user"
                                            aria-label="Delete {{ $user->username }}"
                                            onclick="deleteData({{ $user->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:text-red-400 dark:hover:bg-red-950/40">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $user->id }}"
                                              action="{{ route('users.destroy', $user) }}"
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
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No users found</h3>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                @if (request('search') || request('name') || request('email') || request('late'))
                                                    No results match the current filters. Try adjusting them.
                                                @else
                                                    Get started by creating your first user.
                                                @endif
                                            </p>
                                        </div>
                                        @if (request('search') || request('name') || request('email') || request('late'))
                                            <a href="{{ route('users.index') }}"
                                               class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                                Clear filters
                                            </a>
                                        @else
                                            <a href="{{ route('users.create') }}"
                                               class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                                Create User
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Result count --}}
        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->firstItem() ?? 0 }}</span>
            to
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->lastItem() ?? 0 }}</span>
            of
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->total() }}</span>
            users
        </p>

    </div>{{-- /main content --}}
</div>{{-- /flex wrapper --}}

@endsection

@push('dashboard_script')
<script>
    $(document).ready(function () {
        // Initialize DataTables (set ordering false for # column if desired)
        $('#userTable').DataTable({
            "order": [],        // keep server-side order
            "columnDefs": [
                { "orderable": false, "targets": [0, 5] }  // # and Actions not sortable
            ]
        });

        // Confirm delete
        window.deleteData = function (id) {
            if (! confirm('Delete this user? This action cannot be undone.')) return;
            document.getElementById('delete-form-' + id).submit();
        };

        // Auto-submit sidebar filter on Enter (already handled by form)
        // Optional: debounce name/email inputs for live-feel
        let debounceTimer;
        $('#filter_name, #filter_email').on('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // Uncomment below for auto-submit after 600ms idle
                // $('#sidebarFilterForm').submit();
            }, 600);
        });
    });
</script>
@endpush