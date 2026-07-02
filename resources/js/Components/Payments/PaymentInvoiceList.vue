<script setup>
import PaymentInvoiceCard from './PaymentInvoiceCard.vue';

const props = defineProps({
    sales: {
        type: Array,
        default: () => [],
    },

    selectedSales: {
        type: Array,
        default: () => [],
    },

    items: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits([
    'toggle',
    'updateAmount',
]);

const isSelected = (sale) => {
    return props.selectedSales.some(item => item.id === sale.id);
};

const getAmount = (sale) => {
    const item = props.items.find(i => i.sale_id === sale.id);

    return item
        ? Number(item.amount)
        : Number(sale.remaining_total);
};

const updateAmount = (sale, amount) => {
    emit('updateAmount', {
        sale_id: sale.id,
        amount: Number(amount),
    });
};
</script>

<template>

    <div class="space-y-4">

        <PaymentInvoiceCard
            v-for="sale in sales"
            :key="sale.id"
            :sale="sale"
            :selected="isSelected(sale)"
            :amount="getAmount(sale)"
            @toggle="emit('toggle', $event)"
            @update:amount="updateAmount(sale, $event)"
        />

        <div
            v-if="sales.length === 0"
            class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-12 text-center"
        >
            <div class="text-5xl mb-4">
                📄
            </div>

            <h3 class="text-lg font-semibold text-slate-700">
                Açık Fatura Bulunamadı
            </h3>

            <p class="mt-2 text-slate-500">
                Cari seçildiğinde tahsil edilebilir faturalar burada listelenecek.
            </p>

        </div>

    </div>

</template>