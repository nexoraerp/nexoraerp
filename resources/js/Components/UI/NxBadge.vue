<script setup>

defineProps({

    variant: {
        type: String,
        default: 'primary',
    },

});

</script>

<template>

<span
    class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
>

    <slot />

</span>

</template>