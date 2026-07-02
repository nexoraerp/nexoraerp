<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import WarehouseForm from '@/Components/Warehouses/WarehouseForm.vue';

const form = useForm({
    code: '',
    name: '',
    manager: '',
    phone: '',
    address: '',
    is_active: true,
});

const submit = () => {
    form.post(route('warehouses.store'));
};
</script>

<template>

    <Head title="Yeni Depo" />

    <NxLayout>

        <NxPageHeader
            title="Yeni Depo"
            subtitle="Yeni depo ekleyin."
        />

        <WarehouseForm
            :form="form"
            button-text="Kaydet"
            @submit="submit"
        />

    </NxLayout>

</template>