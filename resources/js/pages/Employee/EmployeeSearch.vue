<template>
    <div class="flex flex-1 min-w-[260px] items-center gap-2">
        <div class="relative flex-1">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
            </span>
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                type="search"
                :placeholder="placeholder"
                class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
        </div>

        <button
            v-if="modelValue"
            type="button"
            @click="$emit('update:modelValue', '')"
            class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
        </button>
    </div>
</template>

<script>
export default {
    name: 'EmployeeSearch',
    props: {
        modelValue:   { type: String, default: '' },
        placeholder:  { type: String, default: 'Search employees…' },
    },
    emits: ['update:modelValue'],
}
</script>