<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    expense: {
        type: Object,
        required: true,
    },
});

const money = value => `₺${Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
})}`;

const cancelExpense = () => {
    const reason = prompt('İptal nedeni giriniz');

    if (!reason) {
        return;
    }

    router.patch(route('expenses.cancel', props.expense.id), {
        cancel_reason: reason,
    });
};
</script>

<template>
    <Head :title="expense.expense_no" />
    <NxLayout>
        <NxPageHeader
            title="Gider Detayı"
            :subtitle="expense.expense_no"
        >
            <div class="flex gap-3">
                <Link :href="route('expenses.index')">
                    <NxButton variant="secondary">Liste</NxButton>
                </Link>
                <Link
                    v-if="expense.status !== 'Cancelled'"
                    :href="route('expenses.edit', expense.id)"
                >
                    <NxButton variant="secondary">Düzenle</NxButton>
                </Link>
                <NxButton
                    v-if="expense.status !== 'Cancelled'"
                    variant="danger"
                    @click="cancelExpense"
                >
                    İptal Et
                </NxButton>
            </div>
        </NxPageHeader>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 space-y-6 xl:col-span-8">
                <NxCard>
                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <p class="text-sm text-slate-500">Kategori</p>
                            <h3 class="mt-1 font-black text-slate-950">{{ expense.category?.name }}</h3>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Gider Tarihi</p>
                            <h3 class="mt-1 font-black text-slate-950">{{ expense.expense_date }}</h3>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Vade</p>
                            <h3 class="mt-1 font-black text-slate-950">{{ expense.due_date ?? '-' }}</h3>
                        </div>
                    </div>
                </NxCard>

                <NxCard>
                    <h2 class="text-xl font-black text-slate-950">{{ expense.title }}</h2>
                    <div class="mt-4 grid gap-5 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-slate-500">Tedarikçi</p>
                            <p class="font-semibold">{{ expense.supplier_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Belge No</p>
                            <p class="font-semibold">{{ expense.document_no ?? '-' }}</p>
                        </div>
                    </div>
                    <p class="mt-5 whitespace-pre-line leading-7 text-slate-600">{{ expense.description ?? expense.notes ?? 'Açıklama bulunmuyor.' }}</p>
                </NxCard>
            </div>

            <div class="col-span-12 xl:col-span-4">
                <NxCard>
                    <h2 class="text-xl font-black text-slate-950">Finans Özeti</h2>
                    <div class="mt-5 space-y-4">
                        <div class="flex justify-between">
                            <span>KDV Hariç</span>
                            <strong>{{ money(Number(expense.subtotal) - Number(expense.discount)) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>KDV</span>
                            <strong>{{ money(expense.vat) }}</strong>
                        </div>
                        <div class="flex justify-between text-lg">
                            <span class="font-black">Genel Toplam</span>
                            <strong class="text-blue-700">{{ money(expense.grand_total) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Ödenen</span>
                            <strong>{{ money(expense.paid_total) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Kalan</span>
                            <strong>{{ money(expense.remaining_total) }}</strong>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm font-bold text-slate-700">
                            Durum: {{ expense.status }} / {{ expense.payment_status }}
                        </div>
                    </div>
                </NxCard>
            </div>
        </div>
    </NxLayout>
</template>
