<script setup>
import { onMounted, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';

import DashboardHero from '@/Components/Dashboard/DashboardHero.vue';
import DashboardKpi from '@/Components/Dashboard/DashboardKpi.vue';
import DashboardStats from '@/Components/Dashboard/DashboardStats.vue';
import DashboardQuickActions from '@/Components/Dashboard/DashboardQuickActions.vue';
import DashboardCriticalStock from '@/Components/Dashboard/DashboardCriticalStock.vue';
import DashboardLastMovements from '@/Components/Dashboard/DashboardLastMovements.vue';
import DashboardChart from '@/Components/Dashboard/DashboardChart.vue';

const hideSensitive = ref(false);

onMounted(() => {
    hideSensitive.value = localStorage.getItem('nexora-dashboard-sensitive-hidden') === 'true';
});

watch(hideSensitive, (value) => {
    localStorage.setItem('nexora-dashboard-sensitive-hidden', value ? 'true' : 'false');
});

defineProps({

    /*
    |--------------------------------------------------------------------------
    | Genel İstatistikler
    |--------------------------------------------------------------------------
    */

    salesChart: {
        type: Array,
        default: () => [],
    },

    customerCount: Number,
    productCount: Number,
    warehouseCount: Number,
    movementCount: Number,

    /*
    |--------------------------------------------------------------------------
    | Satış İstatistikleri
    |--------------------------------------------------------------------------
    */

    saleCount: Number,
    completedSales: Number,
    cancelledSales: Number,
    todaySales: Number,
    totalRevenue: Number,
    todayRevenue: Number,
    monthRevenue: Number,
    averageSale: Number,
    monthVatTotal: Number,
    monthGrossProfit: Number,
    monthProfitMargin: Number,

    /*
    |--------------------------------------------------------------------------
    | Finans
    |--------------------------------------------------------------------------
    */

    todayCollection: Number,
    totalCash: Number,
    totalReceivable: Number,
    openInvoiceCount: Number,

    lastPayments: {
        type: Array,
        default: () => [],
    },

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgetları
    |--------------------------------------------------------------------------
    */

    topProducts: {
        type: Array,
        default: () => [],
    },

    lastSales: {
        type: Array,
        default: () => [],
    },

    criticalProducts: {
        type: Array,
        default: () => [],
    },

    lastMovements: {
        type: Array,
        default: () => [],
    },

});
</script>

<template>

    <Head title="Dashboard" />

    <NxLayout>

        <div class="space-y-5 sm:space-y-8">

            <div class="flex justify-end">
                <button
                    type="button"
                    class="inline-flex min-h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs font-black text-slate-700 shadow-sm transition hover:border-blue-200 hover:text-blue-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 sm:px-5 sm:text-sm"
                    @click="hideSensitive = !hideSensitive"
                >
                    <EyeOff
                        v-if="hideSensitive"
                        class="h-4 w-4"
                    />
                    <Eye
                        v-else
                        class="h-4 w-4"
                    />
                    {{ hideSensitive ? 'Hassas Bilgileri Göster' : 'Müşteri Modu' }}
                </button>
            </div>

            <!-- Hero -->
            <DashboardHero />

            <!-- KPI -->
            <DashboardKpi
                :today-revenue="todayRevenue"
                :today-sales="todaySales"
                :today-collection="todayCollection"
                :total-cash="totalCash"
                :total-receivable="totalReceivable"
                :open-invoice-count="openInvoiceCount"
                :month-vat-total="monthVatTotal"
                :month-gross-profit="monthGrossProfit"
                :month-profit-margin="monthProfitMargin"
                :hide-sensitive="hideSensitive"
            />

            <!-- Genel İstatistikler -->
            <DashboardStats
                :customer-count="customerCount"
                :product-count="productCount"
                :warehouse-count="warehouseCount"
                :movement-count="movementCount"
                :sale-count="saleCount"
                :completed-sales="completedSales"
                :cancelled-sales="cancelledSales"
                :today-sales="todaySales"
                :total-revenue="totalRevenue"
                :today-revenue="todayRevenue"
                :month-revenue="monthRevenue"
                :average-sale="averageSale"
                :hide-sensitive="hideSensitive"
            />

            <!-- Hızlı İşlemler -->
            <DashboardQuickActions />

            <!-- Alt Widgetlar -->
            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2 xl:gap-8">

                <DashboardCriticalStock
                    :products="criticalProducts"
                />

                <DashboardLastMovements
                    :movements="lastMovements"
                />

            </div>

            <!-- Grafik -->
            <DashboardChart
                :sales-chart="salesChart"
                :hide-sensitive="hideSensitive"
            />

        </div>

    </NxLayout>

</template>
