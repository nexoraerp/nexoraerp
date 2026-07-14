<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';

import NxLayout from '@/Layouts/NxLayout.vue';

import PaymentSidebar from '@/Components/Payments/PaymentSidebar.vue';
import PaymentInvoiceList from '@/Components/Payments/PaymentInvoiceList.vue';
import PaymentFooter from '@/Components/Payments/PaymentFooter.vue';

const props = defineProps({

    customers: {
        type: Array,
        default: () => [],
    },

    cashAccounts: {
        type: Array,
        default: () => [],
    },

});                         ;

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = useForm({

    customer_id: '',

    cash_account_id: '',

    payment_method: 'Cash',

    reference_no: '',

    notes: '',

    items: [],

});
/*
|--------------------------------------------------------------------------
| Cari Özeti
|--------------------------------------------------------------------------
*/

const customerSummary = ref({

open_balance: 0,

overdue_balance: 0,

open_invoice_count: 0,

}) ;

/*
|--------------------------------------------------------------------------
| Açık Faturalar
|--------------------------------------------------------------------------
*/

const sales = ref([]) ;

/*
|--------------------------------------------------------------------------
| Seçilen Faturalar
|--------------------------------------------------------------------------
*/

const selectedSales = ref([]) ;

/*
|--------------------------------------------------------------------------
| Fatura Seç / Kaldır
|--------------------------------------------------------------------------
*/

const toggleSale = (sale) => {

const index = selectedSales.value.findIndex(
item => item.id === sale.id
)                                            ;

if (index > -1) {

selectedSales.value.splice(index, 1) ;

} else {

selectedSales.value.push(sale) ;

}

form.items = selectedSales.value.map(sale => {

const existing = form.items.find(
item => item.sale_id === sale.id
)                                 ;

return {

sale_id: sale.id,

amount: existing
? existing.amount
: Number(sale.remaining_total),

} ;

}) ;

} ;

/*
|--------------------------------------------------------------------------
| Tahsil Edilecek Tutar Güncelle
|--------------------------------------------------------------------------
*/

const updateAmount = ({ sale_id, amount }) => {

const item = form.items.find(
item => item.sale_id === sale_id
)                                ;

if (!item) return ;

const sale = sales.value.find(
sale => sale.id === sale_id
)                              ;

if (!sale) return ;

item.amount = Math.min(
Math.max(Number(amount), 0),
Number(sale.remaining_total)
)                            ;

} ;

/*
|--------------------------------------------------------------------------
| Cari Değişince Açık Faturaları Getir
|--------------------------------------------------------------------------
*/

watch(
() => form.customer_id,

async (customerId) => {

selectedSales.value = [] ;
form.items = []          ;

if (!customerId) {

sales.value = [] ;

customerSummary.value = {

open_balance: 0,
overdue_balance: 0,
open_invoice_count: 0,

} ;

return ;

}

try {

const { data } = await axios.get(
route('customers.open-sales', customerId)
)                                         ;

customerSummary.value = data.customer ;

sales.value = data.sales ;

} catch (error) {

console.error(error) ;

}

}
) ;

/*
|--------------------------------------------------------------------------
| Kaydet
|--------------------------------------------------------------------------
*/

const save = () => {

form.post(route('payments.store'));

} ;

</script>

<template>

<Head title="Tahsilat Oluştur" />

<NxLayout>

<div class="mx-auto max-w-7xl">

<div class="mb-8">

<h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">

Tahsilat Oluştur

</h1>

<p class="text-slate-500 mt-2 dark:text-slate-400">

Açık faturaları seçerek tahsilat oluşturabilirsiniz.

</p>

</div>

<div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:gap-8">

<PaymentSidebar

    :customers="customers"

    :cash-accounts="cashAccounts"

    :form="form"

    :customer-summary="customerSummary"

/>

<div class="min-w-0 flex-1 space-y-6">

<PaymentInvoiceList

:sales="sales"

:selected-sales="selectedSales"

:items="form.items"

@toggle="toggleSale"

@updateAmount="updateAmount"

/>

<PaymentFooter

:selected-sales="selectedSales"

:items="form.items"

@save="save"

/>

</div>

</div>

</div>

</NxLayout>

</template>
