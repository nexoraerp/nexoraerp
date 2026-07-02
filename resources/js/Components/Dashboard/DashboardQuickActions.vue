<script setup>
import { Link } from '@inertiajs/vue3';

const actions = [
    {
        title: 'Yeni Cari',
        icon: '👥',
        color: 'bg-blue-50 text-blue-600',
        href: route('customers.create'),
    },
    {
        title: 'Yeni Ürün',
        icon: '📦',
        color: 'bg-emerald-50 text-emerald-600',
        href: route('products.create'),
    },
    {
        title: 'Stok Girişi',
        icon: '📥',
        color: 'bg-purple-50 text-purple-600',
        href: route('stock-movements.create'),
    },
    {
        title: 'Yeni Satış',
        icon: '🛒',
        color: 'bg-orange-50 text-orange-600',
        href: route('sales.create'),
    },
];
</script>

<template>

<div class="bg-white rounded-3xl border shadow-sm p-8">

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-xl font-bold text-slate-800">
            ⚡ Hızlı İşlemler
        </h2>

    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-5">

        <Link
            v-for="action in actions"
            :key="action.title"
            :href="action.href"
            class="group"
        >

            <div
                class="rounded-2xl border p-6
                       hover:shadow-lg
                       hover:-translate-y-1
                       hover:border-blue-200
                       transition-all duration-300"
            >

                <div
                    class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl"
                    :class="action.color"
                >
                    {{ action.icon }}
                </div>

                <h3 class="mt-5 font-semibold text-slate-800">

                    {{ action.title }}

                </h3>

                <p class="mt-2 text-sm text-slate-500">

                    Oluştur

                </p>

            </div>

        </Link>

    </div>

</div>

</template>