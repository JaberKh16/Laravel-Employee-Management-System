@extends('admin.layout.main')

{{-- DataTables CSS kept as requested --}}
@push('dashboard_style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-6xl">

        {{-- ============================================================
             FLASH MESSAGES
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
                    States
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage all states and their countries
                </p>
            </div>

            <a href="{{ route('states.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Create State</span>
            </a>
        </header>

        {{-- ============================================================
             FILTER BAR (search + country filter)
             ============================================================ --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <form action="{{ route('states.index') }}"
                  method="GET"
                  class="flex flex-1 min-w-[260px] flex-wrap items-center gap-2">

                {{-- Search input --}}
                <div class="relative flex-1 min-w-[200px]">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>
                    <input type="search"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Search states by name or code…"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-indigo-400">
                </div>

                {{-- Country filter --}}
                <div class="relative min-w-[180px]">
                    <select name="country_id"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pr-10 text-sm text-gray-900 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-400">
                        <option value="">All countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @selected(request('country_id') == $country->id)>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </span>
                </div>

                {{-- Apply --}}
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <span>Filter</span>
                </button>

                {{-- Clear --}}
                @if (request('search') || request('country_id'))
                    <a href="{{ route('states.index') }}"
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
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Code</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Country</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Description</th>
                            <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($states as $state)
                            <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">

                                {{-- Row number --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ $states->firstItem() + $loop->index }}
                                </td>

                                {{-- State name with initial avatar --}}
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-amber-600 text-xs font-bold uppercase text-white">
                                            {{ strtoupper(substr($state->name ?? 'S', 0, 2)) }}
                                        </span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $state->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- State code --}}
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    @if ($state->state_code)
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                            {{ $state->state_code }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                                {{-- Country chip with flag --}}
                                <td class="whitespace-nowrap px-4 py-3.5">
                                    @if ($state->country)
                                        <div class="flex items-center gap-2">
                                            @if ($state->country->country_code)
                                                <img src="https://flagcdn.com/w40/{{ strtolower($state->country->country_code) }}.png"
                                                     alt="{{ $state->country->name }} flag"
                                                     class="h-5 w-7 rounded object-cover shadow-sm ring-1 ring-gray-200 dark:ring-gray-700"
                                                     onerror="this.style.display='none'">
                                            @endif
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $state->country->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                            No country
                                        </span>
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-gray-300">
                                    @if ($state->description)
                                        <span title="{{ $state->description }}"
                                              class="line-clamp-2 block max-w-xs">
                                            {{ \Illuminate\Support\Str::limit($state->description, 60) }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Edit --}}
                                        <a href="{{ route('states.edit', $state) }}"
                                           title="Edit state"
                                           aria-label="Edit {{ $state->name }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 transition-colors hover:bg-indigo-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button"
                                                title="Delete state"
                                                aria-label="Delete {{ $state->name }}"
                                                onclick="deleteData({{ $state->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:text-red-400 dark:hover:bg-red-950/40">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>

                                        {{-- Hidden delete form --}}
                                        <form id="delete-form-{{ $state->id }}"
                                              action="{{ route('states.destroy', $state) }}"
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
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No states found</h3>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                @if (request('search') || request('country_id'))
                                                    No results match your filters. Try adjusting your search or country.
                                                @else
                                                    Get started by creating your first state.
                                                @endif
                                            </p>
                                        </div>
                                        @if (request('search') || request('country_id'))
                                            <a href="{{ route('states.index') }}"
                                               class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                                Clear filters
                                            </a>
                                        @else
                                            <a href="{{ route('states.create') }}"
                                               class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                                Create State
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
            @if ($states->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                    {{ $states->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Result count --}}
        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $states->firstItem() ?? 0 }}</span>
            to
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $states->lastItem() ?? 0 }}</span>
            of
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $states->total() }}</span>
            states
        </p>

    </div>
@endsection
@push('dashboard_script')
    <script>
        // ============================================================
        // Delete confirm helper — the delete buttons call this
        // ============================================================
        window.deleteData = function (id) {
            if (! confirm('Delete this state? This action cannot be undone.')) return;
            const form = document.getElementById('delete-form-' + id);
            if (form) form.submit();
        };

        // ============================================================
        // NOTE about the original broken script:
        //
        // Original code:
        //     $(document).ready(function () {
        //         $('#userTable').DataTable();   // ← WRONG ID
        //     });
        //
        // Bugs:
        //   1. This is the STATES page, but it references #userTable
        //      (copy-pasted from the users view) — no such element.
        //   2. The <table> has NO id attribute at all.
        //   3. deleteData() was never defined → ReferenceError on click.
        //
        // Fixed by defining deleteData() and removing the broken
        // DataTables call (the <script> tag is still loaded, as requested).
        // ============================================================
    </script>
@endpush