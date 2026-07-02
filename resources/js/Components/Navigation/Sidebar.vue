<script setup>
import { Link, usePage } from '@inertiajs/vue3';

import {
    LayoutDashboard,
    Users,
    Package,
    Boxes,
    ReceiptText,
    Banknote,
    Wallet,
    BarChart3,
    Settings,
} from 'lucide-vue-next';

const page = usePage();

const groups = [

    {
        title: 'GENEL',

        items: [
            {
                title: 'Dashboard',
                icon: LayoutDashboard,
                route: 'dashboard',
                prefix: '/dashboard',
            },
        ],
    },

    {
        title: 'SATIŞ',

        items: [
            {
                title: 'Cariler',
                icon: Users,
                route: 'customers.index',
                prefix: '/customers',
            },
            {
                title: 'Satışlar',
                icon: ReceiptText,
                route: 'sales.index',
                prefix: '/sales',
            },
            {
                title: 'Tahsilatlar',
                icon: Banknote,
                route: 'payments.index',
                prefix: '/payments',
            },
        ],
    },

    {
        title: 'STOK',

        items: [
            {
                title: 'Ürünler',
                icon: Package,
                route: 'products.index',
                prefix: '/products',
            },
            {
                title: 'Depolar',
                icon: Boxes,
                route: 'warehouses.index',
                prefix: '/warehouses',
            },
        ],
    },

    {
        title: 'FİNANS',

        items: [
            {
                title: 'Kasa',
                icon: Wallet,
                route: '#',
                prefix: '/cash',
                disabled: true,
            },
        ],
    },

    {
        title: 'RAPORLAR',

        items: [
            {
                title: 'Raporlar',
                icon: BarChart3,
                route: '#',
                prefix: '/reports',
                disabled: true,
            },
        ],
    },

    {
        title: 'SİSTEM',

        items: [
            {
                title: 'Ayarlar',
                icon: Settings,
                route: '#',
                prefix: '/settings',
                disabled: true,
            },
        ],
    },

];

const isActive = (prefix) => page.url.startsWith(prefix);
</script>

<template>

<aside class="w-72 bg-slate-900 text-white h-screen flex flex-col shadow-2xl">

    <div class="px-8 py-8 border-b border-slate-800">

        <h1 class="text-3xl font-black tracking-[0.30em]">
            NEXORA
        </h1>

        <p class="text-slate-400 text-sm mt-2">
            Modern Business ERP
        </p>

    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6">

        <div
            v-for="group in groups"
            :key="group.title"
            class="mb-8"
        >

            <p
                class="px-4 mb-3 text-xs font-bold tracking-widest uppercase text-slate-500"
            >
                {{ group.title }}
            </p>

            <div class="space-y-1">

                <template
                    v-for="item in group.items"
                    :key="item.title"
                >

                    <Link
                        v-if="!item.disabled"
                        :href="route(item.route)"
                        :class="[
                            'flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200',
                            isActive(item.prefix)
                                ? 'bg-blue-600 text-white shadow-lg'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                        ]"
                    >

                        <component
                            :is="item.icon"
                            class="w-5 h-5"
                        />

                        <span class="font-medium">
                            {{ item.title }}
                        </span>

                    </Link>

                    <div
                        v-else
                        class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 cursor-not-allowed"
                    >

                        <div class="flex items-center gap-4">

                            <component
                                :is="item.icon"
                                class="w-5 h-5"
                            />

                            <span>
                                {{ item.title }}
                            </span>

                        </div>

                        <span
                            class="text-[10px] bg-slate-700 px-2 py-1 rounded-full"
                        >
                            Yakında
                        </span>

                    </div>

                </template>

            </div>

        </div>

    </nav>

</aside>

</template>