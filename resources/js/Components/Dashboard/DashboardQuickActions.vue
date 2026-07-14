<script setup>
import { Link } from '@inertiajs/vue3';
import { PackagePlus, ReceiptText, UserPlus, Warehouse } from 'lucide-vue-next';

const actions = [
    {
        title: 'Yeni Cari',
        icon: UserPlus,
        color: 'bg-blue-50 text-blue-600',
        href: route('customers.create'),
    },
    {
        title: 'Yeni Ürün',
        icon: PackagePlus,
        color: 'bg-emerald-50 text-emerald-600',
        href: route('products.create'),
    },
    {
        title: 'Stok Girişi',
        icon: Warehouse,
        color: 'bg-purple-50 text-purple-600',
        href: route('stock-movements.create'),
    },
    {
        title: 'Yeni Satış',
        icon: ReceiptText,
        color: 'bg-orange-50 text-orange-600',
        href: route('sales.create'),
    },
];
</script>

<template>

<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">

    <div class="mb-4 flex items-center justify-between sm:mb-6">

        <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 sm:text-xl">
            Hızlı İşlemler
        </h2>

    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4 xl:gap-5">

        <Link
            v-for="action in actions"
            :key="action.title"
            :href="action.href"
            class="group"
        >

            <div
                class="rounded-2xl border border-slate-200 p-4
                       hover:shadow-lg
                       hover:border-blue-200
                       transition-all duration-300 dark:border-slate-800 sm:p-6 lg:hover:-translate-y-1"
            >

                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl sm:h-14 sm:w-14"
                    :class="action.color"
                >
                    <component
                        :is="action.icon"
                        class="h-5 w-5 sm:h-7 sm:w-7"
                    />
                </div>

                <h3 class="mt-4 text-sm font-black text-slate-800 dark:text-slate-100 sm:mt-5 sm:text-base">

                    {{ action.title }}

                </h3>

                <p class="mt-1 text-xs font-semibold text-slate-500 sm:mt-2 sm:text-sm">

                    Oluştur

                </p>

            </div>

        </Link>

    </div>

</div>

</template>
