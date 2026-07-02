<script setup>

defineProps({

    title: String,

    description: String,

    center: {
        type: Boolean,
        default: false,
    },

});

</script>

<template>

<div
    :class="[
        center
            ? 'mx-auto max-w-3xl text-center'
            : 'max-w-3xl'
    ]"
>

    <h2
        class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900"
    >

        {{ title }}

    </h2>

    <p
        class="mt-6 text-lg leading-8 text-slate-600"
    >

        {{ description }}

    </p>

</div>

</template>