<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import WarehouseForm from '@/Components/Warehouses/WarehouseForm.vue';

const props = defineProps({
    warehouse: Object,
});

const form = useForm({
    code: props.warehouse.code,
    name: props.warehouse.name,
    manager: props.warehouse.manager,
    phone: props.warehouse.phone,
    address: props.warehouse.address,
    is_active: props.warehouse.is_active,
});

const submit = () => {
    form.put(route('warehouses.update', props.warehouse.id));
};
</script>

<template>

    <Head title="Depo Düzenle" />

    <NxLayout>

        <NxPageHeader
            title="Depo Düzenle"
            subtitle="Depo bilgilerini güncelleyin."
        />

        <WarehouseForm
            :form="form"
            button-text="Güncelle"
            @submit="submit"
        />

    </NxLayout>

</template>