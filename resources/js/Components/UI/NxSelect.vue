<script setup>
defineProps({

    modelValue: [String, Number],

    label: String,

    options: {
        type: Array,
        default: () => [],
    },

    optionLabel: {
        type: String,
        default: 'label',
    },

    optionValue: {
        type: String,
        default: 'value',
    },

    placeholder: {
        type: String,
        default: 'Seçiniz...',
    },

    error: String,

});

defineEmits([
    'update:modelValue',
]);
</script>

<template>

<div class="space-y-2">

    <label
        v-if="label"
        class="text-sm font-medium text-slate-700"
    >
        {{ label }}
    </label>

    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="w-full rounded-xl border border-slate-300
               bg-white text-slate-900
               px-4 py-3
               focus:border-blue-500
               focus:ring-2 focus:ring-blue-200
               outline-none transition"
    >

        <option
            value=""
            class="text-slate-900 bg-white"
        >
            {{ placeholder }}
        </option>

        <option
            v-for="option in options"
            :key="option[optionValue]"
            :value="option[optionValue]"
            class="text-slate-900 bg-white"
        >
            {{ option[optionLabel] }}
        </option>

    </select>

    <p
        v-if="error"
        class="text-sm text-red-500"
    >
        {{ error }}
    </p>

</div>

</template>