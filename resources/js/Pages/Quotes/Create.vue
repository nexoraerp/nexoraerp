<script setup>
import { Head } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import QuoteForm from '@/Components/Quotes/QuoteForm.vue';

defineProps({
    customers: Array,
    products: Array,
    warehouses: Array,
});
</script>

<template>
    <Head title="Yeni Teklif" />

    <NxLayout>
        <NxPageHeader
            title="Yeni Teklif"
            subtitle="Cari, ürün, fiyat ve şartları tek akışta hazırlayın."
        />

        <QuoteForm
            :customers="customers"
            :products="products"
            :warehouses="warehouses"
        />
    </NxLayout>
</template>
