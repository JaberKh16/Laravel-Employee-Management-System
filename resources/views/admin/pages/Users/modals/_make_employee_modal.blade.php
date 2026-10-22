<div id="makeEmployeeModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop p-4">
    <div class="modal-card w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">

        {{-- ═══════════════ HEADER ═══════════════ --}}
        @php $isEmployee = $is_employee ?? false; @endphp
        <div class="border-b border-gray-200 bg-gradient-to-br from-emerald-50 to-teal-50 px-6 py-5 dark:border-gray-700 dark:from-emerald-950/30 dark:to-teal-950/20">
            <div class="flex items-center gap-4">

                {{-- icon --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-950/60">
                    <i class="ri-briefcase-4-line text-2xl text-emerald-600 dark:text-emerald-400"></i>
                </div>

                {{-- title + subtitle --}}
                <div class="min-w-0 flex-1">
                    <h3 id="meModalTitle" class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $isEmployee ? 'Employee Information' : 'Convert to Employee' }}
                    </h3>
                    <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">
                        @if ($isEmployee)
                            Update <strong id="makeEmployeeName" class="text-gray-900 dark:text-white">this employee</strong>'s information.
                        @else
                            Make <strong id="makeEmployeeName" class="text-gray-900 dark:text-white">this user</strong> an employee.
                        @endif
                    </p>
                </div>

                {{-- right-side badges --}}
                <div class="flex shrink-0 flex-col items-end gap-2">

                    {{-- Employee / Non Employee status badge --}}
                    <div id="meRightStatusBadge"
                        class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold
                                {{ $isEmployee
                                    ? 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300'
                                    : 'border-gray-200 bg-gray-100 text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        <i id="meRightStatusIcon"
                        class="{{ $isEmployee ? 'ri-user-star-line' : 'ri-user-line' }} text-base"></i>
                        <span id="meRightStatusLabel">{{ $isEmployee ? 'Employee' : 'Non Employee' }}</span>
                    </div>

                    {{-- Job Profile badge (hidden until JS reveals it) --}}
                    <div id="meRightJobProfileBadge"
                        class="hidden inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold">
                        <i id="meRightJobProfileIcon" class="ri-profile-line text-base"></i>
                        <span id="meRightJobProfileLabel">Job Profile — none</span>

                        {{-- Icon button that opens the job profile template --}}
                        <button type="button"
                                id="meRightJobProfileBtn"
                                title="Open job profile"
                                class="hidden ml-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                            <i class="ri-external-link-line text-[11px]"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <form id="makeEmployeeForm" method="POST" action="" novalidate>
            @csrf

            <div class="max-h-[65vh] overflow-y-auto px-6 py-5">

                {{-- ═══ GLOBAL ERROR SUMMARY ═══ --}}
                <div id="meErrorSummary"
                     class="mb-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
                    <div class="flex items-start gap-2">
                        <i class="ri-error-warning-line mt-0.5 text-base"></i>
                        <div>
                            <p class="font-semibold">Please fix the following errors:</p>
                            <ul id="meErrorList" class="mt-1 list-inside list-disc space-y-0.5"></ul>
                        </div>
                    </div>
                </div>

                {{-- ── PERSONAL INFO ── --}}
                <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Personal Information
                </h4>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Full name --}}
                    <div class="sm:col-span-2">
                        <label for="me_name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="me_name" name="name" required aria-required="true"
                               autocomplete="name"
                               class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:bg-gray-700 dark:text-white
                                      @error('name') border-red-400 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DOB --}}
                    <div>
                        <label for="me_dob" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="me_dob" name="birthdate" required aria-required="true"
                               max="{{ now()->format('Y-m-d') }}"
                               class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:bg-gray-700 dark:text-white
                                      @error('birthdate') border-red-400 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                        @error('birthdate')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Age (auto-filled, readonly) --}}
                    <div>
                        <label for="me_age" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Age
                        </label>
                        <input type="number" id="me_age" name="age" readonly
                               class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200"
                               placeholder="Auto-calculated">
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label for="me_gender" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        @php
                            $genderOptions = [
                                'male'   => 'Male',
                                'female' => 'Female',
                                'other'  => 'Other',
                            ];
                        @endphp
                        <select id="me_gender" name="gender" required aria-required="true"
                                class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:bg-gray-700 dark:text-white
                                       @error('gender') border-red-400 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                            <option value="">Select…</option>
                            @foreach($genderOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('gender') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Zip Code --}}
                    <div>
                        <label for="me_zip" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Zip Code
                        </label>
                        <input type="text" id="me_zip" name="zip_code" maxlength="20"
                               value="{{ old('zip_code') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('zip_code')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- ── WORK INFO ── --}}
                <h4 class="mb-3 mt-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Work Information
                </h4>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                   {{-- Department --}}
                    <div>
                        <label for="me_department" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select id="me_department" name="department_id" required aria-required="true"
                                aria-describedby="me_department_error"
                                class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:bg-gray-700 dark:text-white
                                    @error('department_id') border-red-400 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                            <option value="">Select department…</option>
                            @foreach (\App\Models\Department::orderBy('name')->get() as $dept)
                                <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                            @endforeach
                        </select>

                        {{-- Inline error (JS toggles `hidden`) --}}
                        <p id="me_department_error"
                        class="mt-1 hidden flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400">
                            <i class="ri-error-warning-line text-sm"></i>
                            <span>Department is required.</span>
                        </p>

                        @error('department_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Branch (optional) --}}
                    <div>
                        <label for="me_branch" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Branch
                        </label>
                        <select id="me_branch" name="branch_id"
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select branch…</option>
                            @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- ── LOCATION ── --}}
                <h4 class="mb-3 mt-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Location
                </h4>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Country --}}
                    <div>
                        <label for="me_country" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Country
                        </label>
                        <select id="me_country" name="country_id"
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select country…</option>
                            @foreach (\App\Models\Country::orderBy('name')->get() as $c)
                                <option value="{{ $c->id }}" @selected(old('country_id') == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- State --}}
                    <div>
                        <label for="me_state" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            State
                        </label>
                        <select id="me_state" name="state_id"
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select state…</option>
                        </select>
                        @error('state_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- City --}}
                    <div class="sm:col-span-2">
                        <label for="me_city" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            City
                        </label>
                        <select id="me_city" name="city_id"
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select city…</option>
                        </select>
                        @error('city_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label for="me_address" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Address
                        </label>
                        <textarea id="me_address" name="address" rows="2"
                                  class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- ═══ AJAX LIVE ERROR BOX (JS-populated) ═══ --}}
                <div id="meError"
                     class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
                    <div class="flex items-start gap-2">
                        <i class="ri-error-warning-line mt-0.5"></i>
                        <ul id="meErrorList2" class="list-inside list-disc space-y-0.5"></ul>
                    </div>
                </div>
            </div>

            {{-- ═══════════════ FOOTER ═══════════════ --}}
            <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button" onclick="closeMakeEmployee()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </button>

                @php $isEmployee = $is_employee ?? false; @endphp

                @if ($isEmployee)
                    @include('components.loading.button', [
                        'id'      => 'makeEmployeeSubmit',
                        'label'   => 'Update information',
                        'loading' => 'Saving…',
                        'type'    => 'submit',
                        'variant' => 'primary',      
                        'icon'    => 'ri-save-3-line',
                        'data-role' => 'submit-label',
                    ])
                @else
                    @include('components.loading.button', [
                        'id'      => 'makeEmployeeSubmit',
                        'label'   => 'Yes, make employee',
                        'loading' => 'Converting…',
                        'type'    => 'submit',
                        'variant' => 'success',
                        'icon'    => 'ri-briefcase-4-fill',
                        'data-role' => 'submit-label',
                    ])
                @endif
                            
            </div>
        </form>
    </div>
</div>