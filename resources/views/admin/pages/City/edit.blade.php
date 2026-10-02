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
                    Edit City
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update details for
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $city->name }}</span>
                </p>
            </div>

            <a href="{{ route('cities.index') }}"
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
              action="{{ route('cities.update', $city) }}"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @csrf
            @method('PUT')

            {{-- Card header strip --}}
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    City Details
                </h2>
            </div>

            {{-- Card body --}}
            <div class="space-y-6 p-6">

                @php
                    // Resolve the "current" state: old() first, then DB value.
                    $currentStateId = old('state_id', $city->state_id);

                    // Derive the currently-selected country so the country
                    // filter pre-selects correctly on first load.
                    $currentCountryId = old(
                        'country_filter',
                        optional($states->firstWhere('id', $currentStateId))->country_id
                    );
                @endphp

                {{-- ============================================================
                     COUNTRY (filter only — not submitted)
                     ============================================================ --}}
                <div>
                    <label for="country_filter"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Country
                    </label>

                    <div class="relative">
                        <select id="country_filter"
                                name="country_filter"
                                class="block w-full appearance-none rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 pr-10 text-sm text-gray-900 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-indigo-400">
                            <option value="">All countries</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @selected($currentCountryId == $country->id)>
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

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Narrows the state list below. Not saved.
                    </p>
                </div>

                {{-- ============================================================
                     STATE
                     ============================================================ --}}
                <div>
                    <label for="state_id"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        State <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <select id="state_id"
                                name="state_id"
                                required
                                class="block w-full appearance-none rounded-xl border bg-white px-3.5 py-2.5 pr-10 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white
                                       @error('state_id') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                       @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                       @enderror">
                            <option value="" disabled @selected(! $currentStateId)>
                                Select a state
                            </option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"
                                        data-country-id="{{ $state->country_id }}"
                                        @selected($currentStateId == $state->id)>
                                    {{ $state->name }}@if ($state->country) — {{ $state->country->name }}@endif
                                </option>
                            @endforeach
                        </select>

                        {{-- Custom chevron --}}
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </span>
                    </div>

                    @error('state_id')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- ============================================================
                     CITY NAME
                     ============================================================ --}}
                <div>
                    <label for="name"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        City Name <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name', $city->name) }}"
                           required
                           autocomplete="off"
                           autofocus
                           placeholder="e.g. San Francisco"
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

                {{-- ============================================================
                     DESCRIPTION
                     ============================================================ --}}
                <div>
                    <label for="description"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="3"
                              placeholder="Optional notes or description about this city"
                              class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                                     @error('description') border-red-500 focus:border-red-500 focus:ring-red-500/30
                                     @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                                     @enderror">{{ old('description', $city->description) }}</textarea>

                    @error('description')
                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
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
                <a href="{{ route('cities.index') }}"
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
    <script>
        // ============================================================
        // Dependent Country → State filter
        //
        // The country dropdown is a UI filter only — it hides state
        // options that don't belong to the selected country. The
        // `state_id` select is the one actually submitted.
        //
        // On page load, if the currently-saved state has a country,
        // the country filter is pre-selected and the state list is
        // filtered accordingly. Users can still widen it back to
        // "All countries" to see every state.
        // ============================================================
        (function () {
            'use strict';

            const countrySelect = document.getElementById('country_filter');
            const stateSelect   = document.getElementById('state_id');

            if (!countrySelect || !stateSelect) return;

            // Snapshot every state option on first load so we can rebuild
            // the list whenever the country filter changes.
            const allOptions = Array.from(stateSelect.querySelectorAll('option[data-country-id]'))
                .map(opt => ({
                    value: opt.value,
                    label: opt.textContent,
                    countryId: opt.dataset.countryId,
                    selected: opt.selected,
                }));

            function rebuildStateOptions(countryId, preserveSelection) {
                const currentValue = preserveSelection
                    ? stateSelect.value
                    : null;

                // Remove all state options (keep the placeholder)
                stateSelect.querySelectorAll('option[data-country-id]').forEach(o => o.remove());

                allOptions
                    .filter(o => !countryId || String(o.countryId) === String(countryId))
                    .forEach(o => {
                        const opt = document.createElement('option');
                        opt.value = o.value;
                        opt.textContent = o.label;
                        opt.dataset.countryId = o.countryId;
                        if (o.value === currentValue) opt.selected = true;
                        stateSelect.appendChild(opt);
                    });

                // If the previously-selected state was filtered out, reset
                // the state select back to the placeholder.
                if (currentValue) {
                    const stillPresent = Array.from(stateSelect.options)
                        .some(o => o.value === currentValue && o.value !== '');
                    if (!stillPresent) stateSelect.value = '';
                }
            }

            // On first load, apply the pre-selected country filter so the
            // state dropdown only shows relevant options.
            if (countrySelect.value) {
                rebuildStateOptions(countrySelect.value, true);
            }

            countrySelect.addEventListener('change', function () {
                rebuildStateOptions(this.value, true);
            });
        })();
    </script>
@endpush