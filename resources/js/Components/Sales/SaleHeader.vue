<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

defineProps({
    form: Object,
    customers: {
        type: Array,
        default: () => [],
    },
    warehouses: {
        type: Array,
        default: () => [],
    },
});

const paymentTypes = [
    { value: 'Cash', label: '💵 Nakit' },
    { value: 'Credit', label: '📅 Vadeli' },
    { value: 'Card', label: '💳 Kart' },
    { value: 'Bank', label: '🏦 Havale / EFT' },
    { value: 'Mixed', label: '🔄 Karma' },
];
</script>

<template>

<NxCard class="mb-6">

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                🧾 Yeni Satış
            </h1>

            <p class="text-slate-500 mt-2">
                Yeni satış oluşturun ve ürünlerinizi ekleyin.
            </p>

        </div>

        <div
            class="rounded-2xl bg-blue-50 border border-blue-100 px-6 py-5 text-center"
        >

            <p class="text-xs uppercase tracking-widest text-slate-500">
                Satış No
            </p>

            <p class="text-xl font-bold text-blue-600">
                Otomatik
            </p>

        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        <NxSelect
            label="Cari"
            v-model="form.customer_id"
            :options="customers"
        />

        <NxSelect
            label="Depo"
            v-model="form.warehouse_id"
            :options="warehouses"
        />

        <NxInput
            label="Satış Tarihi"
            type="date"
            v-model="form.sale_date"
        />

        <NxSelect
            label="Ödeme Tipi"
            v-model="form.payment_type"
            :options="paymentTypes"
        />

        <NxInput
            label="Vade Tarihi"
            type="date"
            v-model="form.due_date"
            :disabled="form.payment_type === 'Cash'"
        />

    </div>

</NxCard>

</template>