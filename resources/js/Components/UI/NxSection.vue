<script setup>

defineProps({

    padding: {
        type: String,
        default: 'py-28',
    },

});

</script>

<template>

<section :class="padding">

    <slot />

</section>

</template>