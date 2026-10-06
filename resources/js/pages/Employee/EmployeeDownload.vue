<template>
    <div class="relative" ref="wrap">
        <button
            type="button"
            @click="toggle"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            <span class="hidden md:inline">Download</span>
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>

        <div
            v-if="isOpen"
            class="absolute right-0 z-20 mt-2 w-44 rounded-xl border border-gray-200 bg-white p-1.5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <a
                v-for="fmt in formats"
                :key="fmt.value"
                :href="buildUrl(fmt.value)"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i :class="[fmt.icon, fmt.color]"></i> {{ fmt.label }}
            </a>
            <div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>
            <button
                type="button"
                @click="print"
                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fa-solid fa-print text-slate-600"></i> Print
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EmployeeDownload',
    props: {
        filters: { type: Object, default: () => ({}) },
        search:  { type: String, default: '' },
        endpoint: { type: String, default: '/api/employees/export' },
    },

    data() {
        return {
            isOpen: false,
            formats: [
                { value: 'csv',  label: 'CSV',          icon: 'fa-solid fa-file-csv',   color: 'text-emerald-600' },
                { value: 'xlsx', label: 'Excel (XLSX)', icon: 'fa-solid fa-file-excel', color: 'text-green-600' },
                { value: 'pdf',  label: 'PDF',          icon: 'fa-solid fa-file-pdf',   color: 'text-red-600' },
                { value: 'json', label: 'JSON',         icon: 'fa-solid fa-file-code',  color: 'text-amber-600' },
            ],
        }
    },

    mounted() {
        document.addEventListener('click', this.onOutsideClick)
    },
    beforeUnmount() {
        document.removeEventListener('click', this.onOutsideClick)
    },

    methods: {
        toggle() { this.isOpen = !this.isOpen },
        onOutsideClick(e) {
            if (this.$refs.wrap && !this.$refs.wrap.contains(e.target)) {
                this.isOpen = false
            }
        },

        buildUrl(format) {
            const params = new URLSearchParams()

            params.set('format', format)
            if (this.search) params.set('search', this.search)

            Object.entries(this.filters || {}).forEach(([k, v]) => {
                if (v !== '' && v !== null && v !== undefined) {
                    params.set(k, v)
                }
            })

            return `${this.endpoint}?${params.toString()}`
        },

        print() {
            window.print()
        },
    },
}
</script>