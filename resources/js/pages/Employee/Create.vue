<template>
    <div class="mx-auto max-w-3xl">

        <!-- ══════════════════════════════════════════════════════════
             HEADER
             ══════════════════════════════════════════════════════════ -->
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Create Employee
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Add a new employee to your organization
                </p>
            </div>

            <router-link
                :to="{ name: 'employee.index' }"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Back</span>
            </router-link>
        </header>

        <!-- Global error banner -->
        <div v-if="globalError" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            {{ globalError }}
        </div>

        <!-- ══════════════════════════════════════════════════════════
             FORM CARD
             ══════════════════════════════════════════════════════════ -->
        <form @submit.prevent="storeEmployee"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Employee Information
                </h2>
            </div>

            <div class="space-y-6 p-6">

                <!-- ═══════════════════════════════════════════════
                     NAME ROW
                     ═══════════════════════════════════════════════ -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <label for="first_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="first_name"
                            type="text"
                            v-model="employeeForm.first_name"
                            required
                            autofocus
                            autocomplete="given-name"
                            placeholder="John"
                            class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                            :class="errors.first_name
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                        <p v-if="errors.first_name" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.first_name[0] }}
                        </p>
                    </div>

                    <div>
                        <label for="last_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="last_name"
                            type="text"
                            v-model="employeeForm.last_name"
                            required
                            autocomplete="family-name"
                            placeholder="Doe"
                            class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                            :class="errors.last_name
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                        <p v-if="errors.last_name" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.last_name[0] }}
                        </p>
                    </div>

                    <div>
                        <label for="middle_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Middle Name
                        </label>
                        <input
                            id="middle_name"
                            type="text"
                            v-model="employeeForm.middle_name"
                            autocomplete="additional-name"
                            placeholder="Michael"
                            class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                            :class="errors.middle_name
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                        <p v-if="errors.middle_name" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.middle_name[0] }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <!-- ═══════════════════════════════════════════════
                     ADDRESS
                     ═══════════════════════════════════════════════ -->
                <div>
                    <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Address
                    </label>
                    <input
                        id="address"
                        type="text"
                        v-model="employeeForm.address"
                        autocomplete="street-address"
                        placeholder="123 Main St"
                        class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                        :class="errors.address
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                            : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                    <p v-if="errors.address" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                        {{ errors.address[0] }}
                    </p>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <!-- ═══════════════════════════════════════════════
                     LOCATION
                     ═══════════════════════════════════════════════ -->
                <div>
                    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        Location
                    </h3>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                        <!-- Country -->
                        <div>
                            <label for="country_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Country
                            </label>
                            <select
                                id="country_id"
                                v-model="employeeForm.country_id"
                                @change="onCountryChange"
                                class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white"
                                :class="errors.country_id
                                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                    : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                                <option value="">Select Country</option>
                                <option v-for="country in countries" :key="country.id" :value="country.id">
                                    {{ country.name }}
                                </option>
                            </select>
                            <p v-if="errors.country_id" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                                {{ errors.country_id[0] }}
                            </p>
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                State
                            </label>
                            <select
                                id="state_id"
                                v-model="employeeForm.state_id"
                                @change="onStateChange"
                                :disabled="!employeeForm.country_id"
                                class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800"
                                :class="errors.state_id
                                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                    : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                                <option value="">
                                    {{ employeeForm.country_id ? 'Select State' : 'Select a country first' }}
                                </option>
                                <option v-for="state in filteredStates" :key="state.id" :value="state.id">
                                    {{ state.name }}
                                </option>
                            </select>
                            <p v-if="errors.state_id" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                                {{ errors.state_id[0] }}
                            </p>
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                City
                            </label>
                            <select
                                id="city_id"
                                v-model="employeeForm.city_id"
                                :disabled="!employeeForm.state_id"
                                class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800"
                                :class="errors.city_id
                                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                    : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                                <option value="">
                                    {{ employeeForm.state_id ? 'Select City' : 'Select a state first' }}
                                </option>
                                <option v-for="city in filteredCities" :key="city.id" :value="city.id">
                                    {{ city.name }}
                                </option>
                            </select>
                            <p v-if="errors.city_id" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                                {{ errors.city_id[0] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <!-- ═══════════════════════════════════════════════
                     ZIP + DEPARTMENT
                     ═══════════════════════════════════════════════ -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="zip_code" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Zip Code
                        </label>
                        <input
                            id="zip_code"
                            type="text"
                            v-model="employeeForm.zip_code"
                            autocomplete="postal-code"
                            placeholder="12345"
                            class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"
                            :class="errors.zip_code
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                        <p v-if="errors.zip_code" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.zip_code[0] }}
                        </p>
                    </div>

                    <div>
                        <label for="department_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Department
                        </label>
                        <select
                            id="department_id"
                            v-model="employeeForm.department_id"
                            class="block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white"
                            :class="errors.department_id
                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                                : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400'">
                            <option value="">Select Department</option>
                            <option v-for="department in departments" :key="department.id" :value="department.id">
                                {{ department.name }}
                            </option>
                        </select>
                        <p v-if="errors.department_id" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.department_id[0] }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <!-- ═══════════════════════════════════════════════
                     DATES
                     ═══════════════════════════════════════════════ -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="birthdate" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Birthdate
                        </label>
                        <datepicker
                            v-model="employeeForm.birthdate"
                            input-class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-indigo-400"
                            format="yyyy-MM-dd"
                            :append-to-body="true"
                        />
                        <p v-if="errors.birthdate" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.birthdate[0] }}
                        </p>
                    </div>

                    <div>
                        <label for="date_hired" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Date Hired
                        </label>
                        <datepicker
                            v-model="employeeForm.date_hired"
                            input-class="block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-indigo-400"
                            format="yyyy-MM-dd"
                            :append-to-body="true"
                        />
                        <p v-if="errors.date_hired" class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                            {{ errors.date_hired[0] }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════════════════════════════
                 FOOTER ACTIONS
                 ══════════════════════════════════════════════════════════ -->
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <router-link
                    :to="{ name: 'employee.index' }"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </router-link>

                <button
                    type="submit"
                    :disabled="employeeForm.busy"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    <svg v-if="employeeForm.busy" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>{{ employeeForm.busy ? 'Creating…' : 'Create Employee' }}</span>
                </button>
            </div>

        </form>

    </div>
</template>

<script>
import Form from 'vform'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import axios from 'axios'

const API = '/api/v1'

export default {
    name: 'EmployeeCreate',

    components: { Datepicker },

    data() {
        return {
            globalError: null,

            employeeForm: new Form({
                first_name:    '',
                middle_name:   '',
                last_name:     '',
                address:       '',
                department_id: '',
                country_id:    '',
                city_id:       '',
                state_id:      '',
                zip_code:      '',
                birthdate:     null,
                date_hired:    null,
            }),

            countries:   [],
            states:      [],
            cities:      [],
            departments: [],
        }
    },

    computed: {
        // vform stores validation errors; proxy for cleaner template refs
        errors() {
            return this.employeeForm.errors.errors
        },

        // Filter states by selected country (only if the API exposes country_id)
        filteredStates() {
            if (!this.employeeForm.country_id) return this.states
            return this.states.filter(s => {
                if (s.country_id == null) return true
                return String(s.country_id) === String(this.employeeForm.country_id)
            })
        },

        filteredCities() {
            if (!this.employeeForm.state_id) return this.cities
            return this.cities.filter(c => {
                if (c.state_id == null) return true
                return String(c.state_id) === String(this.employeeForm.state_id)
            })
        },
    },

    mounted() {
        this.loadReferenceData()
    },

    methods: {
        // ─────────────────────────────────────────────
        // Reference data loader
        // ─────────────────────────────────────────────
        async loadReferenceData() {
            const endpoints = [
                { key: 'countries',   url: `${API}/countries` },
                { key: 'states',      url: `${API}/states` },
                { key: 'cities',      url: `${API}/cities` },
                { key: 'departments', url: `${API}/departments` },
            ]

            await Promise.all(endpoints.map(async ({ key, url }) => {
                try {
                    const { data } = await axios.get(url, {
                        headers: { Accept: 'application/json' },
                    })
                    // Accept { data: [...] }, paginator meta, or plain array
                    if (Array.isArray(data)) {
                        this[key] = data
                    } else if (Array.isArray(data?.data)) {
                        this[key] = data.data
                    } else if (Array.isArray(data?.meta?.data)) {
                        this[key] = data.meta.data
                    } else {
                        this[key] = []
                    }
                } catch (e) {
                    console.warn(`Failed to load ${key}`, e?.response ?? e)
                    this[key] = []
                }
            }))
        },

        // ─────────────────────────────────────────────
        // Cascade handlers
        // ─────────────────────────────────────────────
        onCountryChange() {
            this.employeeForm.state_id = ''
            this.employeeForm.city_id  = ''
        },

        onStateChange() {
            this.employeeForm.city_id = ''
        },

        // ─────────────────────────────────────────────
        // Submit
        // ─────────────────────────────────────────────
        async storeEmployee() {
            this.globalError = null

            const payload = { ...this.employeeForm.data() }

            // Format dates → YYYY-MM-DD
            payload.birthdate  = this.formatDate(payload.birthdate)
            payload.date_hired = this.formatDate(payload.date_hired)

            // Empty strings → null (so nullable backend rules pass)
            Object.keys(payload).forEach(k => {
                if (payload[k] === '') payload[k] = null
            })

            try {
                await this.employeeForm
                    .transform(() => payload)
                    .post(`${API}/employees`)

                this.$router.push({ name: 'employee.index' })
            } catch (e) {
                const status = e?.response?.status
                if (status === 422) {
                    // vform already populated this.employeeForm.errors
                    // Nothing else to do — errors render inline
                } else if (status === 401 || status === 403) {
                    this.globalError = 'You are not authorised to create employees.'
                } else {
                    this.globalError = e?.response?.data?.message
                        ?? 'Failed to create employee. Please try again.'
                }
                console.warn('Create failed', e?.response?.data ?? e)
            }
        },

        formatDate(value) {
            if (!value) return null
            const d = value instanceof Date ? value : new Date(value)
            if (isNaN(d.getTime())) return null
            const yyyy = d.getFullYear()
            const mm   = String(d.getMonth() + 1).padStart(2, '0')
            const dd   = String(d.getDate()).padStart(2, '0')
            return `${yyyy}-${mm}-${dd}`
        },
    },
}
</script>

<style scoped>
/* Nothing needed — Tailwind handles everything */
</style>