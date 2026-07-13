<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxTextarea from '@/Components/UI/NxTextarea.vue';
import SaleItemsTable from '@/Components/Sales/SaleItemsTable.vue';

const props = defineProps({
    quote: {
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

const isEdit = computed(() => props.quote !== null);
const today = new Date().toISOString().substring(0, 10);
const nextMonth = new Date();
nextMonth.setDate(nextMonth.getDate() + 30);

const form = useForm({
    customer_id: props.quote?.customer_id ?? '',
    warehouse_id: props.quote?.items?.length ? props.quote.items[0].warehouse_id : '',
    quote_date: props.quote?.quote_date ?? today,
    valid_until: props.quote?.valid_until ?? nextMonth.toISOString().substring(0, 10),
    probability: props.quote?.probability ?? 50,
    notes: props.quote?.notes ?? '',
    terms: props.quote?.terms ?? 'Fiyatlar teklif geçerlilik tarihi sonuna kadar geçerlidir.',
    items: props.quote ? JSON.parse(JSON.stringify(props.quote.items)) : [],
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

const subtotal = computed(() => form.items.reduce((total, item) => {
    return total + ((Number(item.quantity || 0) * Number(item.unit_price || 0)) - Number(item.discount || 0));
}, 0));

const vatTotal = computed(() => form.items.reduce((total, item) => {
    const line = (Number(item.quantity || 0) * Number(item.unit_price || 0)) - Number(item.discount || 0);

    return total + (line * Number(item.vat || 0) / 100);
}, 0));

const grandTotal = computed(() => subtotal.value + vatTotal.value);

const grossProfit = computed(() => form.items.reduce((total, item) => {
    const product = props.products.find(product => product.value == item.product_id);
    const purchasePrice = Number(product?.purchase_price || 0);
    const quantity = Number(item.quantity || 0);
    const discount = Number(item.discount || 0);
    const unitPrice = Number(item.unit_price || 0);

    return total + ((unitPrice - purchasePrice) * quantity - discount);
}, 0));

const profitMargin = computed(() => {
    if (subtotal.value <= 0) {
        return 0;
    }

    return (grossProfit.value / subtotal.value) * 100;
});

const formatMoney = (value) => Number(value).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const submit = () => {
    form.items = form.items.map(item => ({
        ...item,
        warehouse_id: item.warehouse_id || form.warehouse_id,
    }));

    if (isEdit.value) {
        form.put(route('quotes.update', props.quote.id));

        return;
    }

    form.post(route('quotes.store'));
};
</script>

<template>
    <form
        class="space-y-6"
        @submit.prevent="submit"
    >
        <NxCard>
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-5">
                <NxSelect
                    label="Cari"
                    v-model="form.customer_id"
                    :options="customers"
                    option-label="label"
                    option-value="value"
                    :error="form.errors.customer_id"
                />

                <NxSelect
                    label="Varsayılan Depo"
                    v-model="form.warehouse_id"
                    :options="warehouses"
                    option-label="label"
                    option-value="value"
                    :error="form.errors.warehouse_id"
                />

                <NxInput
                    label="Teklif Tarihi"
                    type="date"
                    v-model="form.quote_date"
                    :error="form.errors.quote_date"
                />

                <NxInput
                    label="Geçerlilik"
                    type="date"
                    v-model="form.valid_until"
                    :error="form.errors.valid_until"
                />

                <NxInput
                    label="Kazanma Olasılığı"
                    type="number"
                    min="0"
                    max="100"
                    v-model="form.probability"
                    :error="form.errors.probability"
                />
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <NxTextarea
                    label="Teklif Notu"
                    v-model="form.notes"
                    :error="form.errors.notes"
                />

                <NxTextarea
                    label="Şartlar"
                    v-model="form.terms"
                    :error="form.errors.terms"
                />
            </div>
        </NxCard>

        <SaleItemsTable
            :products="products"
            :warehouses="warehouses"
            :items="form.items"
            :errors="form.errors"
            @add-item="addItem"
            @remove-item="removeItem"
        />

        <NxCard>
            <div class="grid gap-6 text-center md:grid-cols-6">
                <div>
                    <p class="text-sm text-slate-500">Kalem</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">{{ form.items.length }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Ara Toplam</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">₺{{ formatMoney(subtotal) }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">KDV</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">₺{{ formatMoney(vatTotal) }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Genel Toplam</p>
                    <p class="mt-2 text-3xl font-black text-blue-700">₺{{ formatMoney(grandTotal) }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tahmini Kar</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">₺{{ formatMoney(grossProfit) }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Kar Oranı</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">%{{ Number(profitMargin).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</p>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end">
                <NxButton
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    {{ isEdit ? 'Teklifi Güncelle' : 'Teklifi Oluştur' }}
                </NxButton>
            </div>
        </NxCard>
    </form>
</template>
