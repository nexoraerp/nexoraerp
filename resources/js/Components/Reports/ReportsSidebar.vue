<script setup>
import { ArrowUpRight, CircleDollarSign } from 'lucide-vue-next';

import NxCard from '@/Components/UI/NxCard.vue';

defineProps({
    insights: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <NxCard>
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-950">Analiz Özeti</h2>
                <p class="text-sm text-slate-500">Seçili dönemden öne çıkanlar</p>
            </div>
            <CircleDollarSign class="h-6 w-6 text-emerald-600" />
        </div>

        <div class="space-y-3">
            <div
                v-for="insight in insights"
                :key="insight.label"
                class="rounded-2xl border border-slate-200 p-4"
            >
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ insight.label }}</p>
                        <h3 class="mt-2 text-lg font-black text-slate-950">{{ insight.value }}</h3>
                    </div>
                    <ArrowUpRight class="h-4 w-4 text-blue-600" />
                </div>

                <p class="mt-2 text-sm text-slate-500">{{ insight.detail }}</p>
            </div>

            <div
                v-if="insights.length === 0"
                class="rounded-2xl border border-slate-100 bg-slate-50 p-5 text-sm leading-6 text-slate-500"
            >
                Analiz özeti için seçili dönemde tamamlanan satış verisi bulunmuyor.
            </div>
        </div>
    </NxCard>
</template>
