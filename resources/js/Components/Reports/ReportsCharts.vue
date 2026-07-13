<script setup>
import { computed } from 'vue';
import {
    Chart as ChartJS,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    LineElement,
    PointElement,
    Tooltip,
    Legend,
} from 'chart.js';
import { Bar, Doughnut, Line } from 'vue-chartjs';

import NxCard from '@/Components/UI/NxCard.vue';

const props = defineProps({
    charts: {
        type: Object,
        default: () => ({}),
    },
});

ChartJS.register(
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    LineElement,
    PointElement,
    Tooltip,
    Legend
);

const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: '#e2e8f0',
            },
        },
        x: {
            grid: {
                display: false,
            },
        },
    },
};

const salesData = computed(() => ({
    labels: props.charts.labels ?? [],
    datasets: [
        {
            label: 'Satış',
            data: props.charts.sales ?? [],
            borderColor: '#2563eb',
            backgroundColor: '#2563eb',
            borderWidth: 3,
            tension: 0.35,
            pointRadius: 3,
        },
    ],
}));

const profitData = computed(() => ({
    labels: props.charts.labels ?? [],
    datasets: [
        {
            label: 'Kar',
            data: props.charts.profit ?? [],
            borderColor: '#16a34a',
            backgroundColor: '#16a34a',
            borderWidth: 3,
            tension: 0.35,
            pointRadius: 3,
        },
    ],
}));

const categoryData = computed(() => ({
    labels: props.charts.categoryLabels ?? [],
    datasets: [
        {
            label: 'Kategori Satış',
            data: props.charts.categorySales ?? [],
            backgroundColor: ['#2563eb', '#16a34a', '#0891b2', '#9333ea', '#f59e0b'],
            borderRadius: 8,
        },
    ],
}));

const paymentData = computed(() => ({
    labels: props.charts.paymentLabels ?? [],
    datasets: [
        {
            data: props.charts.paymentValues ?? [],
            backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#64748b'],
            borderColor: '#ffffff',
            borderWidth: 4,
        },
    ],
}));

const pieOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                boxWidth: 10,
                usePointStyle: true,
            },
        },
    },
};
</script>

<template>
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <NxCard>
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-950">Satış Grafiği</h2>
                    <p class="text-sm text-slate-500">Dönem içi satış trendi</p>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">Aylık</span>
            </div>

            <div class="h-72">
                <Line
                    :data="salesData"
                    :options="lineOptions"
                />
            </div>
        </NxCard>

        <NxCard>
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-950">Kar Grafiği</h2>
                    <p class="text-sm text-slate-500">Tahmini brüt kar değişimi</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Net</span>
            </div>

            <div class="h-72">
                <Line
                    :data="profitData"
                    :options="lineOptions"
                />
            </div>
        </NxCard>

        <NxCard>
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-950">Kategori Bazlı Satış</h2>
                <p class="text-sm text-slate-500">Kategori performansı ve ciro katkısı</p>
            </div>

            <div class="h-72">
                <Bar
                    :data="categoryData"
                    :options="lineOptions"
                />
            </div>
        </NxCard>

        <NxCard>
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-950">Ödeme Tipi Dağılımı</h2>
                <p class="text-sm text-slate-500">Tahsilat kanalı yoğunluğu</p>
            </div>

            <div class="h-72">
                <Doughnut
                    :data="paymentData"
                    :options="pieOptions"
                />
            </div>
        </NxCard>
    </div>
</template>
