<script setup>
import { computed } from 'vue';

import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';

import { Line } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend,
    Filler
);

const props = defineProps({
    salesChart: {
        type: Array,
        default: () => [],
    },

    hideSensitive: {
        type: Boolean,
        default: false,
    },
});

const chartData = computed(() => ({
    labels: props.salesChart.map(item => item.date),

    datasets: [
        {
            label: 'Satış',

            data: props.salesChart.map(item => item.sales),

            borderColor: '#2563eb',

            backgroundColor: 'rgba(37,99,235,.15)',

            borderWidth: 3,

            fill: true,

            tension: 0.35,

            pointRadius: 4,
        },
        {
    label: 'Tahsilat',

    data: props.salesChart.map(item => item.collections),

    borderColor: '#16a34a',

    backgroundColor: 'rgba(22,163,74,.15)',

    borderWidth: 3,

    fill: false,

    tension: 0.35,

    pointRadius: 4,

    pointHoverRadius: 6,
}
    ],
}));

const chartOptions = {
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
        },
    },
};
</script>

<template>
    <div class="bg-white rounded-3xl border shadow-sm p-8">

        <div class="mb-6">
            <h2 class="text-2xl font-bold">
                📈 Son 30 Gün Satış Grafiği
            </h2>

            <p class="text-slate-500 mt-2">
                Günlük satış performansı
            </p>
        </div>

        <div
            v-if="hideSensitive"
            class="flex h-96 items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 text-center"
        >
            <div>
                <div class="text-4xl">•••</div>
                <h3 class="mt-4 text-lg font-black text-slate-950">Grafik müşteri modunda gizlendi</h3>
                <p class="mt-2 max-w-md text-sm text-slate-500">
                    Satış ve tahsilat tutarları müşteri yanında görünmesin diye geçici olarak maskelendi.
                </p>
            </div>
        </div>

        <div
            v-else
            class="h-96"
        >

            <Line
                :data="chartData"
                :options="chartOptions"
            />

        </div>

    </div>
</template>
