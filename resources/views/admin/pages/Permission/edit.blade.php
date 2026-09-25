@extends('admin.layout.main')

@push('dashboard_style')
    {{-- (empty — reserved for page-specific styles) --}}
@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-2xl">

        {{-- ============================================================
             PAGE HEADER
             ============================================================ --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Edit Permission
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update permission name
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                </p>
            </div>

            <a href="{{ route('permissions.index') }}"
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
              action="{{ route('permissions.update', $permission) }}"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @csrf
            @method('PUT')

            {{-- Card header strip --}}
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Permission Details
                </h2>
            </div>

            {{-- Card body --}}
            <div class="space-y-6 p-6">

                {{-- ============================================================
                     PERMISSION NAME
                     ============================================================ --}}
                <div>
                    <label for="name"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Permission Name <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name', $permission->name) }}"
                           required
                           autocomplete="off"
                           autofocus
                           placeholder="e.g. users.create, posts.edit"
                           class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                  @error('name') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                  @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                  @enderror">

                    {{-- Helper text --}}
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Use dot notation for grouped permissions —
                        <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-gray-700 dark:text-gray-200">module.action</code>
                        (e.g. <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-gray-700 dark:text-gray-200">users.create</code>)
                    </p>

                    @error('name')
                        <p class="mt-2 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

            </div>

            {{-- ============================================================
                 CARD FOOTER
                 ============================================================ --}}
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <a href="{{ route('permissions.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                    <span>Update</span>
                </button>
            </div>

        </form>

    </div>
@endsection

@push('dashboard_script')
    {{-- (empty — no custom JS needed for this form) --}}
@endpush