<template>
    <div class="mx-auto max-w-6xl">

        
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Employees
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage all employees in your organization
                </p>
            </div>

            <router-link
                :to="{ name: 'employee.create' }"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 hover:brightness-105 active:translate-y-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Create Employee</span>
            </router-link>
        </header>

        <!-- {{-- ============================================================
             SEARCH BAR
             ============================================================ --}} -->
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <div class="flex flex-1 min-w-[260px] items-center gap-2">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>
                    <input
                        type="search"
                        v-model="searchWord"
                        placeholder="Search employees by name or department…"
                        class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-indigo-400">
                </div>

                <button
                    v-if="searchWord"
                    type="button"
                    @click="searchWord = ''"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

     
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div v-if="loading" class="flex items-center justify-center p-16">
                <div class="flex flex-col items-center gap-3">
                    <svg class="h-8 w-8 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Loading employees…</p>
                </div>
            </div>

       
            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th scope="col" class="w-16 px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Employee</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Address</th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Department</th>
                            <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr
                            v-for="employee in searchFilter"
                            :key="employee.id"
                            class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20">

                        
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ employee.id }}
                            </td>

                 
                            <td class="whitespace-nowrap px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold uppercase text-white">
                                        {{ initials(employee) }}
                                    </span>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ employee.first_name }} {{ employee.last_name }}
                                        </div>
                                        <div v-if="employee.middle_name" class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ employee.middle_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                    
                            <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-gray-400">
                                <span v-if="employee.address">{{ employee.address }}</span>
                                <span v-else class="text-xs italic text-gray-400 dark:text-gray-500">—</span>
                            </td>

                      
                            <td class="whitespace-nowrap px-4 py-3.5">
                                <span
                                    v-if="employee.department"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 dark:border-violet-800 dark:bg-violet-950/60 dark:text-violet-300">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                    </svg>
                                    {{ employee.department.name }}
                                </span>
                                <span v-else class="text-xs italic text-gray-400 dark:text-gray-500">—</span>
                            </td>

          
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <router-link
                                        :to="{ name: 'employee.edit', params: { id: employee.id } }"
                                        title="Edit employee"
                                        :aria-label="`Edit ${employee.first_name} ${employee.last_name}`"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 transition-colors hover:bg-indigo-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L16.862 4.487M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </router-link>

                                    <button
                                        type="button"
                                        title="Delete employee"
                                        :aria-label="`Delete ${employee.first_name} ${employee.last_name}`"
                                        @click="deleteEmployee(employee.id)"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:text-red-400 dark:hover:bg-red-950/40">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                   
                        <tr v-if="searchFilter.length === 0">
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No employees found</h3>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span v-if="searchWord">
                                                No results for "<strong>{{ searchWord }}</strong>". Try a different search.
                                            </span>
                                            <span v-else>
                                                Get started by creating your first employee.
                                            </span>
                                        </p>
                                    </div>
                                    <button
                                        v-if="searchWord"
                                        type="button"
                                        @click="searchWord = ''"
                                        class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                        Clear search
                                    </button>
                                    <router-link
                                        v-else
                                        :to="{ name: 'employee.create' }"
                                        class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        Create Employee
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div
                v-if="!loading && rawdata.last_page > 1"
                class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                <pagination
                    :data="rawdata"
                    :limit="3"
                    align="center"
                    @pagination-change-page="list">
                    <template #prev-nav>
                        <span class="sr-only">Previous</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </template>
                    <template #next-nav>
                        <span class="sr-only">Next</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </template>
                </pagination>
            </div>
        </div>


        <p v-if="!loading && rawdata.total" class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ rawdata.from ?? 0 }}</span>
            to
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ rawdata.to ?? 0 }}</span>
            of
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ rawdata.total }}</span>
            employees
        </p>

    </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'
import axios from 'axios'

export default {
    name: 'EmployeeIndex',

    components: { pagination },

    data() {
        return {
            loading:    false,
            rawdata:    {},
            employees:  [],
            searchWord: '',
        }
    },

    computed: {
        searchFilter() {
            const q = (this.searchWord || '').toLowerCase().trim()
            if (! q) return this.employees

            return this.employees.filter(employee => {
                const first = (employee.first_name ?? '').toLowerCase()
                const last  = (employee.last_name  ?? '').toLowerCase()
                const dept  = (employee.department?.name ?? '').toLowerCase()
                const addr  = (employee.address ?? '').toLowerCase()

                return first.includes(q) || last.includes(q) || dept.includes(q) || addr.includes(q)
            })
        },
    },

    mounted() {
        this.list()
    },

    methods: {
        // ---------- Fetch paginated list ----------
        async list(page = 1) {
            this.loading = true
            try {
                const { data } = await axios.get(`/api/employees?page=${page}`)
                this.rawdata   = data
                this.employees = data.data
            } catch (e) {
                console.error('Failed to load employees', e)
                this.rawdata   = {}
                this.employees = []
                this.$toast?.error('Failed to load employees.')
            } finally {
                this.loading = false
            }
        },

        // ---------- Fetch all (used after delete) ----------
        async getEmployees() {
            this.loading = true
            try {
                const { data } = await axios.get('/api/employees')
                this.employees = data.data ?? data
            } catch (e) {
                console.error('Failed to load employees', e)
            } finally {
                this.loading = false
            }
        },

        // ---------- Delete with confirm ----------
        async deleteEmployee(id) {
            if (! confirm('Delete this employee? This action cannot be undone.')) return

            try {
                await axios.delete(`/api/employees/${id}`)
                this.$toast?.success('Employee deleted successfully.')
                await this.list()
            } catch (e) {
                console.error('Failed to delete employee', e)
                this.$toast?.error('Failed to delete employee.')
            }
        },

        // ---------- Avatar initials helper ----------
        initials(employee) {
            const f = employee.first_name?.[0] ?? ''
            const l = employee.last_name?.[0]  ?? ''
            return (f + l).toUpperCase() || 'E'
        },
    },
}
</script>