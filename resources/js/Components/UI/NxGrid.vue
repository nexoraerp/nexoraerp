<script setup>

defineProps({

    cols: {
        type: Number,
        default: 2,
    },

    gap: {
        type: String,
        default: 'gap-16',
    },

});

</script>

<template>

<div
    :class="[
        'grid items-center',
        cols === 2 ? 'lg:grid-cols-2' : '',
        cols === 3 ? 'lg:grid-cols-3' : '',
        cols === 4 ? 'lg:grid-cols-4' : '',
        gap,
    ]"
>

    <slot />

</div>

</template>