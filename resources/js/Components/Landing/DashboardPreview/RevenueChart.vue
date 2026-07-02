<script setup>

const data = [
    52, 68, 71, 66, 80,
    74, 88, 91, 79, 82,
    95, 100, 92, 105, 97,
    110, 118, 122, 119, 130,
    126, 140, 136, 148, 144,
    152, 158, 164, 170, 182
];

</script>

<template>

<div
    class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
>

    <!-- Header -->

    <div
        class="mb-8 flex items-center justify-between"
    >

        <div>

            <h3
                class="text-lg font-bold text-slate-900"
            >
                Satış Performansı
            </h3>

            <p
                class="mt-1 text-sm text-slate-500"
            >
                Son 30 günlük satış trendi
            </p>

        </div>

        <div
            class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600"
        >
            Son 30 Gün
        </div>

    </div>

    <!-- Grafik -->

    <div
        class="flex h-56 items-end gap-2"
    >

        <div
            v-for="(item,index) in data"
            :key="index"
            class="flex-1 rounded-full bg-gradient-to-t from-blue-600 to-blue-400 transition-all duration-300 hover:opacity-80"
            :style="{
                height:item+'px'
            }"
        ></div>

    </div>

</div>

</template>