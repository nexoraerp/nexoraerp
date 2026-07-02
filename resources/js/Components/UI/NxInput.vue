<script setup>
defineProps({
    modelValue: String,
    label: String,
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
});

defineEmits(['update:modelValue']);
</script>

<template>

    <div class="space-y-2">

        <label class="text-sm font-medium text-slate-700">
            {{ label }}
        </label>

        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full rounded-xl border border-slate-300 px-4 py-3
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                   outline-none transition"
        />

    </div>

</template>