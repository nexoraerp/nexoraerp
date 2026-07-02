<script setup>
import { Head } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';

import DashboardHero from '@/Components/Dashboard/DashboardHero.vue';
import DashboardKpi from '@/Components/Dashboard/DashboardKpi.vue';
import DashboardStats from '@/Components/Dashboard/DashboardStats.vue';
import DashboardQuickActions from '@/Components/Dashboard/DashboardQuickActions.vue';
import DashboardCriticalStock from '@/Components/Dashboard/DashboardCriticalStock.vue';
import DashboardLastMovements from '@/Components/Dashboard/DashboardLastMovements.vue';
import DashboardChart from '@/Components/Dashboard/DashboardChart.vue';

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

})        ;
</script>

<template>

<Head title="Dashboard" />

<NxLayout>

<div class="space-y-8">

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
/>

<!-- Hızlı İşlemler -->

<DashboardQuickActions />

<!-- Alt Widgetlar -->

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

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
/>

</div>

</NxLayout>

</template>
