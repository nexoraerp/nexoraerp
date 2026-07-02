<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import CustomerForm from '@/Components/Customers/CustomerForm.vue';

const props = defineProps({
    customer: Object,
});

const form = useForm({
    code: props.customer.code,
    name: props.customer.name,
    company: props.customer.company,
    phone: props.customer.phone,
    email: props.customer.email,
    tax_number: props.customer.tax_number,
    tax_office: props.customer.tax_office,
    address: props.customer.address,
});

const submit = () => {
    form.put(route('customers.update', props.customer.id));
};
</script>

<template>

    <Head title="Cari Düzenle" />

    <NxLayout>

        <NxPageHeader
            title="Cari Düzenle"
            subtitle="Cari bilgilerini güncelleyin."
        />

        <CustomerForm
            :form="form"
            button-text="Güncelle"
            @submit="submit"
        />

    </NxLayout>

</template>