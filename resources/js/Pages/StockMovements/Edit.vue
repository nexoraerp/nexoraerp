<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxButton from '@/Components/UI/NxButton.vue';

const props = defineProps({
    movement: Object,
    products: Array,
    warehouses: Array,
    types: Array,
});

const form = useForm({

    product_id: props.movement.product_id,

    warehouse_id: props.movement.warehouse_id,

    type: props.movement.type,

    quantity: props.movement.quantity,

    unit_cost: props.movement.unit_cost,

    description: props.movement.description,

});

const updateMovement = () => {

    form.put(route('stock-movements.update', props.movement.id));

};
</script>

<template>

<Head title="Stok Hareketi Düzenle" />

<NxLayout>

<form @submit.prevent="updateMovement">

<NxCard>

<div class="flex items-center justify-between mb-8">

<div>

<h1 class="text-3xl font-bold">

✏️ Stok Hareketini Düzenle

</h1>

<p class="text-slate-500 mt-2">

Stok hareketi bilgilerini güncelleyin.

</p>

</div>

<Link :href="route('stock-movements.index')">

<NxButton type="button">

← Geri

</NxButton>

</Link>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<NxSelect
label="Ürün"
v-model="form.product_id"
:options="products"
/>

<NxSelect
label="Depo"
v-model="form.warehouse_id"
:options="warehouses"
/>

<NxSelect
label="Hareket Tipi"
v-model="form.type"
:options="types.map(type => ({ value: type, label: type }))"
/>

<NxInput
label="Miktar"
type="number"
v-model="form.quantity"
/>

<NxInput
label="Birim Maliyet"
type="number"
v-model="form.unit_cost"
/>

<NxInput
label="Açıklama"
v-model="form.description"
/>

</div>

<div class="flex justify-end mt-8">

<NxButton
type="submit"
:disabled="form.processing"
>

{{ form.processing ? 'Kaydediliyor...' : 'Kaydet' }}

</NxButton>

</div>

</NxCard>

</form>

</NxLayout>

</template>