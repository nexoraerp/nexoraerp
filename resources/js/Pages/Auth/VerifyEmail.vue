<script setup>
import { Head } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';

import {
    Wallet,
    Users,
    Package,
    Landmark,
} from 'lucide-vue-next';
</script>

<template>
    <Head title="Kontrol Paneli" />

    <NxLayout>
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-800">
                Dashboard
            </h1>

            <p class="text-slate-500 mt-2">
                Nexora ERP Yönetim Paneline Hoş Geldiniz.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <StatCard
                title="Bugünkü Satış"
                value="₺0,00"
                :icon="Wallet"
            />

            <StatCard
                title="Aktif Cari"
                value="0"
                :icon="Users"
            />

            <StatCard
                title="Kritik Stok"
                value="0"
                :icon="Package"
            />

            <StatCard
                title="Kasa"
                value="₺0,00"
                :icon="Landmark"
            />
        </div>
    </NxLayout>
</template> 
