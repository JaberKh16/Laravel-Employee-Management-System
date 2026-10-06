<template>
    <div>
        <!-- Filter toggle -->
        <button
            type="button"
            @click="toggle"
            :aria-expanded="isOpen ? 'true' : 'false'"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
            </svg>
            <span>Filter</span>
            <svg
                :class="['h-3.5 w-3.5 transition-transform duration-300', isOpen ? 'rotate-180' : '']"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>

        <!-- Panel -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2">
            <div
                v-if="isOpen"
                class="mt-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <!-- Panel header -->
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Advanced Filters</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Refine by designation, department, status, or dates</p>
                        </div>
                    </div>
                    <button type="button" @click="close"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Radio criteria -->
                <div class="mb-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Search Criteria</p>
                    <div class="flex flex-wrap gap-2">
                        <div v-for="c in criterias" :key="c.key" class="relative">
                            <input
                                type="radio"
                                :id="`criteria_${c.key}`"
                                :value="c.key"
                                v-model="activeCriteria"
                                class="criteria-radio">
                            <label
                                :for="`criteria_${c.key}`"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ c.label }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Dynamic fields -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    <!-- DESIGNATION -->
                    <div v-show="activeCriteria === 'designation'">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Designation</label>
                        <input
                            v-model="filters.designation"
                            type="text"
                            placeholder="e.g. Senior Engineer"
                            class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- DEPARTMENT -->
                    <div v-show="activeCriteria === 'department'">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Department</label>
                        <select
                            v-model="filters.department_id"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All departments</option>
                            <option v-for="d in meta.departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                        </select>
                    </div>

                    <!-- EMPLOYMENT TYPE -->
                    <div v-show="activeCriteria === 'employment_type'">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Employment Type</label>
                        <select
                            v-model="filters.employment_type"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All types</option>
                            <option v-for="t in meta.employment_types" :key="t.value" :value="t.value">{{ t.label }}</option>
                        </select>
                    </div>

                    <!-- STATUS -->
                    <div v-show="activeCriteria === 'status'">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</label>
                        <select
                            v-model="filters.status"
                            class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All statuses</option>
                            <option v-for="s in meta.statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                    </div>

                    <!-- HIRED DATE RANGE -->
                    <div v-show="activeCriteria === 'hired'" class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Hired Date Range</label>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <input
                                v-model="filters.hired_from"
                                type="date"
                                class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <input
                                v-model="filters.hired_to"
                                type="date"
                                class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- SALARY RANGE -->
                    <div v-show="activeCriteria === 'salary'" class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Salary Range</label>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <input v-model="filters.salary_min" type="number" step="0.01" placeholder="Min"
                                   class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <input v-model="filters.salary_max" type="number" step="0.01" placeholder="Max"
                                   class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- SORT -->
                    <div v-show="activeCriteria === 'sort'" class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sort</label>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <select v-model="filters.sort"
                                    class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Default</option>
                                <option value="designation">Designation</option>
                                <option value="basic_salary">Salary</option>
                                <option value="date_hired">Hired Date</option>
                                <option value="created_at">Created</option>
                            </select>
                            <select v-model="filters.direction"
                                    class="block w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="desc">Descending</option>
                                <option value="asc">Ascending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="apply"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                            Apply Filters
                        </button>
                        <button type="button" @click="reset"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                            Reset
                        </button>
                    </div>
                    <div v-if="activeChips.length" class="flex flex-wrap gap-1.5">
                        <span v-for="chip in activeChips" :key="chip"
                              class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300">
                            {{ chip }}
                        </span>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: 'EmployeeFilters',
    props: {
        meta:    { type: Object, default: () => ({ departments: [], employment_types: [], statuses: [] }) },
        modelValue: { type: Object, default: () => ({}) },
    },
    emits: ['update:modelValue', 'apply'],

    data() {
        return {
            isOpen: false,
            activeCriteria: 'designation',
            criterias: [
                { key: 'designation',     label: 'Designation' },
                { key: 'department',      label: 'Department' },
                { key: 'employment_type', label: 'Employment Type' },
                { key: 'status',          label: 'Status' },
                { key: 'hired',           label: 'Hired Date' },
                { key: 'salary',          label: 'Salary' },
                { key: 'sort',            label: 'Sorting' },
            ],
            filters: {
                designation:     '',
                department_id:   '',
                employment_type: '',
                status:          '',
                hired_from:      '',
                hired_to:        '',
                salary_min:      '',
                salary_max:      '',
                sort:            '',
                direction:       'desc',
            },
        }
    },

    computed: {
        activeChips() {
            const chips = []
            const f = this.filters
            if (f.designation)     chips.push(`Designation: ${f.designation}`)
            if (f.department_id)   chips.push(`Department: #${f.department_id}`)
            if (f.employment_type) chips.push(`Type: ${f.employment_type}`)
            if (f.status)          chips.push(`Status: ${f.status}`)
            if (f.hired_from)      chips.push(`Hired from: ${f.hired_from}`)
            if (f.hired_to)        chips.push(`Hired to: ${f.hired_to}`)
            if (f.salary_min)      chips.push(`Salary ≥ ${f.salary_min}`)
            if (f.salary_max)      chips.push(`Salary ≤ ${f.salary_max}`)
            return chips
        },
    },

    methods: {
        toggle() { this.isOpen ? this.close() : this.open() },
        open()   { this.isOpen = true },
        close()  { this.isOpen = false },

        apply() {
            this.$emit('update:modelValue', { ...this.filters })
            this.$emit('apply')
        },

        reset() {
            this.filters = {
                designation: '', department_id: '', employment_type: '',
                status: '', hired_from: '', hired_to: '',
                salary_min: '', salary_max: '', sort: '', direction: 'desc',
            }
            this.$emit('update:modelValue', { ...this.filters })
            this.$emit('apply')
        },
    },
}
</script>

<style scoped>
.criteria-radio:checked + label {
    background-color: #4f46e5;
    color: #fff;
    border-color: #4f46e5;
    box-shadow: 0 4px 10px rgba(79,70,229,0.3);
}
.criteria-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
</style>