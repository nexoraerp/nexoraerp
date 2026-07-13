<script setup>

defineProps({

    todayRevenue: {
        type: Number,
        default: 0,
    },

    todaySales: {
        type: Number,
        default: 0,
    },

    todayCollection: {
        type: Number,
        default: 0,
    },

    totalCash: {
        type: Number,
        default: 0,
    },

    totalReceivable: {
        type: Number,
        default: 0,
    },

    openInvoiceCount: {
        type: Number,
        default: 0,
    },

    monthVatTotal: {
        type: Number,
        default: 0,
    },

    monthGrossProfit: {
        type: Number,
        default: 0,
    },

    monthProfitMargin: {
        type: Number,
        default: 0,
    },

    hideSensitive: {
        type: Boolean,
        default: false,
    },

});

const money = (value) => `₺${Number(value).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
})}`;

const hiddenValue = '••••••';

</script>

<template>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    <!-- Bugünkü Satış -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            💰 Bugünkü Satış
        </p>

        <h2 class="text-3xl font-bold mt-4 text-emerald-600">

            {{ hideSensitive ? hiddenValue : money(todayRevenue) }}

        </h2>

    </div>

    <!-- Bugünkü Tahsilat -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            💵 Bugünkü Tahsilat
        </p>

        <h2 class="text-3xl font-bold mt-4 text-blue-600">

            {{ hideSensitive ? hiddenValue : money(todayCollection) }}

        </h2>

    </div>

    <!-- Kasalardaki Para -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            🏦 Kasalar
        </p>

        <h2 class="text-3xl font-bold mt-4 text-purple-600">

            {{ hideSensitive ? hiddenValue : money(totalCash) }}

        </h2>

    </div>

    <!-- Cari Alacak -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            📒 Cari Alacak
        </p>

        <h2 class="text-3xl font-bold mt-4 text-red-600">

            {{ hideSensitive ? hiddenValue : money(totalReceivable) }}

        </h2>

    </div>

    <!-- Açık Fatura -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            📄 Açık Fatura
        </p>

        <h2 class="text-3xl font-bold mt-4 text-orange-600">

            {{ openInvoiceCount }}

        </h2>

    </div>

    <!-- Bugünkü Satış Adedi -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            🛒 Bugünkü Satış Adedi
        </p>

        <h2 class="text-3xl font-bold mt-4 text-indigo-600">

            {{ todaySales }}

        </h2>

    </div>

    <!-- Bu Ay Oluşan KDV -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            🧮 Bu Ay Oluşan KDV
        </p>

        <h2 class="text-3xl font-bold mt-4 text-cyan-600">

            {{ hideSensitive ? hiddenValue : money(monthVatTotal) }}

        </h2>

    </div>

    <!-- Bu Ay Brüt Kar -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            📈 Bu Ay Brüt Kar
        </p>

        <h2 class="text-3xl font-bold mt-4 text-emerald-700">

            {{ hideSensitive ? hiddenValue : money(monthGrossProfit) }}

        </h2>

    </div>

    <!-- Kar Oranı -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border">

        <p class="text-slate-500 text-sm">
            🎯 Bu Ay Kar Oranı
        </p>

        <h2 class="text-3xl font-bold mt-4 text-slate-900">

            {{ hideSensitive ? hiddenValue : `%${Number(monthProfitMargin).toLocaleString('tr-TR',{ minimumFractionDigits:2 })}` }}

        </h2>

    </div>

</div>

</template>
