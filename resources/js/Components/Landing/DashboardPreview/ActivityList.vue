<script setup>

const items = [

    {
        company: 'ABC Teknoloji A.Ş.',
        title: 'Tahsilat Alındı',
        amount: '+₺12.500',
        time: '2 dk önce',
        color: 'emerald',
    },

    {
        company: 'Nova Lojistik',
        title: 'Satış Faturası',
        amount: '+₺8.420',
        time: '8 dk önce',
        color: 'blue',
    },

    {
        company: 'Atlas Mobilya',
        title: 'Kasa Gideri',
        amount: '-₺950',
        time: '18 dk önce',
        color: 'red',
    },

];

</script>

<template>

<div
    class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
>

    <div class="mb-6 flex items-center justify-between">

        <h3 class="text-lg font-bold text-slate-900">
            Son Hareketler
        </h3>

        <span
            class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500"
        >
            Canlı
        </span>

    </div>

    <div class="space-y-4">

        <div
            v-for="item in items"
            :key="item.company"
            class="flex items-center justify-between rounded-2xl border border-transparent p-4 transition hover:border-blue-100 hover:bg-slate-50"
        >

            <div class="flex items-center gap-4">

                <div
                    :class="[
                        'h-3 w-3 rounded-full',
                        item.color === 'emerald'
                            ? 'bg-emerald-500'
                            : item.color === 'blue'
                            ? 'bg-blue-500'
                            : 'bg-red-500'
                    ]"
                ></div>

                <div>

                    <div class="font-semibold text-slate-900">
                        {{ item.company }}
                    </div>

                    <div class="text-sm text-slate-500">
                        {{ item.title }}
                    </div>

                </div>

            </div>

            <div class="text-right">

                <div
                    :class="[
                        'font-bold',
                        item.amount.startsWith('-')
                            ? 'text-red-500'
                            : 'text-emerald-600'
                    ]"
                >
                    {{ item.amount }}
                </div>

                <div class="text-xs text-slate-400">
                    {{ item.time }}
                </div>

            </div>

        </div>

    </div>

</div>

</template>