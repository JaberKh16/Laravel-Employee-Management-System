<div id="makeEmployeeModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop p-4">
    <div class="modal-card w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">

        {{-- ═══════════════ HEADER ═══════════════ --}}
        <div class="border-b border-gray-200 bg-gradient-to-br from-emerald-50 to-teal-50 px-6 py-5 dark:border-gray-700 dark:from-emerald-950/30 dark:to-teal-950/20">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-950/60">
                    <i class="ri-briefcase-4-line text-2xl text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Convert to Employee</h3>
                    <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">
                        Make <strong id="makeEmployeeName" class="text-gray-900 dark:text-white">this user</strong> an employee.
                    </p>
                </div>
            </div>
        </div>

        <form id="makeEmployeeForm" method="POST" action="">
            @csrf

            <div class="max-h-[65vh] overflow-y-auto px-6 py-5">
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
                        <input type="text" id="me_name" name="name" required
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- DOB --}}
                    <div>
                        <label for="me_dob" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="me_dob" name="birthdate" required
                               max="{{ now()->format('Y-m-d') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                        <select id="me_gender" name="gender" required
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select…</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    {{-- Zip Code --}}
                    <div>
                        <label for="me_zip" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Zip Code
                        </label>
                        <input type="text" id="me_zip" name="zip_code" maxlength="20"
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                        <select id="me_department" name="department_id" required
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select department…</option>
                            @foreach (\App\Models\Department::orderBy('name')->get() as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
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
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
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
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
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
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label for="me_address" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Address
                        </label>
                        <textarea id="me_address" name="address" rows="2"
                                  class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                </div>

                {{-- Live error box --}}
                <div id="meError"
                     class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
                </div>
            </div>

            {{-- ═══════════════ FOOTER ═══════════════ --}}
            <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button" onclick="closeMakeEmployee()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </button>

                @include('components.loading.button', [
                    'id'      => 'makeEmployeeSubmit',
                    'label'   => 'Yes, make employee',
                    'loading' => 'Converting…',
                    'type'    => 'submit',
                    'variant' => 'success',
                    'icon'    => 'ri-briefcase-4-fill',
                ])
            </div>
        </form>
    </div>
</div>

