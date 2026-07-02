<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import CustomerForm from '@/Components/Customers/CustomerForm.vue';

const form = useForm({
    code: '',
    name: '',
    company: '',
    phone: '',
    email: '',
    tax_number: '',
    tax_office: '',
    address: '',
});

const submit = () => {
    form.post(route('customers.store'));
};
</script>

<template>

    <Head title="Yeni Cari" />

    <NxLayout>

        <NxPageHeader
            title="Yeni Cari"
            subtitle="Yeni müşteri veya tedarikçi ekleyin."
        />

        <CustomerForm
            :form="form"
            button-text="Kaydet"
            @submit="submit"
        />

    </NxLayout>

</template>