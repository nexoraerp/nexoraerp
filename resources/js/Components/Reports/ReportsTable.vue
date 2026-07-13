<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ArrowDownUp, Eye, FileDown } from 'lucide-vue-next';

import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxSearch from '@/Components/UI/NxSearch.vue';

const props = defineProps({
    rows: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');
const sortKey = ref('date');
const sortDirection = ref('desc');

const columns = [
    { key: 'date', label: 'Tarih', align: 'left' },
    { key: 'no', label: 'Belge No', align: 'left' },
    { key: 'customer', label: 'Müşteri', align: 'left' },
    { key: 'sales', label: 'Satış', align: 'right' },
    { key: 'vat', label: 'KDV', align: 'right' },
    { key: 'profit', label: 'Kar', align: 'right' },
    { key: 'payment', label: 'Ödeme Tipi', align: 'center' },
    { key: 'status', label: 'Durum', align: 'center' },
];

const formatMoney = (value) => Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const sortBy = (key) => {
    if (sortKey.value === key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';

        return;
    }

    sortKey.value = key;
    sortDirection.value = 'asc';
};

const filteredRows = computed(() => {
    const keyword = search.value.toLowerCase();

    return props.rows
        .filter(row => (
            row.no.toLowerCase().includes(keyword) ||
            row.customer.toLowerCase().includes(keyword) ||
            row.payment.toLowerCase().includes(keyword) ||
            row.status.toLowerCase().includes(keyword)
        ))
        .sort((a, b) => {
            const aValue = a[sortKey.value];
            const bValue = b[sortKey.value];
            const result = aValue > bValue ? 1 : aValue < bValue ? -1 : 0;

            return sortDirection.value === 'asc' ? result : -result;
        });
});

const statusClass = (status) => {
    if (status === 'Tamamlandı') {
        return 'bg-emerald-50 text-emerald-700';
    }

    if (status === 'Açık') {
        return 'bg-amber-50 text-amber-700';
    }

    return 'bg-blue-50 text-blue-700';
};
</script>

<template>
    <NxCard>
        <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-950">Gelişmiş Rapor Tablosu</h2>
                <p class="text-sm text-slate-500">Satış, KDV, kar ve ödeme durumunu belge bazında izleyin.</p>
            </div>

            <div class="w-full xl:w-80">
                <NxSearch
                    v-model="search"
                    placeholder="Tabloda ara..."
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1020px]">
                <thead class="border-b bg-slate-50">
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            class="px-4 py-4 text-sm font-bold text-slate-600"
                            :class="{
                                'text-left': column.align === 'left',
                                'text-right': column.align === 'right',
                                'text-center': column.align === 'center',
                            }"
                        >
                            <button
                                type="button"
                                class="inline-flex items-center gap-2"
                                @click="sortBy(column.key)"
                            >
                                {{ column.label }}
                                <ArrowDownUp class="h-3.5 w-3.5 text-slate-400" />
                            </button>
                        </th>

                        <th class="px-4 py-4 text-right text-sm font-bold text-slate-600">İşlemler</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="row in filteredRows"
                        :key="row.no"
                        class="border-b transition hover:bg-slate-50"
                    >
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ row.date }}</td>
                        <td class="px-4 py-4 font-bold text-blue-700">{{ row.no }}</td>
                        <td class="px-4 py-4">{{ row.customer }}</td>
                        <td class="px-4 py-4 text-right font-bold">₺{{ formatMoney(row.sales) }}</td>
                        <td class="px-4 py-4 text-right">₺{{ formatMoney(row.vat) }}</td>
                        <td class="px-4 py-4 text-right font-bold text-emerald-700">₺{{ formatMoney(row.profit) }}</td>
                        <td class="px-4 py-4 text-center">{{ row.payment }}</td>
                        <td class="px-4 py-4 text-center">
                            <span
                                class="rounded-full px-3 py-1 text-xs font-bold"
                                :class="statusClass(row.status)"
                            >
                                {{ row.status }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-end gap-2">
                                <Link
                                    :href="row.show_url"
                                    class="inline-flex items-center justify-center rounded-2xl bg-transparent px-4 py-2 text-sm font-semibold text-slate-700 transition-all duration-300 hover:bg-slate-100"
                                >
                                    <Eye class="h-4 w-4" />
                                </Link>

                                <NxButton
                                    size="sm"
                                    variant="secondary"
                                >
                                    <FileDown class="h-4 w-4" />
                                </NxButton>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="filteredRows.length === 0"
                class="rounded-b-2xl border-x border-b border-slate-100 bg-slate-50 p-8 text-center text-sm text-slate-500"
            >
                Seçili filtrelere uygun tamamlanan satış kaydı bulunmuyor.
            </div>
        </div>

        <div class="mt-5 flex items-center justify-between text-sm text-slate-500">
            <span>{{ filteredRows.length }} kayıt gösteriliyor</span>
            <span>Sıralama: {{ sortKey }} / {{ sortDirection === 'asc' ? 'Artan' : 'Azalan' }}</span>
        </div>
    </NxCard>
</template>
