<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import StockMovementForm from '@/Components/StockMovements/StockMovementForm.vue';

const props = defineProps({
    products: Array,
    warehouses: Array,
    types: Array,
});

const form = useForm({
    product_id: '',
    warehouse_id: '',
    type: 'IN',
    quantity: '',
    unit_cost: '',
    description: '',
});

const submit = () => {
    form.post(route('stock-movements.store'));
};
</script>

<template>

<Head title="Yeni Stok Hareketi" />

<NxLayout>



    <StockMovementForm
        :form="form"
        :products="products"
        :warehouses="warehouses"
        :types="types"
        button-text="Kaydet"
        @submit="submit"
    />

</NxLayout>

</template>