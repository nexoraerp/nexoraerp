<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    BellRing,
    CalendarClock,
    CircleDollarSign,
    TrendingUp,
} from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    dueReminders: {
        type: Object,
        default: () => ({
            overdue_count: 0,
            today_count: 0,
            upcoming_count: 0,
            total_amount: 0,
            items: [],
        }),
    },
    risks: {
        type: Array,
        default: () => [],
    },
});

const money = (value) => new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
}).format(Number(value || 0));

const dueItems = computed(() => props.dueReminders.items ?? []);
const highRiskCount = computed(() => props.risks.filter(risk => risk.level === 'high').length);

const statusClasses = {
    overdue: 'bg-red-50 text-red-700',
    today: 'bg-amber-50 text-amber-700',
    upcoming: 'bg-blue-50 text-blue-700',
};

const statusLabels = {
    overdue: 'Gecikmiş',
    today: 'Bugün',
    upcoming: 'Yaklaşan',
};

const riskClasses = {
    high: 'border-red-500/30 bg-red-500/10 text-red-300',
    medium: 'border-amber-500/30 bg-amber-500/10 text-amber-300',
    low: 'border-blue-500/30 bg-blue-500/10 text-blue-300',
};

const riskLabels = {
    high: 'Yüksek',
    medium: 'Orta',
    low: 'Düşük',
};
</script>

<template>
    <Head title="Risk Analizi" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Risk Analizi"
                subtitle="Nexora AI tarafından izlenen vade, stok ve işlem risklerini detaylı inceleyin."
            />

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Geciken Vade</p>
                            <h2 class="mt-2 text-3xl font-black text-red-700">{{ dueReminders.overdue_count }}</h2>
                        </div>
                        <CalendarClock class="h-8 w-8 text-red-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Bugünkü Vade</p>
                            <h2 class="mt-2 text-3xl font-black text-amber-700">{{ dueReminders.today_count }}</h2>
                        </div>
                        <BellRing class="h-8 w-8 text-amber-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Takip Tutarı</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ money(dueReminders.total_amount) }}</h2>
                        </div>
                        <CircleDollarSign class="h-8 w-8 text-blue-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Yüksek Risk</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ highRiskCount }}</h2>
                        </div>
                        <AlertTriangle class="h-8 w-8 text-red-700" />
                    </div>
                </NxCard>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <NxCard class="xl:col-span-2">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-950">Vade Riskleri</h2>
                            <p class="text-sm text-slate-500">Nexora AI vadesi geçen ve yaklaşan tahsilatları önceliklendirir.</p>
                        </div>
                        <Activity class="h-6 w-6 text-blue-700" />
                    </div>

                    <div v-if="dueItems.length" class="space-y-3">
                        <div
                            v-for="item in dueItems"
                            :key="item.sale_id"
                            class="flex flex-col gap-3 rounded-2xl border border-slate-100 p-4 md:flex-row md:items-center md:justify-between"
                        >
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold text-slate-950">{{ item.sale_no }}</span>
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-bold"
                                        :class="statusClasses[item.status]"
                                    >
                                        {{ statusLabels[item.status] }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ item.message }}</p>
                            </div>
                            <div class="shrink-0 text-left md:text-right">
                                <div class="font-black text-slate-950">{{ money(item.remaining_total) }}</div>
                                <div class="text-sm text-slate-500">{{ item.due_date }}</div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="rounded-2xl border border-slate-100 bg-slate-50 p-6 text-sm text-slate-500">
                        Takip edilecek açık vade bulunmuyor.
                    </div>
                </NxCard>

                <NxCard>
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-950">Risk Sinyalleri</h2>
                            <p class="text-sm text-slate-500">Tahsilat ve stok riskleri.</p>
                        </div>
                        <TrendingUp class="h-6 w-6 text-slate-700" />
                    </div>

                    <div v-if="risks.length" class="space-y-3">
                        <div
                            v-for="(risk, index) in risks"
                            :key="`${risk.title}-${index}`"
                            class="rounded-2xl border p-4"
                            :class="riskClasses[risk.level]"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-black uppercase tracking-wide">{{ riskLabels[risk.level] }} Risk</div>
                                    <h3 class="mt-1 font-black text-slate-950 dark:text-slate-100">{{ risk.title }}</h3>
                                </div>
                                <div class="text-sm font-black">{{ risk.metric }}</div>
                            </div>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ risk.description }}</p>
                            <p class="mt-3 text-xs font-bold text-slate-500 dark:text-slate-400">{{ risk.action }}</p>
                        </div>
                    </div>

                    <div v-else class="rounded-2xl border border-slate-100 bg-slate-50 p-6 text-sm text-slate-500">
                        Aktif risk sinyali bulunmuyor.
                    </div>
                </NxCard>
            </div>
        </div>
    </NxLayout>
</template>
