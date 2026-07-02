<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },

    variant: {
        type: String,
        default: 'primary',
    },

    size: {
        type: String,
        default: 'md',
    },

    loading: {
        type: Boolean,
        default: false,
    },

    disabled: {
        type: Boolean,
        default: false,
    },

    block: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() => {

    const base = [
        'inline-flex',
        'items-center',
        'justify-center',
        'rounded-2xl',
        'font-semibold',
        'transition-all',
        'duration-300',
        'focus:outline-none',
        'focus:ring-4',
        'disabled:opacity-60',
        'disabled:cursor-not-allowed',
    ];

    const sizes = {
        sm: 'px-4 py-2 text-sm',
        md: 'px-6 py-3 text-sm',
        lg: 'px-8 py-4 text-base',
    };

    const variants = {

        primary:
            'bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-500/20 focus:ring-blue-200',

        secondary:
            'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 focus:ring-slate-200',

        ghost:
            'bg-transparent text-slate-700 hover:bg-slate-100 focus:ring-slate-200',

        success:
            'bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 focus:ring-emerald-200',

        danger:
            'bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-500/20 focus:ring-red-200',
    };

    return [
        ...base,
        sizes[props.size],
        variants[props.variant],
        props.block ? 'w-full' : '',
    ];

});
</script>

<template>

<button
    :type="type"
    :disabled="disabled || loading"
    :class="classes"
>

    <svg
        v-if="loading"
        class="mr-2 h-5 w-5 animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        />

        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        />
    </svg>

    <slot />

</button>

</template>