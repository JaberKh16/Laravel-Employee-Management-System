{{-- ═══ JOB PROFILE SECTION (shown only for employees with a profile) ═══ --}}
<div id="meJobProfileSection" class="hidden">
    <h4 class="mb-3 mt-6 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
        <i class="ri-profile-line text-base text-indigo-500 dark:text-indigo-400"></i>
        Job Profile
    </h4>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <input type="hidden" id="me_job_profile_id" name="job_profile_id" data-field="id">

        {{-- Designation --}}
        <div class="sm:col-span-2">
            <label for="me_job_designation" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Designation
            </label>
            <input type="text" id="me_job_designation" name="designation" data-field="designation"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Employment type --}}
        <div>
            <label for="me_job_employment_type" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Employment Type
            </label>
            <select id="me_job_employment_type" name="employment_type" data-field="employment_type"
                    class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Select…</option>
                <option value="full-time">Full-time</option>
                <option value="part-time">Part-time</option>
                <option value="contract">Contract</option>
                <option value="intern">Intern</option>
            </select>
        </div>

        {{-- Currency --}}
        <div>
            <label for="me_job_currency" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Currency
            </label>
            <input type="text" id="me_job_currency" name="currency" data-field="currency" maxlength="8"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Basic salary --}}
        <div>
            <label for="me_job_basic_salary" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Basic Salary
            </label>
            <input type="number" step="0.01" min="0" id="me_job_basic_salary" name="basic_salary" data-field="basic_salary"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Allowance --}}
        <div>
            <label for="me_job_allowance" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Allowance
            </label>
            <input type="number" step="0.01" min="0" id="me_job_allowance" name="allowance" data-field="allowance"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- National ID --}}
        <div>
            <label for="me_job_national_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                National ID
            </label>
            <input type="text" id="me_job_national_id" name="national_id" data-field="national_id"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Passport --}}
        <div>
            <label for="me_job_passport" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Passport Number
            </label>
            <input type="text" id="me_job_passport" name="passport_number" data-field="passport_number"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Nationality --}}
        <div>
            <label for="me_job_nationality" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Nationality
            </label>
            <input type="text" id="me_job_nationality" name="nationality" data-field="nationality"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Marital status --}}
        <div>
            <label for="me_job_marital" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Marital Status
            </label>
            <select id="me_job_marital" name="marital_status" data-field="marital_status"
                    class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Select…</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="divorced">Divorced</option>
                <option value="widowed">Widowed</option>
            </select>
        </div>

        {{-- Phone --}}
        <div>
            <label for="me_job_phone" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Phone
            </label>
            <input type="text" id="me_job_phone" name="phone" data-field="phone"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Emergency contact name --}}
        <div>
            <label for="me_job_ec_name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Emergency Contact Name
            </label>
            <input type="text" id="me_job_ec_name" name="emergency_contact_name" data-field="emergency_contact_name"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Emergency contact phone --}}
        <div>
            <label for="me_job_ec_phone" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Emergency Contact Phone
            </label>
            <input type="text" id="me_job_ec_phone" name="emergency_contact_phone" data-field="emergency_contact_phone"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Postal code --}}
        <div>
            <label for="me_job_postal" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Postal Code
            </label>
            <input type="text" id="me_job_postal" name="postal_code" data-field="postal_code"
                   class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Notes --}}
        <div class="sm:col-span-2">
            <label for="me_job_notes" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                Notes
            </label>
            <textarea id="me_job_notes" name="notes" rows="2" data-field="notes"
                      class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
        </div>

    </div>
</div>

{{-- ═══ NO JOB PROFILE CALLOUT (shown for employees without a profile) ═══ --}}
<div id="meNoJobProfile"
     class="hidden mt-6 rounded-xl border border-indigo-200 bg-indigo-50 p-4 dark:border-indigo-900/40 dark:bg-indigo-950/30">
    <div class="flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-950/60">
            <i class="ri-profile-line text-xl text-indigo-600 dark:text-indigo-400"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-indigo-900 dark:text-indigo-100">
                No job profile attached yet
            </p>
            <p class="mt-1 text-xs text-indigo-700 dark:text-indigo-300">
                This employee doesn't have a job profile on record. Create one to store salary,
                designation, and emergency contact information.
            </p>
            <div class="mt-3">
                <button type="button" id="me_create_job_profile_btn"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                    <i class="ri-add-line text-sm"></i>
                    Create Job Profile
                </button>
            </div>
        </div>
    </div>
</div>