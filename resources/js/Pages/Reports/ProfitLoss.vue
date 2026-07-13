<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Bar, Line } from 'vue-chartjs';
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';
import { computed, reactive } from 'vue';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Tooltip, Legend);

const props = defineProps({
    filters: Object,
    kpis: Object,
    charts: Object,
    monthlyRows: Array,
    topExpenseCategories: Array,
    aiSummary: String,
});

const form = reactive({
    range: props.filters?.range ?? 'month',
    startDate: props.filters?.startDate ?? '',
    endDate: props.filters?.endDate ?? '',
});

const money = value => `₺${Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
})}`;

const percent = value => `%${Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
})}`;

const submit = () => {
    router.get(route('reports.profit-loss'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const incomeExpenseChart = computed(() => ({
    labels: props.charts?.labels ?? [],
    datasets: [
        {
            label: 'Net Satış Geliri',
            data: props.charts?.sales ?? [],
            backgroundColor: '#2563eb',
        },
        {
            label: 'Faaliyet Giderleri',
            data: props.charts?.expenses ?? [],
            backgroundColor: '#dc2626',
        },
    ],
}));

const profitTrendChart = computed(() => ({
    labels: props.charts?.labels ?? [],
    datasets: [
        {
            label: 'Brüt Kâr',
            data: props.charts?.grossProfit ?? [],
            borderColor: '#059669',
            tension: 0.35,
        },
        {
            label: 'Faaliyet Kârı',
            data: props.charts?.operatingProfit ?? [],
            borderColor: '#7c3aed',
            tension: 0.35,
        },
    ],
}));

const categoryChart = computed(() => ({
    labels: props.charts?.expenseCategoryLabels ?? [],
    datasets: [
        {
            label: 'Gider',
            data: props.charts?.expenseCategoryValues ?? [],
            backgroundColor: '#f59e0b',
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
        },
    },
};
</script>

<template>
    <Head title="Kâr/Zarar Raporu" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Kâr/Zarar Raporu"
                subtitle="Tahakkuk eden satış geliri, satış maliyeti ve faaliyet giderlerine göre analiz."
            />

            <NxCard>
                <form
                    class="grid gap-4 md:grid-cols-[180px_1fr_1fr_auto]"
                    @submit.prevent="submit"
                >
                    <select
                        v-model="form.range"
                        class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold"
                    >
                        <option value="today">Bugün</option>
                        <option value="week">Bu Hafta</option>
                        <option value="month">Bu Ay</option>
                        <option value="year">Bu Yıl</option>
                        <option value="custom">Özel Tarih</option>
                    </select>
                    <input
                        v-model="form.startDate"
                        type="date"
                        class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold"
                    >
                    <input
                        v-model="form.endDate"
                        type="date"
                        class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold"
                    >
                    <NxButton type="submit">Filtrele</NxButton>
                </form>
            </NxCard>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Net Satış Geliri</p>
                    <h2 class="mt-2 text-2xl font-black text-blue-700">{{ money(kpis.net_sales) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Satışların Maliyeti</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ money(kpis.cost_of_sales) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Brüt Kâr</p>
                    <h2 class="mt-2 text-2xl font-black text-emerald-700">{{ money(kpis.gross_profit) }}</h2>
                    <p class="mt-1 text-xs font-bold text-slate-500">{{ percent(kpis.gross_margin) }}</p>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Faaliyet Gideri</p>
                    <h2 class="mt-2 text-2xl font-black text-red-700">{{ money(kpis.operating_expenses) }}</h2>
                </NxCard>
                <NxCard>
                    <p class="text-sm font-semibold text-slate-500">Faaliyet Kârı/Zararı</p>
                    <h2 class="mt-2 text-2xl font-black text-purple-700">{{ money(kpis.operating_profit) }}</h2>
                    <p class="mt-1 text-xs font-bold text-slate-500">{{ percent(kpis.operating_margin) }}</p>
                </NxCard>
            </div>

            <div class="grid gap-6 xl:grid-cols-3">
                <NxCard class="xl:col-span-2">
                    <h2 class="mb-4 text-xl font-black text-slate-950">Gelir / Gider Karşılaştırması</h2>
                    <div class="h-80">
                        <Bar
                            :data="incomeExpenseChart"
                            :options="chartOptions"
                        />
                    </div>
                </NxCard>

                <NxCard>
                    <h2 class="mb-4 text-xl font-black text-slate-950">KDV Operasyon Bilgisi</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Satış KDV’si</span>
                            <strong>{{ money(kpis.sales_vat) }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Gider KDV’si</span>
                            <strong>{{ money(kpis.expense_vat) }}</strong>
                        </div>
                        <div class="flex justify-between border-t pt-4">
                            <span class="font-black">Tahmini Ödenecek KDV</span>
                            <strong class="text-blue-700">{{ money(kpis.estimated_vat_payable) }}</strong>
                        </div>
                        <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                            KDV kâr hesabına dahil edilmez; yalnızca operasyonel bilgi olarak gösterilir.
                        </p>
                    </div>
                </NxCard>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <NxCard>
                    <h2 class="mb-4 text-xl font-black text-slate-950">Kâr Trendi</h2>
                    <div class="h-80">
                        <Line
                            :data="profitTrendChart"
                            :options="chartOptions"
                        />
                    </div>
                </NxCard>

                <NxCard>
                    <h2 class="mb-4 text-xl font-black text-slate-950">Kategori Bazlı Gider</h2>
                    <div class="h-80">
                        <Bar
                            :data="categoryChart"
                            :options="chartOptions"
                        />
                    </div>
                </NxCard>
            </div>

            <NxCard>
                <h2 class="mb-4 text-xl font-black text-slate-950">Aylık Kâr/Zarar Tablosu</h2>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead class="border-b bg-slate-50 text-sm text-slate-500">
                            <tr>
                                <th class="px-4 py-4 text-left">Ay</th>
                                <th class="text-right">Net Satış</th>
                                <th class="text-right">Satış Maliyeti</th>
                                <th class="text-right">Brüt Kâr</th>
                                <th class="text-right">Faaliyet Gideri</th>
                                <th class="pr-4 text-right">Faaliyet Kârı</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in monthlyRows"
                                :key="row.month"
                                class="border-b text-sm"
                            >
                                <td class="px-4 py-4 font-black">{{ row.month }}</td>
                                <td class="text-right">{{ money(row.net_sales) }}</td>
                                <td class="text-right">{{ money(row.cost_of_sales) }}</td>
                                <td class="text-right">{{ money(row.gross_profit) }}</td>
                                <td class="text-right">{{ money(row.operating_expenses) }}</td>
                                <td class="pr-4 text-right font-black">{{ money(row.operating_profit) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </NxCard>

            <div class="grid gap-6 xl:grid-cols-2">
                <NxCard>
                    <h2 class="mb-4 text-xl font-black text-slate-950">En Çok Gider Oluşturan Kategoriler</h2>
                    <div class="space-y-3">
                        <div
                            v-for="category in topExpenseCategories"
                            :key="category.label"
                            class="flex justify-between rounded-2xl bg-slate-50 p-4"
                        >
                            <span class="font-bold">{{ category.label }}</span>
                            <strong>{{ money(category.value) }}</strong>
                        </div>
                    </div>
                </NxCard>

                <NxCard>
                    <h2 class="mb-4 text-xl font-black text-slate-950">Nexora AI Analiz Verisi</h2>
                    <p class="leading-7 text-slate-600">{{ aiSummary }}</p>
                </NxCard>
            </div>
        </div>
    </NxLayout>
</template>
