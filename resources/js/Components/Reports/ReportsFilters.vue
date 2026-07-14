<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { CalendarDays, RotateCcw, SlidersHorizontal } from 'lucide-vue-next';

import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxInput from '@/Components/UI/NxInput.vue';

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
    filterOptions: {
        type: Object,
        default: () => ({}),
    },
});

const ranges = [
    { value: 'today', label: 'Bugün' },
    { value: 'week', label: 'Bu Hafta' },
    { value: 'month', label: 'Bu Ay' },
    { value: 'year', label: 'Bu Yıl' },
    { value: 'custom', label: 'Özel Tarih' },
];

const form = reactive({
    range: props.initialFilters.range ?? 'month',
    startDate: props.initialFilters.startDate ?? new Date().toISOString().substring(0, 10),
    endDate: props.initialFilters.endDate ?? new Date().toISOString().substring(0, 10),
    branch: props.initialFilters.branch ?? 'all',
    warehouse: props.initialFilters.warehouse ?? 'all',
    customer: props.initialFilters.customer ?? 'all',
    product: props.initialFilters.product ?? 'all',
    category: props.initialFilters.category ?? 'all',
    user: props.initialFilters.user ?? 'all',
    paymentType: props.initialFilters.paymentType ?? 'all',
});

const clear = () => {
    form.range = 'month';
    form.startDate = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().substring(0, 10);
    form.endDate = new Date().toISOString().substring(0, 10);
    form.branch = 'all';
    form.warehouse = 'all';
    form.customer = 'all';
    form.product = 'all';
    form.category = 'all';
    form.user = 'all';
    form.paymentType = 'all';

    apply();
};

const apply = () => {
    router.get(route('reports.index'), { ...form }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <NxCard>
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                    <SlidersHorizontal class="h-5 w-5" />
                </div>

                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Rapor Filtreleri</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Dönem, depo, cari ve ürün kırılımlarını daraltın.</p>
                </div>
            </div>

            <div class="hidden items-center gap-2 text-sm font-semibold text-slate-500 lg:flex">
                <CalendarDays class="h-4 w-4" />
                {{ form.startDate }} - {{ form.endDate }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <div class="lg:col-span-2">
                <NxSelect
                    label="Tarih Aralığı"
                    v-model="form.range"
                    :options="ranges"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxInput
                    label="Başlangıç"
                    type="date"
                    v-model="form.startDate"
                />
            </div>

            <div class="lg:col-span-2">
                <NxInput
                    label="Bitiş"
                    type="date"
                    v-model="form.endDate"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Şube"
                    v-model="form.branch"
                    :options="filterOptions.branches ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Depo"
                    v-model="form.warehouse"
                    :options="filterOptions.warehouses ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Müşteri"
                    v-model="form.customer"
                    :options="filterOptions.customers ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Ürün"
                    v-model="form.product"
                    :options="filterOptions.products ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Kategori"
                    v-model="form.category"
                    :options="filterOptions.categories ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Personel"
                    v-model="form.user"
                    :options="filterOptions.users ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="lg:col-span-2">
                <NxSelect
                    label="Ödeme Tipi"
                    v-model="form.paymentType"
                    :options="filterOptions.paymentTypes ?? []"
                    option-label="label"
                    option-value="value"
                />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end lg:col-span-4">
                <NxButton
                    type="button"
                    block
                    @click="apply"
                >
                    Filtre Uygula
                </NxButton>

                <NxButton
                    type="button"
                    variant="secondary"
                    block
                    @click="clear"
                >
                    <RotateCcw class="mr-2 h-4 w-4" />
                    Temizle
                </NxButton>
            </div>
        </div>
    </NxCard>
</template>
