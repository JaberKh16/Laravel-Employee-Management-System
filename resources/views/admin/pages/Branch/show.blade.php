@extends('admin.layout.main')

@push('dashboard_style')
    {{-- (empty — reserved for page-specific styles) --}}
@endpush

@section('dashboard_content')

    <div class="mx-auto max-w-3xl">

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

        {{-- ============================================================
             PAGE HEADER
             ============================================================ --}}
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Branch Details
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View complete information for
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $branch->name }}</span>
                </p>
            </div>

            <a href="{{ route('branches.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Back</span>
            </a>
        </header>

        {{-- ============================================================
             HERO CARD — Avatar + name + status + actions
             ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="h-24 bg-gradient-to-br from-sky-500 via-indigo-500 to-indigo-600"></div>

            <div class="relative px-6 pb-6">
                <div class="-mt-12 flex flex-wrap items-end justify-between gap-4">
                    <div class="flex items-end gap-5">
                        {{-- Initials avatar --}}
                        <span class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-sky-500 to-indigo-600 text-2xl font-bold uppercase text-white shadow-lg dark:border-gray-800">
                            {{ $branch->initials }}
                        </span>

                        <div class="pb-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $branch->name }}
                            </h2>

                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                @if ($branch->code)
                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-mono text-[11px] font-semibold uppercase tracking-wide text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $branch->code }}
                                    </span>
                                @endif

                                {{-- Current status chip --}}
                                @php
                                    $status  = $branch->status;
                                    $label   = $status?->label() ?? 'Unknown';
                                    $color   = $status?->color() ?? 'gray';
                                    $classes = match ($color) {
                                        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                                        'amber'   => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                                        'red'     => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/60 dark:text-red-300',
                                        default   => 'border-gray-200 bg-gray-100 text-gray-600 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                                    {{ $label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center gap-2 pb-1">
                        <a href="{{ route('branches.edit', $branch) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                            <span>Edit</span>
                        </a>

                        <button type="button"
                                onclick="deleteData({{ $branch->id }})"
                                class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition-colors hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-400 dark:border-red-900 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-950/40">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                            <span>Delete</span>
                        </button>

                        <form id="delete-form-{{ $branch->id }}"
                              action="{{ route('branches.destroy', $branch) }}"
                              method="POST"
                              class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
             STATUS CHANGE CARD
             ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Change Status
                </h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Update the operational status of this branch
                </p>
            </div>

            <form action="{{ route('branches.update-status', $branch) }}"
                  method="POST"
                  class="space-y-5 p-6">
                @csrf
                @method('PATCH')

                {{-- Status radio cards --}}
                <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                    @foreach ($branchStatus as $statusOption)
                        @php
                            $isCurrent = old('status', $branch->status?->value) === $statusOption->value;

                            // Map enum color → tailwind classes for selected state
                            $selectedClasses = match ($statusOption->color()) {
                                'emerald' => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:border-emerald-500 dark:peer-checked:bg-emerald-900/30',
                                'amber'   => 'peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:border-amber-500 dark:peer-checked:bg-amber-900/30',
                                'red'     => 'peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:border-red-500 dark:peer-checked:bg-red-900/30',
                                default   => 'peer-checked:border-gray-500 peer-checked:bg-gray-50 dark:peer-checked:border-gray-500 dark:peer-checked:bg-gray-700/40',
                            };

                            $dotClasses = match ($statusOption->color()) {
                                'emerald' => 'bg-emerald-500',
                                'amber'   => 'bg-amber-500',
                                'red'     => 'bg-red-500',
                                default   => 'bg-gray-400',
                            };
                        @endphp

                        <label class="group relative cursor-pointer">
                            <input type="radio"
                                   name="status"
                                   value="{{ $statusOption->value }}"
                                   class="peer sr-only"
                                   {{ $isCurrent ? 'checked' : '' }}
                                   required>

                            <div class="flex items-center justify-center gap-2 rounded-xl border-2 border-gray-200 bg-white px-3 py-2.5 transition-all duration-200
                                        hover:border-gray-300 hover:shadow-sm
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-indigo-500 peer-focus-visible:ring-offset-1
                                        dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600
                                        dark:peer-focus-visible:ring-offset-gray-900
                                        {{ $selectedClasses }}">
                                <span class="h-2 w-2 rounded-full {{ $dotClasses }}"></span>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                    {{ $statusOption->label() }}
                                </span>
                            </div>

                            {{-- Check badge --}}
                            <span class="pointer-events-none absolute right-1.5 top-1.5 hidden h-4 w-4 items-center justify-center rounded-full bg-indigo-500 text-white shadow
                                         peer-checked:flex">
                                <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('status')
                    <p class="flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4 dark:border-gray-700">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span>Update Status</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================================
             CONTACT INFORMATION
             ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Contact Information
                </h2>
            </div>

            <dl class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                {{-- Email --}}
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Email
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        @if ($branch->email)
                            <a href="mailto:{{ $branch->email }}"
                               class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                                {{ $branch->email }}
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </dd>
                </div>

                {{-- Phone --}}
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Phone
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        @if ($branch->phone)
                            <a href="tel:{{ $branch->phone }}"
                               class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                                {{ $branch->phone }}
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </dd>
                </div>

                {{-- Manager --}}
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Manager
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->manager_name ?: '—' }}
                    </dd>
                </div>

                {{-- Code --}}
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Branch Code
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        @if ($branch->code)
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-wide text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ $branch->code }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- ============================================================
             LOCATION
             ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Location
                </h2>
            </div>

            <dl class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Country
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->country?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        State
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->state?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        City
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->city?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Zip Code
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->zip_code ?: '—' }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Street Address
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        @if ($branch->address)
                            <span class="inline-flex items-start gap-1.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                                {{ $branch->address }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- ============================================================
             MAP
             ============================================================ --}}
        @php
            // Build the best possible address string from available fields
            $mapQuery = collect([
                $branch->address,
                $branch->city?->name,
                $branch->state?->name,
                $branch->zip_code,
                $branch->country?->name,
            ])->filter()->implode(', ');
        @endphp

        @if ($mapQuery)
            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Map
                        </h2>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            {{ $mapQuery }}
                        </p>
                    </div>

                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($mapQuery) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                        <span>Open in Google Maps</span>
                    </a>
                </div>

                {{-- Google Maps embed iframe (no API key required for this format) --}}
                <div class="aspect-[16/9] w-full bg-gray-100 dark:bg-gray-900">
                    <iframe
                        title="Map for {{ $branch->name }}"
                        src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed"
                        class="h-full w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif

        {{-- ============================================================
             DESCRIPTION
             ============================================================ --}}
        @if ($branch->description)
            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Description
                    </h2>
                </div>

                <div class="p-6 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                    {!! nl2br(e($branch->description)) !!}
                </div>
            </div>
        @endif

        {{-- ============================================================
             META / TIMESTAMPS
             ============================================================ --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Record Information
                </h2>
            </div>

            <dl class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">
                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        ID
                    </dt>
                    <dd class="font-mono text-sm text-gray-900 dark:text-white">
                        #{{ $branch->id }}
                    </dd>
                </div>

                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Created
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->created_at?->format('M d, Y · H:i') ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Last Updated
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $branch->updated_at?->diffForHumans() ?? '—' }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- ============================================================
             FOOTER ACTIONS
             ============================================================ --}}
        <div class="flex flex-wrap items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white px-6 py-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <a href="{{ route('branches.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Back to List</span>
            </a>

            <a href="{{ route('branches.edit', $branch) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>
                <span>Edit Branch</span>
            </a>
        </div>

    </div>
@endsection

@push('dashboard_script')
    <script>
        // ============================================================
        // Delete confirm helper — same as the index page
        // ============================================================
        window.deleteData = function (id) {
            if (! confirm('Delete this branch? This action cannot be undone.')) return;
            const form = document.getElementById('delete-form-' + id);
            if (form) form.submit();
        };
    </script>
@endpush