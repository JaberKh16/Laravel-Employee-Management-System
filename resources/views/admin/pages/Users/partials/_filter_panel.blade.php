<div id="filterPanel" class="filter-panel">
    <form action="{{ route('users.index') }}"
          method="GET"
          id="filterForm"
          data-loading="Applying filters…"
          class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        @if (request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        {{-- ═══════════════ HEADER ═══════════════ --}}
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

        {{-- ═══════════════ SEARCH CRITERIA RADIOS ═══════════════ --}}
        <div class="mb-4">
            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Search Criteria</p>
            <div class="flex flex-wrap gap-2">
                @php
                    $criteria  = request('criteria', 'name');
                    $criterias = $filterCriterias;
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

        {{-- ═══════════════ CRITERIA FIELDS ═══════════════ --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

            {{-- NAME --}}
            <div class="criteria-field" data-criteria="name">
                <label for="filter_name"
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-user-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Full Name / Username
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-search-line text-sm"></i>
                    </span>
                    <input type="text" name="name" id="filter_name" value="{{ request('name') }}"
                           placeholder="e.g. John Doe"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            {{-- EMAIL --}}
            <div class="criteria-field" data-criteria="email">
                <label for="filter_email"
                    class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-mail-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Email
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-at-line text-sm"></i>
                    </span>
                    <input type="email" name="email" id="filter_email" value="{{ request('email') }}"
                        placeholder="e.g. user@example.com"
                        class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            {{-- DATE --}}
            <div class="criteria-field" data-criteria="date">
                <label for="filter_date"
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-calendar-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Specific Date
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-calendar-event-line text-sm"></i>
                    </span>
                    <input type="date" name="date" id="filter_date" value="{{ request('date') }}"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            {{-- DOB --}}
            <div class="criteria-field" data-criteria="dob">
                <label for="filter_dob"
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-cake-2-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Date of Birth
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-calendar-2-line text-sm"></i>
                    </span>
                    <input type="date" name="dob" id="filter_dob" value="{{ request('dob') }}"
                           class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            {{-- STATUS --}}
            <div class="criteria-field" data-criteria="status">
                <label for="filter_status"
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-checkbox-circle-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Status
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-toggle-line text-sm"></i>
                    </span>
                    <select name="status" id="filter_status"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">All statuses</option>
                        @foreach ($statusOptions as $meta)
                            <option value="{{ $meta['value'] }}"
                                {{ (string) request('status') === (string) $meta['value'] ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="ri-arrow-down-s-line text-sm"></i>
                    </span>
                </div>
            </div>

            {{-- ROLE --}}
            <div class="criteria-field" data-criteria="role">
                <label for="filter_role"
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-shield-user-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Role
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="ri-team-line text-sm"></i>
                    </span>
                    <select name="role" id="filter_role"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">All roles</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>
                                {{ ucfirst($r->name) }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="ri-arrow-down-s-line text-sm"></i>
                    </span>
                </div>
            </div>

            {{-- TIMESTAMP RANGE --}}
            <div class="criteria-field md:col-span-2" data-criteria="timestamp">
                <label
                       class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <i class="ri-time-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                    Timestamp Range (Updated)
                </label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-calendar-check-line text-sm"></i>
                        </span>
                        <input type="datetime-local" name="updated_from" value="{{ request('updated_from') }}"
                               placeholder="From"
                               class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-calendar-todo-line text-sm"></i>
                        </span>
                        <input type="datetime-local" name="updated_to" value="{{ request('updated_to') }}"
                               placeholder="To"
                               class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════ SORT OPTIONS ═══════════════ --}}
        <div class="mt-5 border-t border-gray-200 pt-4 dark:border-gray-700">
            <p class="mb-2 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                <i class="ri-sort-desc text-sm text-indigo-500 dark:text-indigo-400"></i>
                Sort Results
            </p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- Sort by --}}
                <div>
                    <label for="filter_sort"
                           class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <i class="ri-list-check text-sm text-indigo-500 dark:text-indigo-400"></i>
                        Sort By
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-arrow-up-down-line text-sm"></i>
                        </span>
                        <select name="sort" id="filter_sort"
                                class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="updated_at" {{ request('sort', 'updated_at') === 'updated_at' ? 'selected' : '' }}>Last Modified</option>
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="username"   {{ request('sort') === 'username'   ? 'selected' : '' }}>Username</option>
                            <option value="email"      {{ request('sort') === 'email'      ? 'selected' : '' }}>Email</option>
                            <option value="status"     {{ request('sort') === 'status'     ? 'selected' : '' }}>Status</option>
                            <option value="id"         {{ request('sort') === 'id'         ? 'selected' : '' }}>ID</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <i class="ri-arrow-down-s-line text-sm"></i>
                        </span>
                    </div>
                </div>

                {{-- Direction --}}
                <div>
                    <label for="filter_direction"
                           class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <i class="ri-arrow-up-down-line text-sm text-indigo-500 dark:text-indigo-400"></i>
                        Direction
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-arrow-down-line text-sm"></i>
                        </span>
                        <select name="direction" id="filter_direction"
                                class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-9 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Descending (Newest / Z→A)</option>
                            <option value="asc"  {{ request('direction') === 'asc'  ? 'selected' : '' }}>Ascending (Oldest / A→Z)</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <i class="ri-arrow-down-s-line text-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════ FOOTER ═══════════════ --}}
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