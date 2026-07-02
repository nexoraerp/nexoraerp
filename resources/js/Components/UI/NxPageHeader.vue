<script setup>

defineProps({

    title: String,

    subtitle: String,

});

</script>

<template>

<div
    class="flex justify-between items-center mb-8">

    <div>

        <h1
            class="text-4xl font-bold text-slate-800">

            {{ title }}

        </h1>

        <p
            class="text-slate-500 mt-2">

            {{ subtitle }}

        </p>

    </div>

    <slot />

</div>

</template>