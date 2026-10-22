{{-- ══════════════════════════════════════════════════════════════
     PROFILE MODAL — View + Inline Edit Mode
     ══════════════════════════════════════════════════════════════ --}}
<div id="profileModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="profileName">

    <div class="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-800 dark:ring-white/10">

        {{-- ════════════════════════════════════════════════════════
             HERO HEADER
             ════════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-500 to-purple-600 px-6 pb-6 pt-8 sm:px-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-white/5"></div>

            <button type="button" onclick="closeProfile()"
                    class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="relative shrink-0">
                    <div id="profileAvatar"
                         class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 text-2xl font-bold uppercase text-white ring-4 ring-white/20 backdrop-blur-sm">
                        —
                    </div>
                    <span id="profileStatusDot"
                          class="absolute -bottom-0.5 -right-0.5 h-5 w-5 rounded-full border-4 border-indigo-500 bg-gray-400 shadow-lg"></span>
                </div>

                <div class="min-w-0 flex-1 text-white">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 id="profileName" class="truncate text-2xl font-bold tracking-tight view-mode">Loading…</h2>

                        <input type="text" id="edit_name" name="name"
                               class="edit-mode hidden w-full max-w-md rounded-lg border border-white/30 bg-white/10 px-3 py-1.5 text-xl font-bold text-white placeholder-white/60 backdrop-blur-sm focus:border-white/60 focus:outline-none focus:ring-2 focus:ring-white/40"
                               placeholder="Full name">

                        <span id="profileStatusBadge"
                              class="inline-flex items-center gap-1.5 rounded-full bg-gray-400/20 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-gray-100 ring-1 ring-gray-300/40 backdrop-blur-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                            <span id="profileStatusLabel">—</span>
                        </span>
                    </div>

                    <p id="profileUsername" class="mt-1 truncate text-sm font-medium text-white/80 view-mode">—</p>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-white/75">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Created <strong id="profileCreatedAt" class="font-semibold text-white">—</strong></span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7"/>
                            </svg>
                            <span>Updated <strong id="profileUpdatedAt" class="font-semibold text-white">—</strong></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
             BODY
             ════════════════════════════════════════════════════════ --}}
        <div class="max-h-[65vh] overflow-y-auto bg-gray-50/60 px-6 py-6 dark:bg-gray-900/40 sm:px-8">

            {{-- LOADING --}}
            <div id="profileLoading" class="flex flex-col items-center justify-center py-16">
                <svg class="h-10 w-10 animate-spin text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">Loading profile…</p>
            </div>

            {{-- ERROR STATE --}}
            <div id="profileErrorState" class="hidden flex-col items-center justify-center py-16">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Unable to load profile</h3>
                <p id="profileErrorStateMsg" class="mt-1 max-w-sm text-center text-sm text-gray-500 dark:text-gray-400">
                    Something went wrong. Please try again.
                </p>
                <button type="button" onclick="retryLoadProfile()"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7"/>
                    </svg>
                    Retry
                </button>
            </div>

            {{-- CONTENT --}}
            <div id="profileContent" class="hidden space-y-6">

                {{-- PERSONAL --}}
                <section>
                    <header class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Personal Details</h3>
                        <span class="h-px flex-1 bg-gradient-to-r from-gray-200 to-transparent dark:from-gray-700"></span>
                    </header>

                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">First Name</dt>
                            <dd id="profileFirstName" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_first_name" name="first_name"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Middle Name</dt>
                            <dd id="profileMiddleName" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_middle_name" name="middle_name"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Last Name</dt>
                            <dd id="profileLastName" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_last_name" name="last_name"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Birthdate</dt>
                            <dd id="profileBirthdate" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="date" id="edit_birthdate" name="birthdate"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Gender</dt>
                            <dd id="profileGender" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <select id="edit_gender" name="gender"
                                    class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select…</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Age</dt>
                            <dd id="profileAge" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                    </dl>
                </section>

                {{-- CONTACT --}}
                <section>
                    <header class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Contact Details</h3>
                        <span class="h-px flex-1 bg-gradient-to-r from-gray-200 to-transparent dark:from-gray-700"></span>
                    </header>

                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Email</dt>
                            <dd id="profileEmail" class="view-mode mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="email" id="edit_email" name="email"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Phone</dt>
                            <dd id="profilePhone" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_phone" name="phone"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Website</dt>
                            <dd id="profileWebsite" class="view-mode mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="url" id="edit_website" name="website"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                   placeholder="https://...">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">LinkedIn</dt>
                            <dd id="profileLinkedin" class="view-mode mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_linkedin" name="linkedin"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Twitter</dt>
                            <dd id="profileTwitter" class="view-mode mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_twitter" name="twitter"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Zip Code</dt>
                            <dd id="profileZip" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <input type="text" id="edit_zip_code" name="zip_code"
                                   class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:col-span-2 lg:col-span-3">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Address</dt>
                            <dd id="profileAddress" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <textarea id="edit_address" name="address" rows="2"
                                      class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Country</dt>
                            <dd id="profileCountry" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <select id="edit_country_id" name="country_id"
                                    class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select country…</option>
                                @foreach (\App\Models\Country::orderBy('name')->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">State</dt>
                            <dd id="profileState" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <select id="edit_state_id" name="state_id"
                                    class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select state…</option>
                            </select>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">City</dt>
                            <dd id="profileCity" class="view-mode mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                            <select id="edit_city_id" name="city_id"
                                    class="edit-mode hidden mt-1 w-full rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select city…</option>
                            </select>
                        </div>
                    </dl>
                </section>

                {{-- OFFICIAL --}}
                <section id="employeeSection" class="hidden">
                    <header class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-1.591 2.162A48.35 48.35 0 0112 21.75a48.35 48.35 0 01-6.659-.465 2.25 2.25 0 01-1.591-2.162V14.15"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Official Details</h3>
                        <span class="h-px flex-1 bg-gradient-to-r from-gray-200 to-transparent dark:from-gray-700"></span>
                    </header>

                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Employee ID</dt>
                            <dd id="profileEmployeeId" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Department</dt>
                            <dd id="profileDepartment" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Designation</dt>
                            <dd id="profileDesignation" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Hire Date</dt>
                            <dd id="profileHireDate" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Employment Status</dt>
                            <dd id="profileEmpStatus" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Reports To</dt>
                            <dd id="profileReportsTo" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                    </dl>
                </section>

                <div id="profileError"
                     class="hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800 sm:px-8">
            <div id="footerView" class="flex items-center gap-2">
                <button type="button" onclick="closeProfile()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    Close
                </button>
                <button type="button" id="profileEditBtn" onclick="enterEditMode()"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Edit User
                </button>
            </div>

            <div id="footerEdit" class="hidden flex items-center gap-2">
                <button type="button" onclick="cancelEditMode()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </button>
                <button type="button" id="profileSaveBtn" onclick="saveProfile()"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:opacity-60">
                    <svg id="saveSpinner" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg id="saveIcon" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span id="saveLabel">Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- <script>
(function () {
    'use strict';

    // ──────────────────────────────────────────────────────────
    // STATE
    // ──────────────────────────────────────────────────────────
    var currentProfile = null;
    var currentUserId  = null;
    var currentMode    = 'view';

    // ──────────────────────────────────────────────────────────
    // SAFE DOM HELPERS — NO CRASHES
    // ──────────────────────────────────────────────────────────
    function $(id) { return document.getElementById(id); }

    function setText(id, value) {
        var el = $(id);
        if (!el) return;
        el.textContent = (value === null || value === undefined || value === '') ? '—' : value;
    }

    function setVal(id, value) {
        var el = $(id);
        if (!el) return;
        el.value = (value === null || value === undefined) ? '' : value;
    }

    function show(el)  { el && el.classList.remove('hidden'); }
    function hide(el)  { el && el.classList.add('hidden'); }

    // ──────────────────────────────────────────────────────────
    // UI STATE MACHINE
    // ──────────────────────────────────────────────────────────
    function uiLoading() {
        show($('profileLoading'));
        hide($('profileErrorState'));
        hide($('profileContent'));
        var fv = $('footerView');
        if (fv) fv.classList.add('opacity-50', 'pointer-events-none');
        hide($('footerEdit'));
    }

    function uiError(msg) {
        hide($('profileLoading'));
        show($('profileErrorState'));
        var es = $('profileErrorState');
        if (es) es.classList.add('flex');

        var m = $('profileErrorStateMsg');
        if (m) m.textContent = msg || 'Something went wrong. Please try again.';

        hide($('profileContent'));
        var fv = $('footerView');
        if (fv) fv.classList.add('opacity-50', 'pointer-events-none');
    }

    function uiContent() {
        hide($('profileLoading'));
        hide($('profileErrorState'));
        var es = $('profileErrorState');
        if (es) es.classList.remove('flex');

        show($('profileContent'));
        var fv = $('footerView');
        if (fv) fv.classList.remove('opacity-50', 'pointer-events-none');
    }

    // ──────────────────────────────────────────────────────────
    // OPEN MODAL
    // ──────────────────────────────────────────────────────────
    window.openProfile = function (userId) {
        var modal = $('profileModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        currentProfile = null;
        currentUserId  = userId;
        currentMode    = 'view';

        uiLoading();
        setViewModeUI();

        var url = '/admin/users/' + encodeURIComponent(userId) + '/profile/modal';

        fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            if (!res.ok) {
                if (res.status === 404) throw new Error('User not found.');
                if (res.status === 403) throw new Error('You do not have permission to view this profile.');
                if (res.status === 401) throw new Error('Your session has expired. Please log in again.');
                throw new Error('Failed to load profile (HTTP ' + res.status + ')');
            }
            return res.json();
        })
        .then(function (json) {
            if (!json || !json.data) throw new Error('Invalid response from server.');
            currentProfile = json.data;
            fillView(json.data);
            uiContent();
        })
        .catch(function (err) {
            console.error('[profile modal] load failed:', err);
            uiError(err.message);
        });
    };

    window.retryLoadProfile = function () {
        if (currentUserId) window.openProfile(currentUserId);
    };

    window.closeProfile = function () {
        var modal = $('profileModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        currentProfile = null;
        currentUserId  = null;
        currentMode    = 'view';
    };

    // ──────────────────────────────────────────────────────────
    // FILL VIEW
    // ──────────────────────────────────────────────────────────
    function fillView(d) {
        var p = d.profile || {};

        setText('profileAvatar',    d.initials || 'U');
        setText('profileName',      d.full_name || d.username || '—');
        setText('profileUsername',  d.username ? '@' + d.username : '—');
        setText('profileCreatedAt', d.created_at_human || '—');
        setText('profileUpdatedAt', d.updated_at_human || '—');

        var statusMap = {
            active:   { badge: 'bg-emerald-400/20 text-emerald-100 ring-emerald-300/40', dot: 'bg-emerald-400', label: 'Active' },
            inactive: { badge: 'bg-gray-400/20 text-gray-100 ring-gray-300/40',         dot: 'bg-gray-400',   label: 'Inactive' },
            pending:  { badge: 'bg-amber-400/20 text-amber-100 ring-amber-300/40',      dot: 'bg-amber-400',  label: 'Pending' },
            banned:   { badge: 'bg-red-400/20 text-red-100 ring-red-300/40',            dot: 'bg-red-400',    label: 'Banned' }
        };
        var s = statusMap[d.status] || statusMap.inactive;

        var badge = $('profileStatusBadge');
        if (badge) {
            badge.className = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wider ring-1 backdrop-blur-sm ' + s.badge;
            badge.innerHTML = '<span class="h-1.5 w-1.5 rounded-full ' + s.dot + '"></span>' + s.label;
        }

        var dot = $('profileStatusDot');
        if (dot) {
            dot.className = 'absolute -bottom-0.5 -right-0.5 h-5 w-5 rounded-full border-4 border-indigo-500 ' + s.dot + ' shadow-lg';
        }

        setText('profileFirstName',  p.first_name);
        setText('profileMiddleName', p.middle_name);
        setText('profileLastName',   p.last_name);
        setText('profileBirthdate',  p.birthdate);
        setText('profileGender',     p.gender);
        setText('profileAge',        p.age);

        setText('profileEmail',    d.email);
        setText('profilePhone',    p.phone);
        setText('profileWebsite',  p.website);
        setText('profileLinkedin', p.linkedin);
        setText('profileTwitter',  p.twitter);
        setText('profileZip',      p.zip_code);
        setText('profileAddress',  p.address);
        setText('profileCity',     p.city   && p.city.name);
        setText('profileState',    p.state  && p.state.name);
        setText('profileCountry',  p.country && p.country.name);

        var hasEmployee = d.employee && Object.keys(d.employee).length > 0;
        var sec = $('employeeSection');
        if (sec) {
            if (hasEmployee) sec.classList.remove('hidden');
            else sec.classList.add('hidden');
        }

        if (hasEmployee) {
            setText('profileEmployeeId',  d.employee.employee_id);
            setText('profileDepartment',  d.employee.department && d.employee.department.name);
            setText('profileDesignation', d.employee.designation);
            setText('profileHireDate',    d.employee.hire_date);
            setText('profileEmpStatus',   d.employee.emp_status || d.employee.status);
            setText('profileReportsTo',   (d.parent && d.parent.full_name) || '—');
        }
    }

    // ──────────────────────────────────────────────────────────
    // FILL EDIT
    // ──────────────────────────────────────────────────────────
    function fillEdit(d) {
        var p = d.profile || {};

        setVal('edit_name',        d.full_name || d.username || '');
        setVal('edit_first_name',  p.first_name);
        setVal('edit_middle_name', p.middle_name);
        setVal('edit_last_name',   p.last_name);
        setVal('edit_birthdate',   p.birthdate_raw);
        setVal('edit_gender',      (p.gender_raw || '').toLowerCase());

        setVal('edit_email',    d.email);
        setVal('edit_phone',    p.phone);
        setVal('edit_website',  p.website);
        setVal('edit_linkedin', p.linkedin);
        setVal('edit_twitter',  p.twitter);
        setVal('edit_zip_code', p.zip_code);
        setVal('edit_address',  p.address);

        setVal('edit_country_id', p.country_id);

        loadStates(p.country_id, p.state_id).then(function () {
            if (p.state_id) loadCities(p.state_id, p.city_id);
        });
    }

    // ──────────────────────────────────────────────────────────
    // MODE SWITCHING (no setTimeout, no async races)
    // ──────────────────────────────────────────────────────────
    function setEditModeUI() {
        currentMode = 'edit';

        var vms = document.querySelectorAll('.view-mode');
        var ems = document.querySelectorAll('.edit-mode');
        for (var i = 0; i < vms.length; i++) vms[i].classList.add('hidden');
        for (var j = 0; j < ems.length; j++) ems[j].classList.remove('hidden');

        hide($('footerView'));
        show($('footerEdit'));

        var err = $('profileError');
        if (err) {
            err.classList.add('hidden');
            err.textContent = '';
        }
    }

    function setViewModeUI() {
        currentMode = 'view';

        var ems = document.querySelectorAll('.edit-mode');
        var vms = document.querySelectorAll('.view-mode');
        for (var i = 0; i < ems.length; i++) ems[i].classList.add('hidden');
        for (var j = 0; j < vms.length; j++) vms[j].classList.remove('hidden');

        hide($('footerEdit'));
        show($('footerView'));

        var err = $('profileError');
        if (err) {
            err.classList.add('hidden');
            err.textContent = '';
        }

        var btn = $('profileSaveBtn');
        if (btn) btn.disabled = false;
        hide($('saveSpinner'));
        show($('saveIcon'));
        var lbl = $('saveLabel');
        if (lbl) lbl.textContent = 'Save Changes';
    }

    window.enterEditMode = function () {
        if (!currentProfile) return;
        setEditModeUI();
        fillEdit(currentProfile);
    };

    window.cancelEditMode = function () {
        if (!confirm('Discard unsaved changes?')) return;
        setViewModeUI();
        if (currentProfile) fillView(currentProfile);
    };

    // ──────────────────────────────────────────────────────────
    // SAVE
    // ──────────────────────────────────────────────────────────
    window.saveProfile = function () {
        if (!currentUserId) return;

        var btn     = $('profileSaveBtn');
        var spinner = $('saveSpinner');
        var icon    = $('saveIcon');
        var label   = $('saveLabel');
        var errorEl = $('profileError');

        if (btn) btn.disabled = true;
        show(spinner);
        hide(icon);
        if (label) label.textContent = 'Saving…';
        if (errorEl) { errorEl.classList.add('hidden'); errorEl.textContent = ''; }

        function val(id) {
            var el = $(id);
            if (!el) return null;
            var v = el.value;
            v = (v == null) ? '' : String(v).trim();
            return v === '' ? null : v;
        }

        var payload = {
            email:       val('edit_email'),
            first_name:  val('edit_first_name'),
            middle_name: val('edit_middle_name'),
            last_name:   val('edit_last_name'),
            birthdate:   val('edit_birthdate'),
            gender:      val('edit_gender'),
            phone:       val('edit_phone'),
            website:     val('edit_website'),
            linkedin:    val('edit_linkedin'),
            twitter:     val('edit_twitter'),
            zip_code:    val('edit_zip_code'),
            address:     val('edit_address'),
            country_id:  val('edit_country_id'),
            state_id:    val('edit_state_id'),
            city_id:     val('edit_city_id')
        };

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var url = '/admin/users/' + encodeURIComponent(currentUserId) + '/profile/modal';

        fetch(url, {
            method: 'PUT',
            credentials: 'same-origin',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     csrfMeta ? csrfMeta.content : '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(function (res) {
            if (res.status === 422) {
                return res.json().then(function (json) {
                    showValidationErrors(json.errors || {});
                    throw new Error('VALIDATION');
                });
            }
            if (res.status === 401) throw new Error('Your session has expired. Please log in again.');
            if (res.status === 403) throw new Error('You do not have permission to update this profile.');
            if (!res.ok) throw new Error('Save failed (HTTP ' + res.status + ')');
            return res.json();
        })
        .then(function (json) {
            if (!json || !json.data) throw new Error('Invalid response from server.');
            currentProfile = json.data;
            fillView(json.data);
            setViewModeUI();
        })
        .catch(function (err) {
            console.error('[profile modal] save failed:', err);
            if (err.message !== 'VALIDATION' && errorEl) {
                errorEl.textContent = err.message || 'Unable to save changes. Please try again.';
                errorEl.classList.remove('hidden');
                if (errorEl.scrollIntoView) errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .then(function () {
            if (btn) btn.disabled = false;
            hide(spinner);
            show(icon);
            if (label) label.textContent = 'Save Changes';
        });
    };

    // ──────────────────────────────────────────────────────────
    // VALIDATION ERRORS
    // ──────────────────────────────────────────────────────────
    function showValidationErrors(errors) {
        var errorEl = $('profileError');
        if (!errorEl) return;

        var items = [];
        Object.keys(errors).forEach(function (k) {
            var v = errors[k];
            if (Array.isArray(v)) items = items.concat(v);
            else items.push(v);
        });
        if (!items.length) return;

        errorEl.innerHTML =
            '<div class="flex items-start gap-2">' +
                '<svg class="h-4 w-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>' +
                '</svg>' +
                '<div>' +
                    '<strong class="font-semibold">Please fix the following:</strong>' +
                    '<ul class="mt-1 list-disc list-inside space-y-0.5">' +
                        items.map(function (e) { return '<li>' + escapeHtml(e) + '</li>'; }).join('') +
                    '</ul>' +
                '</div>' +
            '</div>';
        errorEl.classList.remove('hidden');
        if (errorEl.scrollIntoView) errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ──────────────────────────────────────────────────────────
    // CASCADING LOCATION
    // ──────────────────────────────────────────────────────────
    function loadStates(countryId, selectedStateId) {
        var sel = $('edit_state_id');
        if (!sel) return Promise.resolve();

        sel.innerHTML = '<option value="">Select state…</option>';
        if (!countryId) return Promise.resolve();

        var url = '/api/states?country_id=' + encodeURIComponent(countryId);

        return fetch(url, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (data) {
            (data || []).forEach(function (s) {
                sel.add(new Option(s.name, s.id, false, String(s.id) === String(selectedStateId)));
            });
        })
        .catch(function (e) { console.warn('[profile modal] loadStates:', e); });
    }

    function loadCities(stateId, selectedCityId) {
        var sel = $('edit_city_id');
        if (!sel) return Promise.resolve();

        sel.innerHTML = '<option value="">Select city…</option>';
        if (!stateId) return Promise.resolve();

        var url = '/api/cities?state_id=' + encodeURIComponent(stateId);

        return fetch(url, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (data) {
            (data || []).forEach(function (c) {
                sel.add(new Option(c.name, c.id, false, String(c.id) === String(selectedCityId)));
            });
        })
        .catch(function (e) { console.warn('[profile modal] loadCities:', e); });
    }

    // ──────────────────────────────────────────────────────────
    // EVENT BINDING
    // ──────────────────────────────────────────────────────────
    function bindEvents() {
        var countrySel = $('edit_country_id');
        if (countrySel) {
            countrySel.addEventListener('change', function (e) {
                loadStates(e.target.value);
                var citySel = $('edit_city_id');
                if (citySel) citySel.innerHTML = '<option value="">Select city…</option>';
            });
        }

        var stateSel = $('edit_state_id');
        if (stateSel) {
            stateSel.addEventListener('change', function (e) {
                loadCities(e.target.value);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && currentMode === 'view') {
                var modal = $('profileModal');
                if (modal && !modal.classList.contains('hidden')) {
                    window.closeProfile();
                }
            }
        });

        var modal = $('profileModal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target.id === 'profileModal' && currentMode === 'view') {
                    window.closeProfile();
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindEvents);
    } else {
        bindEvents();
    }
})();
</script> --}}