<script setup>
import { Head } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import QuoteForm from '@/Components/Quotes/QuoteForm.vue';

defineProps({
    quote: Object,
    customers: Array,
    products: Array,
    warehouses: Array,
});
</script>

<template>
    <Head :title="`Teklif Düzenle - ${quote.quote_no}`" />

    <NxLayout>
        <NxPageHeader
            title="Teklifi Düzenle"
            subtitle="Teklif kalemleri, geçerlilik ve şartları güncelleyin."
        />

        <QuoteForm
            :quote="quote"
            :customers="customers"
            :products="products"
            :warehouses="warehouses"
        />
    </NxLayout>
</template>
