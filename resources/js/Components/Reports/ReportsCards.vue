<script setup>
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    BadgePercent,
    Banknote,
    Boxes,
    Layers3,
    PackageCheck,
    ReceiptText,
    TrendingUp,
    Users,
    Wallet,
    Warehouse,
} from 'lucide-vue-next';

const icons = {
    Activity,
    BadgePercent,
    Banknote,
    Boxes,
    Layers3,
    PackageCheck,
    ReceiptText,
    TrendingUp,
    Users,
    Wallet,
    Warehouse,
};

defineProps({
    reports: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-950">Rapor Kartları</h2>
                <p class="text-sm text-slate-500">Operasyonel raporlara hızlı erişim</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div
                v-for="report in reports"
                :key="report.title"
                class="flex min-h-[190px] flex-col justify-between rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:shadow-md"
            >
                <div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                        <component
                            :is="icons[report.icon]"
                            class="h-5 w-5"
                        />
                    </div>

                    <h3 class="mt-4 font-bold text-slate-950">{{ report.title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ report.description }}</p>
                    <p class="mt-3 text-sm font-black text-blue-700">{{ report.metric }}</p>
                </div>

                <Link
                    v-if="report.route"
                    :href="route(report.route)"
                    class="mt-4 inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-700"
                >
                    Raporu Aç
                </Link>

                <button
                    v-else
                    type="button"
                    class="mt-4 inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700"
                    disabled
                >
                    Bu sayfada
                </button>
            </div>
        </div>

        <div
            v-if="reports.length === 0"
            class="rounded-2xl border border-slate-100 bg-slate-50 p-6 text-sm text-slate-500"
        >
            Rapor kartı oluşturmak için önce satış, cari, ürün veya tahsilat kaydı ekleyin.
        </div>
    </div>
</template>
