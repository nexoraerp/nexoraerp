<script setup>
defineProps({
    modelValue: [String, Number],

    label: String,

    placeholder: String,

    error: String,

    step: {
        type: String,
        default: '0.01',
    },

    min: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits([
    'update:modelValue',
]);
</script>

<template>

<div>

    <label
        v-if="label"
        class="block text-sm font-medium text-slate-700 mb-2"
    >
        {{ label }}
    </label>

    <input

        type="number"

        :value="modelValue"

        :step="step"

        :min="min"

        :placeholder="placeholder"

        @input="
            emit(
                'update:modelValue',
                $event.target.value
            )
        "

        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"

    />

    <p
        v-if="error"
        class="text-red-500 text-sm mt-1"
    >
        {{ error }}
    </p>

</div>

</template>