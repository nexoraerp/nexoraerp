<script setup>
import { Head } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

import SaleForm from '@/Components/Sales/SaleForm.vue';

const props = defineProps({

    sale: Object,

    customers: {
        type: Array,
        default: () => [],
    },

    products: {
        type: Array,
        default: () => [],
    },

    warehouses: {
        type: Array,
        default: () => [],
    },

});
</script>

<template>

<Head :title="`Satış Düzenle - ${sale.sale_no}`" />

<NxLayout>

    <NxPageHeader
        title="Satışı Düzenle"
        subtitle="Satış bilgilerini güncelleyin."
    />

    <SaleForm
        :sale="sale"
        :customers="customers"
        :products="products"
        :warehouses="warehouses"
    />

</NxLayout>

</template>