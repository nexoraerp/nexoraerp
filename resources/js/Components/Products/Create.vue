<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

import ProductForm from '@/Components/Products/ProductForm.vue';

const form = useForm({
    code: '',
    barcode: '',
    name: '',
    category: '',
    brand: '',
    model: '',
    purchase_price: 0,
    sale_price: 0,
    vat: 20,
    stock: 0,
    min_stock: 0,
    unit: 'Adet',
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>

    <Head title="Yeni Ürün" />

    <NxLayout>

        <NxPageHeader
            title="Yeni Ürün"
            subtitle="Yeni ürün ekleyin."
        />

        <ProductForm
            :form="form"
            button-text="Kaydet"
            @submit="submit"
        />

    </NxLayout>

</template>