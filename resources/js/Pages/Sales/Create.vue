<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';

import SaleHeader from '@/Components/Sales/SaleHeader.vue';
import SaleItemsTable from '@/Components/Sales/SaleItemsTable.vue';
import SaleSummary from '@/Components/Sales/SaleSummary.vue';
import SaleNotes from '@/Components/Sales/SaleNotes.vue';

const props = defineProps({
    customers: Array,
    products: Array,
    warehouses: Array,
});

const today = new Date().toISOString().substring(0, 10);

const form = useForm({

    customer_id: '',

    warehouse_id: '',

    sale_date: today,

    due_date: today,

    payment_type: 'Credit',

    notes: '',

    items: [],

});

const addItem = () => {

    form.items.push({

        product_id: '',
        warehouse_id: form.warehouse_id,
        quantity: 1,
        unit_price: 0,
        discount: 0,
        vat: 20,

    });

};

const removeItem = (index) => {

    form.items.splice(index, 1);

};

const saveSale = () => {
    if (form.payment_type === 'Cash') {
        form.due_date = form.sale_date;
    }

    form.items = form.items.map(item => ({
        ...item,
        warehouse_id: item.warehouse_id || form.warehouse_id,
    }));

    form.post(route('sales.store'));

};

const subtotal = computed(() => {

    return form.items.reduce((total, item) => {

        return total +

            ((Number(item.quantity || 0) *
            Number(item.unit_price || 0))
            - Number(item.discount || 0));

    }, 0);

});

const vatTotal = computed(() => {

    return form.items.reduce((total, item) => {

        const line =

            (Number(item.quantity || 0) *
            Number(item.unit_price || 0))
            - Number(item.discount || 0);

        return total +

            (line * Number(item.vat || 0) / 100);

    }, 0);

});

const grandTotal = computed(() => {

    return subtotal.value + vatTotal.value;

});
</script>

<template>

<NxLayout>

<form @submit.prevent="saveSale">

    <SaleHeader
        :form="form"
        :customers="customers"
        :warehouses="warehouses"
    />

    <div class="grid grid-cols-12 gap-6">

        <!-- Sol Taraf -->

        <div class="col-span-12 xl:col-span-8 space-y-6">

            <SaleItemsTable
                :products="products"
                :warehouses="warehouses"
                :items="form.items"
                :errors="form.errors"
                @add-item="addItem"
                @remove-item="removeItem"
            />

            <SaleNotes
                :form="form"
            />

        </div>

        <!-- Sağ Taraf -->

        <div class="col-span-12 xl:col-span-4">

            <SaleSummary
                :subtotal="subtotal"
                :vat="vatTotal"
                :grand-total="grandTotal"
                :item-count="form.items.length"
            />

        </div>

    </div>

    <div
        class="mt-8 flex justify-end gap-3"
    >

        <NxButton
            type="button"
            variant="secondary"
        >

            İptal

        </NxButton>

        <NxButton
            type="submit"
            :disabled="form.processing"
        >

            {{ form.processing
                ? 'Kaydediliyor...'
                : '🟢 Satışı Kaydet' }}

        </NxButton>

    </div>

</form>

</NxLayout>

</template>
