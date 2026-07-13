<script setup>
defineOptions({
    inheritAttrs: false,
});

defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
    error: String,
});

defineEmits(['update:modelValue']);
</script>

<template>

    <div class="space-y-2">

        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ label }}
        </label>

        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            v-bind="$attrs"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full rounded-xl border border-slate-300 px-4 py-3
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                   outline-none transition
                   dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-blue-900/40"
        />

        <p
            v-if="error"
            class="text-sm text-red-500"
        >
            {{ error }}
        </p>

    </div>

</template>
