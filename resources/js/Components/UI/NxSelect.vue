<script setup>
const props = defineProps({

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

const optionKey = (option) => {
    return typeof option === 'object'
        ? option[props.optionValue]
        : option;
};

const optionText = (option) => {
    return typeof option === 'object'
        ? option[props.optionLabel]
        : option;
};
</script>

<template>

<div class="space-y-2">

    <label
        v-if="label"
        class="text-sm font-medium text-slate-700 dark:text-slate-300"
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
               outline-none transition
               dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-blue-900/40"
    >

        <option
            value=""
            class="text-slate-900 bg-white dark:bg-slate-900 dark:text-slate-100"
        >
            {{ placeholder }}
        </option>

        <option
            v-for="option in options"
            :key="optionKey(option)"
            :value="optionKey(option)"
            class="text-slate-900 bg-white dark:bg-slate-900 dark:text-slate-100"
        >
            {{ optionText(option) }}
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
