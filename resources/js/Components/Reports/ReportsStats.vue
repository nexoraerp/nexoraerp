<script setup>
import { computed } from 'vue';
import { Banknote, BarChart3, Boxes, Calculator, Package, ReceiptText, TrendingUp, WalletCards } from 'lucide-vue-next';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});

const icons = [ReceiptText, WalletCards, Calculator, TrendingUp, BarChart3, Boxes, Banknote, Package];

const tones = {
    blue: 'bg-blue-50 text-blue-700 border-blue-100',
    emerald: 'bg-emerald-50 text-emerald-700 border-emerald-100',
    cyan: 'bg-cyan-50 text-cyan-700 border-cyan-100',
    green: 'bg-green-50 text-green-700 border-green-100',
    purple: 'bg-purple-50 text-purple-700 border-purple-100',
    indigo: 'bg-indigo-50 text-indigo-700 border-indigo-100',
    amber: 'bg-amber-50 text-amber-700 border-amber-100',
    slate: 'bg-slate-100 text-slate-700 border-slate-200',
};

const items = computed(() => props.items.map((item, index) => ({
    ...item,
    icon: icons[index],
})));
</script>

<template>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div
            v-for="item in items"
            :key="item.label"
            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">{{ item.label }}</p>
                    <h3 class="mt-3 text-3xl font-black tracking-tight text-slate-950">{{ item.value }}</h3>
                </div>

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border"
                    :class="tones[item.tone] ?? tones.blue"
                >
                    <component
                        :is="item.icon"
                        class="h-5 w-5"
                    />
                </div>
            </div>

            <p class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-500">{{ item.hint }}</p>
        </div>
    </div>
</template>
