<script setup>

defineProps({

    fluid: {
        type: Boolean,
        default: false,
    },

});

</script>

<template>

<div
    :class="[
        fluid
            ? 'w-full px-6 lg:px-8'
            : 'mx-auto max-w-7xl px-6 lg:px-8'
    ]"
>

    <slot />

</div>

</template>