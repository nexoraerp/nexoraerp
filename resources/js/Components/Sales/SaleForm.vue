<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxButton from '@/Components/UI/NxButton.vue';

import SaleItemsTable from '@/Components/Sales/SaleItemsTable.vue';

const props = defineProps({

    sale: {
        type: Object,
        default: null,
    },

    customers: {
        type: Array,
        default: () => [],
    },

    products: {
        type: Array,
        default: () => [],
    },

    warehouses: {
        type: Array,
        default: () => [],
    },

});

const isEdit = computed(() => props.sale !== null);

const form = useForm({

    customer_id: props.sale?.customer_id ?? '',

    warehouse_id:
        props.sale?.items?.length
            ? props.sale.items[0].warehouse_id
            : '',

    sale_date:
        props.sale?.sale_date ??
        new Date().toISOString().substring(0, 10),

    notes: props.sale?.notes ?? '',

    items: props.sale
        ? JSON.parse(JSON.stringify(props.sale.items))
        : [],

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

const subtotal = computed(() => {

    return form.items.reduce((total, item) => {

        const line =

            (Number(item.quantity || 0) *
            Number(item.unit_price || 0))

            - Number(item.discount || 0);

        return total + line;

    }, 0);

});

const vatTotal = computed(() => {

    return form.items.reduce((total, item) => {

        const line =

            (Number(item.quantity || 0) *
            Number(item.unit_price || 0))

            - Number(item.discount || 0);

        return total + (line * Number(item.vat || 0) / 100);

    }, 0);

});

const grandTotal = computed(() => {

    return subtotal.value + vatTotal.value;

});

const submit = () => {

    if (isEdit.value) {

        form.put(route('sales.update', props.sale.id));

    } else {

        form.post(route('sales.store'));

    }

};
</script>

<template>

<form
    @submit.prevent="submit"
    class="space-y-8"
>

    <NxCard>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <NxSelect
                label="Cari"
                v-model="form.customer_id"
                :options="customers"
                option-label="label"
                option-value="value"
                :error="form.errors.customer_id"
            />

            <NxSelect
                label="Depo"
                v-model="form.warehouse_id"
                :options="warehouses"
                option-label="label"
                option-value="value"
                :error="form.errors.warehouse_id"
            />

            <NxInput
                label="Satış Tarihi"
                type="date"
                v-model="form.sale_date"
                :error="form.errors.sale_date"
            />

        </div>

        <div class="mt-6">

            <NxInput
                label="Not"
                v-model="form.notes"
                :error="form.errors.notes"
            />

        </div>

        <div class="mt-10">

            <SaleItemsTable
                :products="products"
                :warehouses="warehouses"
                :items="form.items"
                @add-item="addItem"
                @remove-item="removeItem"
            />

        </div> 
                <NxCard class="mt-8 border-0 bg-slate-50">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="text-center">

                    <p class="text-sm text-slate-500">

                        Ara Toplam

                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-slate-800">

                        ₺{{ subtotal.toLocaleString('tr-TR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }) }}

                    </h3>

                </div>

                <div class="text-center">

                    <p class="text-sm text-slate-500">

                        KDV Toplamı

                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-emerald-600">

                        ₺{{ vatTotal.toLocaleString('tr-TR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }) }}

                    </h3>

                </div>

                <div class="text-center">

                    <p class="text-sm text-slate-500">

                        Genel Toplam

                    </p>

                    <h3 class="mt-2 text-4xl font-extrabold text-blue-600">

                        ₺{{ grandTotal.toLocaleString('tr-TR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }) }}

                    </h3>

                </div>

            </div>

        </NxCard>

        <div class="mt-8 flex items-center justify-between">

            <div class="text-sm text-slate-500">

                {{ form.items.length }} ürün satırı

            </div>

            <div class="flex gap-3">
                            <NxButton
                    type="submit"
                    :disabled="form.processing"
                    class="min-w-[220px]"
                >

                    <span v-if="form.processing">

                        Kaydediliyor...

                    </span>

                    <span v-else>

                        {{ isEdit ? '💾 Değişiklikleri Kaydet' : '🛒 Satışı Kaydet' }}

                    </span>

                </NxButton>

            </div>

        </div>

    </NxCard>

</form>

</template>