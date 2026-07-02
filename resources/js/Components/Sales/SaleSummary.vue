<script setup>

const props = defineProps({

    subtotal: {
        type: Number,
        default: 0,
    },

    vat: {
        type: Number,
        default: 0,
    },

    grandTotal: {
        type: Number,
        default: 0,
    },

    itemCount: {
        type: Number,
        default: 0,
    },

});

</script>

<template>

<div class="sticky top-6">

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

<div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5">

<h2 class="text-xl font-bold text-white">

💰 Satış Özeti

</h2>

<p class="text-blue-100 text-sm mt-1">

Satış toplamları anlık hesaplanır.

</p>

</div>

<div class="p-6 space-y-5">

<div class="flex justify-between">

<span class="text-slate-500">

Ara Toplam

</span>

<strong class="text-slate-800">

₺{{ subtotal.toLocaleString('tr-TR',{
minimumFractionDigits:2,
maximumFractionDigits:2
}) }}

</strong>

</div>

<div class="flex justify-between">

<span class="text-slate-500">

KDV

</span>

<strong class="text-emerald-600">

₺{{ vat.toLocaleString('tr-TR',{
minimumFractionDigits:2,
maximumFractionDigits:2
}) }}

</strong>

</div>

<hr>

<div class="flex justify-between">

<span class="text-slate-500">

Ürün Sayısı

</span>

<strong>

{{ itemCount }}

</strong>

</div>

<div class="rounded-xl bg-slate-50 p-5 text-center">

<p class="text-sm text-slate-500">

Genel Toplam

</p>

<p class="text-4xl font-black text-blue-600 mt-2">

₺{{ grandTotal.toLocaleString('tr-TR',{
minimumFractionDigits:2,
maximumFractionDigits:2
}) }}

</p>

</div>

</div>

</div>

</div>

</template>