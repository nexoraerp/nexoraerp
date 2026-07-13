<script setup>
import { RefreshCw } from 'lucide-vue-next';

defineProps({
    rates: {
        type: Object,
        default: () => ({
            available: false,
            items: [],
        }),
    },
    compact: {
        type: Boolean,
        default: false,
    },
});

const formatRate = (value) => {
    if (value === null || value === undefined) {
        return '-';
    }

    return Number(value).toLocaleString('tr-TR', {
        minimumFractionDigits: 4,
        maximumFractionDigits: 4,
    });
};
</script>

<template>
    <section
        class="overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
        :class="compact ? 'h-11 w-[460px] rounded-2xl 2xl:w-[560px]' : 'rounded-3xl'"
    >
        <div
            v-if="rates.available && rates.items?.length"
            class="flex items-center"
            :class="compact ? 'h-full gap-2' : 'gap-4'"
        >
            <div
                class="flex shrink-0 items-center gap-2 border-r border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900"
                :class="compact ? 'h-full px-3' : 'px-5 py-4'"
            >
                <RefreshCw class="h-3.5 w-3.5 text-blue-700" />
                <div>
                    <div
                        class="font-black uppercase text-blue-700"
                        :class="compact ? 'text-[10px] tracking-[0.12em]' : 'text-xs tracking-[0.18em]'"
                    >
                        Anlık Kur
                    </div>
                    <div
                        v-if="!compact"
                        class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400"
                    >
                        {{ rates.source ?? 'TCMB' }}
                        <span v-if="rates.date">- {{ rates.date }}</span>
                    </div>
                </div>
            </div>

            <div
                class="relative min-w-0 flex-1 overflow-hidden"
                :class="compact ? 'py-1.5' : 'py-3'"
            >
                <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-10 bg-gradient-to-r from-white to-transparent dark:from-slate-950"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-10 bg-gradient-to-l from-white to-transparent dark:from-slate-950"></div>

                <div
                    class="flex w-max animate-[nexoraTicker_34s_linear_infinite] items-center"
                    :class="compact ? 'gap-2' : 'gap-3'"
                >
                    <template
                        v-for="copy in 2"
                        :key="copy"
                    >
                        <div
                            v-for="rate in rates.items"
                            :key="`${copy}-${rate.code}`"
                            class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900"
                            :class="compact ? 'min-w-[172px] gap-2 px-3 py-1.5' : 'min-w-[260px] gap-4 px-4 py-3'"
                        >
                            <div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="font-black text-slate-950 dark:text-slate-100"
                                        :class="compact ? 'text-sm' : 'text-lg'"
                                    >
                                        {{ rate.code }}
                                    </span>
                                    <span
                                        v-if="!compact"
                                        class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-black text-blue-700"
                                    >
                                        TRY
                                    </span>
                                </div>
                                <div
                                    v-if="!compact"
                                    class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400"
                                >
                                    {{ rate.name }}
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-2 text-right text-xs"
                                :class="compact ? 'gap-2' : 'gap-3'"
                            >
                                <div>
                                    <div
                                        v-if="!compact"
                                        class="font-bold text-slate-400 dark:text-slate-500"
                                    >
                                        Alış
                                    </div>
                                    <div
                                        class="font-black text-slate-950 dark:text-slate-100"
                                        :class="compact ? 'text-xs' : 'mt-1 text-sm'"
                                    >
                                        {{ formatRate(rate.forex_buying) }}
                                    </div>
                                </div>
                                <div>
                                    <div
                                        v-if="!compact"
                                        class="font-bold text-slate-400 dark:text-slate-500"
                                    >
                                        Satış
                                    </div>
                                    <div
                                        class="font-black text-slate-950 dark:text-slate-100"
                                        :class="compact ? 'text-xs' : 'mt-1 text-sm'"
                                    >
                                        {{ formatRate(rate.forex_selling) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div
            v-else
            class="flex h-full items-center gap-2 bg-amber-50 px-4 text-xs font-semibold text-amber-800 dark:bg-amber-950/40 dark:text-amber-200"
        >
            <RefreshCw class="h-3.5 w-3.5" />
            Kur bilgisi alınamadı.
        </div>

        <div
            v-if="!compact"
            class="border-t border-slate-100 px-5 py-2 text-[11px] font-semibold text-slate-400 dark:border-slate-800 dark:text-slate-500"
        >
            Son kontrol: {{ rates.updated_at ?? '-' }}
        </div>
    </section>
</template>
