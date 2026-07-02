<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

import ProductForm from '@/Components/Products/ProductForm.vue';

const props = defineProps({
    product: Object,
});

const form = useForm({
    code: props.product.code,
    barcode: props.product.barcode,
    name: props.product.name,
    category: props.product.category,
    brand: props.product.brand,
    model: props.product.model,
    purchase_price: props.product.purchase_price,
    sale_price: props.product.sale_price,
    vat: props.product.vat,
    stock: props.product.stock,
    min_stock: props.product.min_stock,
    unit: props.product.unit,
});

const submit = () => {
    form.put(route('products.update', props.product.id));
};
</script>

<template>

    <Head title="Ürün Düzenle" />

    <NxLayout>

        <NxPageHeader
            title="Ürün Düzenle"
            subtitle="Ürün bilgilerini güncelleyin."
        />

        <ProductForm
            :form="form"
            button-text="Güncelle"
            @submit="submit"
        />

    </NxLayout>

</template>