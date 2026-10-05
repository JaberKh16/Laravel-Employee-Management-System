{{-- ══════════════════════════════════════════════════════════════
     PROFILE MODAL — Modern Design
     ══════════════════════════════════════════════════════════════ --}}
<div id="profileModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="profileName">

    <div class="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-800 dark:ring-white/10">

        {{-- ════════════════════════════════════════════════════════
             HERO HEADER
             ════════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-500 to-purple-600 px-6 pb-6 pt-8 sm:px-8">
            {{-- Decorative circles --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-white/5"></div>

            {{-- Close button --}}
            <button type="button" onclick="closeProfile()"
                    class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center">
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    <div id="profileAvatar"
                         class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 text-2xl font-bold uppercase text-white shadow-inner ring-4 ring-white/20 backdrop-blur-sm">
                        JD
                    </div>
                    {{-- Online dot --}}
                    <span id="profileStatusDot"
                          class="absolute -bottom-0.5 -right-0.5 h-5 w-5 rounded-full border-4 border-indigo-500 bg-emerald-400 shadow-lg"></span>
                </div>

                {{-- Name + Username + Meta --}}
                <div class="min-w-0 flex-1 text-white">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 id="profileName" class="truncate text-2xl font-bold tracking-tight">John Doe</h2>

                        {{-- Status pill --}}
                        <span id="profileStatusBadge"
                              class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/20 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-100 ring-1 ring-emerald-300/40 backdrop-blur-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                            Active
                        </span>
                    </div>

                    <p id="profileUsername" class="mt-1 truncate text-sm font-medium text-white/80">@jdoe</p>

                    {{-- Created / Updated --}}
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

            {{-- Loading --}}
            <div id="profileLoading" class="flex items-center justify-center py-16">
                @include('components.loading.inline', ['size' => 'lg'])
            </div>

            {{-- Content --}}
            <div id="profileContent" class="hidden space-y-6">

                {{-- ──────────────────────────────────────────────
                     SECTION 1: PERSONAL DETAILS
                     ────────────────────────────────────────────── --}}
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
                            <dd id="profileFirstName" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Middle Name</dt>
                            <dd id="profileMiddleName" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Last Name</dt>
                            <dd id="profileLastName" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Birthdate</dt>
                            <dd id="profileBirthdate" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Gender</dt>
                            <dd id="profileGender" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Age</dt>
                            <dd id="profileAge" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                    </dl>
                </section>

                {{-- ──────────────────────────────────────────────
                     SECTION 2: CONTACT DETAILS
                     ────────────────────────────────────────────── --}}
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
                            <dd id="profileEmail" class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Phone</dt>
                            <dd id="profilePhone" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Website</dt>
                            <dd id="profileWebsite" class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">LinkedIn</dt>
                            <dd id="profileLinkedin" class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Twitter</dt>
                            <dd id="profileTwitter" class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Zip Code</dt>
                            <dd id="profileZip" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:col-span-2 lg:col-span-3">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Address</dt>
                            <dd id="profileAddress" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">City</dt>
                            <dd id="profileCity" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">State</dt>
                            <dd id="profileState" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Country</dt>
                            <dd id="profileCountry" class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">—</dd>
                        </div>
                    </dl>
                </section>

                {{-- ──────────────────────────────────────────────
                     SECTION 3: OFFICIAL DETAILS
                     ────────────────────────────────────────────── --}}
                <section id="employeeSection" class="hidden">
                    <header class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-1.591 2.162A48.35 48.35 0 0112 21.75a48.35 48.35 0 01-6.659-.465 2.25 2.25 0 01-1.591-2.162V14.15M18 18.75h.008v.008H18v-.008zm0-3h.008v.008H18v-.008zm0-3h.008v.008H18v-.008zm0-3h.008v.008H18v-.008zm-12 9h.008v.008H6v-.008zm0-3h.008v.008H6v-.008zm0-3h.008v.008H6v-.008zm0-3h.008v.008H6v-.008zM18 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm-3-3a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
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
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
             FOOTER
             ════════════════════════════════════════════════════════ --}}
        <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800 sm:px-8">
            <button type="button" onclick="closeProfile()"
                    class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Close
            </button>
            <a id="profileEditLink" href="#"
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
                Edit User
            </a>
        </div>
    </div>
</div>
