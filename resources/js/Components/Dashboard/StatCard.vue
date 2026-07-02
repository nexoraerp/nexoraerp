<script setup>
defineProps({
    title: String,
    value: String,
    change: String,
    positive: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>

<div
    class="group rounded-3xl border border-slate-200 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl"
>

    <!-- Üst -->

    <div class="flex items-start justify-between">

        <div>

            <p
                class="text-sm font-medium text-slate-500"
            >
                {{ title }}
            </p>

            <h3
                class="mt-3 text-3xl font-bold tracking-tight text-slate-900"
            >
                {{ value }}
            </h3>

        </div>

        <!-- Durum -->

        <div
            :class="[
                'rounded-xl px-3 py-1 text-sm font-semibold',
                positive
                    ? 'bg-emerald-50 text-emerald-600'
                    : 'bg-red-50 text-red-600'
            ]"
        >
            {{ change }}
        </div>

    </div>

    <!-- Alt -->

    <div
        class="mt-6 flex items-center justify-between"
    >

        <span
            class="text-xs text-slate-400"
        >
            Son 24 Saat
        </span>

        <div
            class="h-2 w-20 overflow-hidden rounded-full bg-slate-100"
        >

            <div
                class="h-full w-3/4 rounded-full bg-blue-600"
            ></div>

        </div>

    </div>

</div>

</template>