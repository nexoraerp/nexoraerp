<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    expenses: {
        type: Array,
        default: () => [],
    },
    kpis: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref('');

const filteredExpenses = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('tr-TR');

    if (!term) {
        return props.expenses;
    }

    return props.expenses.filter((expense) => [
        expense.expense_no,
        expense.title,
        expense.supplier_name,
        expense.document_no,
        expense.category?.name,
        expense.payment_status,
        expense.status,
    ].some(value => String(value ?? '').toLocaleLowerCase('tr-TR').includes(term)));
});

const money = value => `₺${Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
})}`;

const statusLabel = {
    Draft: 'Taslak',
    Approved: 'Onaylı',
    PartiallyPaid: 'Kısmi Ödendi',
    Paid: 'Ödendi',
    Cancelled: 'İptal',
};

const paymentLabel = {
    Unpaid: 'Ödenmedi',
    Partial: 'Kısmi',
    Paid: 'Ödendi',
};
</script>

<template>
    <Head title="Gider Yönetimi" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Gider Yönetimi"
                subtitle="Faaliyet giderlerinizi, ödeme durumlarını ve KDV kırılımlarını takip edin."
            >
                <div class="flex gap-3">
                    <Link :href="route('expenses.categories')">
                        <NxButton variant="secondary">Gider Kategorileri</NxButton>
                    </Link>
                    <Link :href="route('expenses.create')">
                        <NxButton>
                            <Plus class="mr-2 h-4 w-4" />
                            Yeni Gider
                        </NxButton>
                    </Link>
                </div>
            </NxPageHeader>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Bugünkü Gider</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ money(kpis.today) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Bu Ayki Gider</p>
                    <h2 class="mt-2 text-2xl font-black text-blue-700">{{ money(kpis.month) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Bu Yılki Gider</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ money(kpis.year) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Bekleyen Gider</p>
                    <h2 class="mt-2 text-2xl font-black text-amber-700">{{ kpis.pending ?? 0 }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Ödenmemiş Toplam</p>
                    <h2 class="mt-2 text-2xl font-black text-red-700">{{ money(kpis.unpaid_total) }}</h2>
                </NxCard>
            </div>

            <NxCard>
                <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-950">Giderler</h2>
                        <p class="text-sm text-slate-500">{{ filteredExpenses.length }} kayıt görüntüleniyor.</p>
                    </div>
                    <label class="relative w-full xl:max-w-md">
                        <Search class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Gider no, kategori, tedarikçi veya belge ara"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        >
                    </label>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1200px]">
                        <thead class="border-b bg-slate-50 text-sm text-slate-500">
                            <tr>
                                <th class="px-4 py-4 text-left">Tarih</th>
                                <th class="text-left">Gider No</th>
                                <th class="text-left">Kategori</th>
                                <th class="text-left">Başlık</th>
                                <th class="text-left">Tedarikçi</th>
                                <th class="text-right">KDV Hariç</th>
                                <th class="text-right">KDV</th>
                                <th class="text-right">Genel Toplam</th>
                                <th class="text-right">Kalan</th>
                                <th class="text-center">Ödeme</th>
                                <th class="text-center">Durum</th>
                                <th class="pr-4 text-right">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="expense in filteredExpenses"
                                :key="expense.id"
                                class="border-b text-sm hover:bg-slate-50"
                            >
                                <td class="px-4 py-4 font-semibold">{{ expense.expense_date }}</td>
                                <td class="font-black text-slate-950">{{ expense.expense_no }}</td>
                                <td>{{ expense.category?.name ?? '-' }}</td>
                                <td class="font-semibold">{{ expense.title }}</td>
                                <td>{{ expense.supplier_name ?? '-' }}</td>
                                <td class="text-right">{{ money(Number(expense.subtotal) - Number(expense.discount)) }}</td>
                                <td class="text-right">{{ money(expense.vat) }}</td>
                                <td class="text-right font-black">{{ money(expense.grand_total) }}</td>
                                <td class="text-right">{{ money(expense.remaining_total) }}</td>
                                <td class="text-center">{{ paymentLabel[expense.payment_status] ?? expense.payment_status }}</td>
                                <td class="text-center">{{ statusLabel[expense.status] ?? expense.status }}</td>
                                <td class="pr-4 text-right">
                                    <Link
                                        :href="route('expenses.show', expense.id)"
                                        class="rounded-xl px-3 py-2 font-bold text-blue-700 hover:bg-blue-50"
                                    >
                                        Aç
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </NxCard>
        </div>
    </NxLayout>
</template>
