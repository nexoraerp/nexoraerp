<script setup>

defineProps({

    title: String,

    value: String,

    change: String,

});

</script>

<template>

<div
    class="rounded-2xl border border-slate-200 bg-slate-50 p-5"
>

    <p
        class="text-sm text-slate-500"
    >
        {{ title }}
    </p>

    <h3
        class="mt-3 text-3xl font-black text-slate-900"
    >
        {{ value }}
    </h3>

    <span
        class="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
    >
        ▲ {{ change }}
    </span>

</div>

</template>