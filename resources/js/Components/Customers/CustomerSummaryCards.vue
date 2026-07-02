<script setup>
defineProps({
    summary: {
        type: Object,
        required: true,
    },
});
</script>

<template>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Güncel Bakiye
        </p>

        <h2 class="text-3xl font-bold text-red-600 mt-3">
            ₺ {{ Number(summary.balance).toLocaleString('tr-TR',{
                minimumFractionDigits:2
            }) }}
        </h2>

    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Toplam Satış
        </p>

        <h2 class="text-3xl font-bold text-blue-600 mt-3">
            ₺ {{ Number(summary.total_sales).toLocaleString('tr-TR',{
                minimumFractionDigits:2
            }) }}
        </h2>

    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Toplam Tahsilat
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-3">
            ₺ {{ Number(summary.total_paid).toLocaleString('tr-TR',{
                minimumFractionDigits:2
            }) }}
        </h2>

    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Açık Fatura
        </p>

        <h2 class="text-3xl font-bold text-amber-600 mt-3">
            {{ summary.open_invoice_count }}
        </h2>

    </div>

</div>

</template>