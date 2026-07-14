<script setup>

import NxDashboardCard from '@/Components/UI/NxDashboardCard.vue';

defineProps({

    customerCount: Number,

    productCount: Number,

    warehouseCount: Number,

    movementCount: Number,

    saleCount: Number,

    completedSales: Number,

    cancelledSales: Number,

    totalRevenue: Number,

    hideSensitive: {
        type: Boolean,
        default: false,
    },

});

</script>

<template>

<div class="grid grid-cols-2 gap-3 md:grid-cols-2 xl:grid-cols-4 xl:gap-6">

    <NxDashboardCard
        title="Cari Yönetimi"
        :count="customerCount"
        subtitle="Toplam Cari"
        icon="👥"
        color="blue"
        :href="route('customers.index')"
    />

    <NxDashboardCard
        title="Ürün Yönetimi"
        :count="productCount"
        subtitle="Toplam Ürün"
        icon="📦"
        color="emerald"
        :href="route('products.index')"
    />

    <NxDashboardCard
        title="Satışlar"
        :count="saleCount"
        subtitle="Toplam Satış"
        icon="🧾"
        color="cyan"
        :href="route('sales.index')"
    />

    <NxDashboardCard
        title="Toplam Ciro"
        :count="hideSensitive ? '••••••' : Number(totalRevenue).toLocaleString('tr-TR') + ' ₺'"
        subtitle="Tamamlanan Satışlar"
        icon="💰"
        color="green"
    />

    <NxDashboardCard
        title="İptal Satış"
        :count="cancelledSales"
        subtitle="İptal Edilen"
        icon="❌"
        color="red"
        :href="route('sales.index')"
    />

    <NxDashboardCard
        title="Tamamlanan"
        :count="completedSales"
        subtitle="Başarılı Satış"
        icon="✅"
        color="lime"
        :href="route('sales.index')"
    />

    <NxDashboardCard
        title="Depolar"
        :count="warehouseCount"
        subtitle="Toplam Depo"
        icon="🏢"
        color="purple"
        :href="route('warehouses.index')"
    />

    <NxDashboardCard
        title="Stok Hareketleri"
        :count="movementCount"
        subtitle="Toplam Hareket"
        icon="📊"
        color="orange"
        :href="route('stock-movements.index')"
    />

</div>

</template>
