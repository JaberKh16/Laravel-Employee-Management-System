@extends('admin.layout.main')

{{-- DataTables CSS kept as requested --}}
@push('dashboard_style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-5xl">

        {{-- ============================================================
             FLASH MESSAGE
             ============================================================ --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm dark:border-red-800 dark:bg-red-950/50 dark:text-red-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ============================================================
             PAGE HEADER
             ============================================================ --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Branches
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage all branch locations across your organization
                </p>
            </div>

            <a href="{{ route('branches.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Create Branch</span>
            </a>
        </header>

        {{-- ============================================================
             SEARCH BAR
             ============================================================ --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <form action="{{ route('branches.index') }}" method="GET" class="flex flex-1 min-w-[260px] items-center gap-2">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>
                    <input type="search"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Search branches by name, code, email…"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-indigo-400">
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <span>Search</span>
                </button>

                @if (request('search'))
                    <a href="{{ route('branches.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- ============================================================
             TABLE CARD
             NOTE: no overflow-x-auto on this wrapper — we need the
             status dropdown to be able to overflow outside the table.
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th scope="col" class="w-16 px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Branch</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Location</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($branches as $branch)
                        <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">

                            {{-- Row number --}}
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $branches->firstItem() + $loop->index }}
                            </td>

                            {{-- Branch name with initial avatar + code/email --}}
                            <td class="whitespace-nowrap px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-xs font-bold uppercase text-white">
                                        {{ $branch->initials }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $branch->name }}
                                        </div>
                                        <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            @if ($branch->code)
                                                <span class="inline-flex items-center rounded-md bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] font-semibold uppercase tracking-wide text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $branch->code }}
                                                </span>
                                            @endif
                                            @if ($branch->email)
                                                <span class="truncate">{{ $branch->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Location chip --}}
                            <td class="whitespace-nowrap px-4 py-3.5">
                                @if ($branch->location)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                        </svg>
                                        {{ $branch->location }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                        No location
                                    </span>
                                @endif
                            </td>

                            {{-- Status chip (interactive dropdown) --}}
                            <td class="whitespace-nowrap px-4 py-3.5">
                                @php
                                    $status = $branch->status;
                                    $label  = $status?->label() ?? 'Unknown';
                                    $color  = $status?->color() ?? 'gray';
                                    $classes = match ($color) {
                                        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                                        'amber'   => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                                        'red'     => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/60 dark:text-red-300',
                                        default   => 'border-gray-200 bg-gray-100 text-gray-600 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                @endphp

                                <div class="relative inline-block text-left"
                                     data-status-widget
                                     data-branch-id="{{ $branch->id }}"
                                     data-update-url="{{ route('branches.update-status', $branch) }}">

                                    {{-- Trigger button --}}
                                    <button type="button"
                                            data-status-trigger
                                            title="Change status"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold transition-all hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-1 dark:focus:ring-offset-gray-900 {{ $classes }}">
                                        <span data-status-dot class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                                        <span data-status-label>{{ $label }}</span>
                                        <svg class="h-3 w-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </button>

                                    {{-- Dropdown menu --}}
                                    <div data-status-menu
                                         role="listbox"
                                         class="absolute left-0 top-full z-30 mt-1.5 hidden w-44 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">

                                        <div class="relative">
                                            @foreach ($branchStatus as $option)
                                                @php
                                                    $dotColor = match ($option->color()) {
                                                        'emerald' => 'bg-emerald-500',
                                                        'amber'   => 'bg-amber-500',
                                                        'red'     => 'bg-red-500',
                                                        default   => 'bg-gray-400',
                                                    };
                                                    $isCurrent = $branch->status?->value === $option->value;
                                                @endphp

                                                <button type="button"
                                                        role="option"
                                                        aria-selected="{{ $isCurrent ? 'true' : 'false' }}"
                                                        data-status-option="{{ $option->value }}"
                                                        class="flex w-full items-center gap-2.5 px-3 py-2 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60
                                                            {{ $isCurrent ? 'bg-indigo-50/60 dark:bg-indigo-950/40' : '' }}">
                                                    <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
                                                    <span class="flex-1">{{ $option->label() }}</span>

                                                    @if ($isCurrent)
                                                        <svg class="check-icon h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                            @endforeach

                                            {{-- Loading overlay --}}
                                            <div data-status-loading
                                                 class="absolute inset-0 hidden items-center justify-center bg-white/70 backdrop-blur-[1px] dark:bg-gray-800/70">
                                                <svg class="h-4 w-4 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Error message --}}
                                        <div data-status-error
                                             class="hidden border-t border-red-100 bg-red-50 px-3 py-2 text-[11px] font-medium text-red-600 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- View --}}
                                    <a href="{{ route('branches.show', $branch) }}"
                                       title="View branch"
                                       aria-label="View {{ $branch->name }}"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-600 transition-colors hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500 dark:text-gray-300 dark:hover:bg-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('branches.edit', $branch) }}"
                                       title="Edit branch"
                                       aria-label="Edit {{ $branch->name }}"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 transition-colors hover:bg-indigo-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <button type="button"
                                            title="Delete branch"
                                            aria-label="Delete {{ $branch->name }}"
                                            onclick="deleteData({{ $branch->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:text-red-400 dark:hover:bg-red-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>

                                    {{-- Hidden delete form --}}
                                    <form id="delete-form-{{ $branch->id }}"
                                          action="{{ route('branches.destroy', $branch) }}"
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
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No branches found</h3>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            @if (request('search'))
                                                No results for "<strong>{{ request('search') }}</strong>". Try a different search.
                                            @else
                                                Get started by creating your first branch.
                                            @endif
                                        </p>
                                    </div>
                                    @if (request('search'))
                                        <a href="{{ route('branches.index') }}"
                                           class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                            Clear search
                                        </a>
                                    @else
                                        <a href="{{ route('branches.create') }}"
                                           class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                            Create Branch
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($branches->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                    {{ $branches->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Result count --}}
        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $branches->firstItem() ?? 0 }}</span>
            to
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $branches->lastItem() ?? 0 }}</span>
            of
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $branches->total() }}</span>
            branches
        </p>

    </div>
@endsection

@push('dashboard_script')

    <script>
        // ============================================================
        // Delete confirm helper
        // ============================================================
        window.deleteData = function (id) {
            if (! confirm('Delete this branch? This action cannot be undone.')) return;
            const form = document.getElementById('delete-form-' + id);
            if (form) form.submit();
        };

        // ============================================================
        // Inline status widget
        // ============================================================
        (function () {
            'use strict';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content
                ?? '{{ csrf_token() }}';

            // Cache of status value → display info
            const STATUS_META = {
                @foreach ($branchStatus as $option)
                    @php
                        $classes = match ($option->color()) {
                            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                            'amber'   => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                            'red'     => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/60 dark:text-red-300',
                            default   => 'border-gray-200 bg-gray-100 text-gray-600 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300',
                        };
                    @endphp
                    @json($option->value): {
                        label: @json($option->label()),
                        classes: @json($classes),
                    },
                @endforeach
            };

            // ---------------- Close all open menus -----------------
            function closeAllMenus(except) {
                document.querySelectorAll('[data-status-widget]').forEach(widget => {
                    if (widget === except) return;
                    widget.querySelector('[data-status-menu]')?.classList.add('hidden');
                    widget.querySelector('[data-status-trigger]')?.setAttribute('aria-expanded', 'false');
                });
            }

            // ---------------- Apply visual state after success -----
            function applyNewStatus(widget, status) {
                const meta = STATUS_META[status];
                if (!meta) return;

                const trigger = widget.querySelector('[data-status-trigger]');
                const labelEl = widget.querySelector('[data-status-label]');

                if (labelEl) labelEl.textContent = meta.label;

                if (trigger) {
                    // Reset all status-color classes
                    Object.values(STATUS_META).forEach(m => {
                        m.classes.split(' ').forEach(cls => trigger.classList.remove(cls));
                    });
                    // Apply new ones
                    meta.classes.split(' ').forEach(cls => trigger.classList.add(cls));
                }

                // Update the checkmark inside the menu
                widget.querySelectorAll('[data-status-option]').forEach(opt => {
                    const isMatch = opt.dataset.statusOption === status;
                    opt.setAttribute('aria-selected', isMatch ? 'true' : 'false');
                    opt.classList.toggle('bg-indigo-50/60', isMatch);
                    opt.classList.toggle('dark:bg-indigo-950/40', isMatch);

                    // Remove existing check
                    opt.querySelector('svg.check-icon')?.remove();

                    if (isMatch) {
                        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                        svg.setAttribute('class', 'check-icon h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400');
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', 'currentColor');
                        svg.setAttribute('stroke-width', '2.5');
                        svg.setAttribute('viewBox', '0 0 24 24');
                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('stroke-linecap', 'round');
                        path.setAttribute('stroke-linejoin', 'round');
                        path.setAttribute('d', 'M4.5 12.75l6 6 9-13.5');
                        svg.appendChild(path);
                        opt.appendChild(svg);
                    }
                });
            }

            // ---------------- Bind each widget ---------------------
            document.querySelectorAll('[data-status-widget]').forEach(widget => {
                const trigger = widget.querySelector('[data-status-trigger]');
                const menu    = widget.querySelector('[data-status-menu]');
                const loading = widget.querySelector('[data-status-loading]');
                const errorEl = widget.querySelector('[data-status-error]');
                const url     = widget.dataset.updateUrl;

                if (!trigger || !menu || !url) return;

                // Toggle menu
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = !menu.classList.contains('hidden');
                    closeAllMenus(widget);
                    if (isOpen) {
                        menu.classList.add('hidden');
                        trigger.setAttribute('aria-expanded', 'false');
                    } else {
                        menu.classList.remove('hidden');
                        trigger.setAttribute('aria-expanded', 'true');
                        errorEl?.classList.add('hidden');
                    }
                });

                // Pick an option
                menu.querySelectorAll('[data-status-option]').forEach(opt => {
                    opt.addEventListener('click', async (e) => {
                        e.stopPropagation();

                        const newStatus = opt.dataset.statusOption;
                        const current   = widget.querySelector('[data-status-option][aria-selected="true"]')
                                                 ?.dataset.statusOption;

                        if (newStatus === current) {
                            menu.classList.add('hidden');
                            trigger.setAttribute('aria-expanded', 'false');
                            return;
                        }

                        // Show spinner
                        errorEl?.classList.add('hidden');
                        loading?.classList.remove('hidden');
                        loading?.classList.add('flex');

                        try {
                            const res = await fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: JSON.stringify({ status: newStatus }),
                            });

                            const data = await res.json().catch(() => ({}));

                            if (!res.ok) {
                                const msg = data?.errors?.status?.[0]
                                    ?? data?.message
                                    ?? `Request failed (${res.status})`;
                                throw new Error(msg);
                            }

                            // Success → update UI
                            applyNewStatus(widget, newStatus);
                            menu.classList.add('hidden');
                            trigger.setAttribute('aria-expanded', 'false');

                        } catch (err) {
                            console.error('[status-update] failed:', err);
                            if (errorEl) {
                                errorEl.textContent = err.message || 'Could not update status.';
                                errorEl.classList.remove('hidden');
                            }
                        } finally {
                            loading?.classList.add('hidden');
                            loading?.classList.remove('flex');
                        }
                    });
                });

                // Prevent clicks inside the menu from closing it
                menu.addEventListener('click', (e) => e.stopPropagation());
            });

            // Click anywhere else → close all menus
            document.addEventListener('click', () => closeAllMenus());

            // Escape key → close all menus
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeAllMenus();
            });
        })();
    </script>
@endpush