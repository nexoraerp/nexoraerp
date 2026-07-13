<script setup>
import { Head } from '@inertiajs/vue3';
import { Download, FileSpreadsheet, Printer } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import ReportsCards from '@/Components/Reports/ReportsCards.vue';
import ReportsCharts from '@/Components/Reports/ReportsCharts.vue';
import ReportsFilters from '@/Components/Reports/ReportsFilters.vue';
import ReportsSidebar from '@/Components/Reports/ReportsSidebar.vue';
import ReportsStats from '@/Components/Reports/ReportsStats.vue';
import ReportsTable from '@/Components/Reports/ReportsTable.vue';

defineProps({
    filters: {
        type: Object,
        default: () => ({}),
    },
    filterOptions: {
        type: Object,
        default: () => ({}),
    },
    kpis: {
        type: Array,
        default: () => [],
    },
    charts: {
        type: Object,
        default: () => ({}),
    },
    tableRows: {
        type: Array,
        default: () => [],
    },
    insights: {
        type: Array,
        default: () => [],
    },
    reportCards: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Raporlar" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Raporlar"
                subtitle="İşletmenizin satış, finans ve stok performansını analiz edin."
            >
                <div class="flex flex-wrap gap-3">
                    <NxButton variant="secondary">
                        <Download class="mr-2 h-4 w-4" />
                        PDF Aktar
                    </NxButton>

                    <NxButton variant="secondary">
                        <FileSpreadsheet class="mr-2 h-4 w-4" />
                        Excel Aktar
                    </NxButton>

                    <NxButton variant="secondary">
                        <Printer class="mr-2 h-4 w-4" />
                        Yazdır
                    </NxButton>
                </div>
            </NxPageHeader>

            <ReportsFilters
                :initial-filters="filters"
                :filter-options="filterOptions"
            />

            <ReportsStats :items="kpis" />

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 space-y-6 2xl:col-span-9">
                    <ReportsCharts :charts="charts" />
                    <ReportsCards :reports="reportCards" />
                    <ReportsTable :rows="tableRows" />
                </div>

                <div class="col-span-12 2xl:col-span-3">
                    <div class="sticky top-6">
                        <ReportsSidebar :insights="insights" />
                    </div>
                </div>
            </div>
        </div>
    </NxLayout>
</template>
