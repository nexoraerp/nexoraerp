<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const props = defineProps({
    expense: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    cashAccounts: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    expense_category_id: props.expense?.expense_category_id ?? '',
    expense_date: props.expense?.expense_date ?? new Date().toISOString().slice(0, 10),
    due_date: props.expense?.due_date ?? '',
    title: props.expense?.title ?? '',
    description: props.expense?.description ?? '',
    supplier_name: props.expense?.supplier_name ?? '',
    document_no: props.expense?.document_no ?? '',
    subtotal: props.expense?.subtotal ?? 0,
    discount: props.expense?.discount ?? 0,
    vat_rate: props.expense?.vat_rate ?? 20,
    paid_total: props.expense?.paid_total ?? 0,
    payment_method: props.expense?.payment_method ?? 'Cash',
    payment_source_type: props.expense?.payment_source_type ?? 'cash_account',
    payment_source_id: props.expense?.payment_source_id ?? '',
    notes: props.expense?.notes ?? '',
});

const netTotal = computed(() => Math.max(0, Number(form.subtotal || 0) - Number(form.discount || 0)));
const vatTotal = computed(() => netTotal.value * (Number(form.vat_rate || 0) / 100));
const grandTotal = computed(() => netTotal.value + vatTotal.value);
const remainingTotal = computed(() => Math.max(0, grandTotal.value - Number(form.paid_total || 0)));

const paymentMethods = [
    { value: 'Cash', label: 'Nakit' },
    { value: 'Bank', label: 'Banka' },
    { value: 'Card', label: 'Kart' },
    { value: 'Mixed', label: 'Karma' },
];

const money = (value) => Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const submit = () => {
    if (props.expense) {
        form.put(route('expenses.update', props.expense.id));
        return;
    }

    form.post(route('expenses.store'));
};
</script>

<template>
    <form
        class="grid grid-cols-12 gap-6"
        @submit.prevent="submit"
    >
        <div class="col-span-12 space-y-6 xl:col-span-8">
            <NxCard>
                <div class="mb-5">
                    <h2 class="text-xl font-black text-slate-950">Gider Bilgileri</h2>
                    <p class="text-sm text-slate-500">Giderin konusu, kategorisi ve belge bilgilerini girin.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <NxInput
                        v-model="form.expense_date"
                        type="date"
                        label="Gider Tarihi"
                        :error="form.errors.expense_date"
                    />
                    <NxInput
                        v-model="form.due_date"
                        type="date"
                        label="Vade Tarihi"
                        :error="form.errors.due_date"
                    />
                    <NxSelect
                        v-model="form.expense_category_id"
                        label="Gider Kategorisi"
                        :options="categories"
                        option-label="label"
                        option-value="value"
                        :error="form.errors.expense_category_id"
                    />
                    <NxInput
                        v-model="form.title"
                        label="Başlık"
                        :error="form.errors.title"
                    />
                    <NxInput
                        v-model="form.supplier_name"
                        label="Tedarikçi"
                        :error="form.errors.supplier_name"
                    />
                    <NxInput
                        v-model="form.document_no"
                        label="Belge No"
                        :error="form.errors.document_no"
                    />
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium text-slate-700">Açıklama</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    />
                </div>
            </NxCard>

            <NxCard>
                <div class="mb-5">
                    <h2 class="text-xl font-black text-slate-950">Ödeme Bilgileri</h2>
                    <p class="text-sm text-slate-500">Ödeme varsa kasa çıkışı otomatik oluşturulur.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <NxInput
                        v-model="form.paid_total"
                        type="number"
                        step="0.01"
                        label="Ödenen Tutar"
                        :error="form.errors.paid_total"
                    />
                    <NxSelect
                        v-model="form.payment_method"
                        label="Ödeme Yöntemi"
                        :options="paymentMethods"
                        option-label="label"
                        option-value="value"
                        :error="form.errors.payment_method"
                    />
                    <NxSelect
                        v-model="form.payment_source_id"
                        label="Kasa / Banka Hesabı"
                        :options="cashAccounts"
                        option-label="label"
                        option-value="value"
                        :error="form.errors.payment_source_id"
                    />
                </div>

                <div class="mt-4">
                    <label class="text-sm font-medium text-slate-700">Notlar</label>
                    <textarea
                        v-model="form.notes"
                        rows="3"
                        class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    />
                </div>
            </NxCard>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <NxCard>
                <h2 class="text-xl font-black text-slate-950">Tutar Özeti</h2>
                <div class="mt-5 space-y-4">
                    <NxInput
                        v-model="form.subtotal"
                        type="number"
                        step="0.01"
                        label="KDV Hariç Tutar"
                        :error="form.errors.subtotal"
                    />
                    <NxInput
                        v-model="form.discount"
                        type="number"
                        step="0.01"
                        label="İskonto"
                        :error="form.errors.discount"
                    />
                    <NxInput
                        v-model="form.vat_rate"
                        type="number"
                        step="0.01"
                        label="KDV Oranı %"
                        :error="form.errors.vat_rate"
                    />
                </div>

                <div class="mt-6 space-y-3 rounded-2xl bg-slate-50 p-4 text-sm">
                    <div class="flex justify-between">
                        <span>Net Gider</span>
                        <strong>₺{{ money(netTotal) }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>KDV</span>
                        <strong>₺{{ money(vatTotal) }}</strong>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="font-black">Genel Toplam</span>
                        <strong class="text-blue-700">₺{{ money(grandTotal) }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Kalan</span>
                        <strong>₺{{ money(remainingTotal) }}</strong>
                    </div>
                </div>

                <NxButton
                    type="submit"
                    class="mt-6 w-full"
                    :loading="form.processing"
                >
                    {{ expense ? 'Gideri Güncelle' : 'Gideri Kaydet' }}
                </NxButton>
            </NxCard>
        </div>
    </form>
</template>
