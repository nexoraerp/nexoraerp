<script setup>
import {
    Banknote,
    Calculator,
    FileText,
    Landmark,
    Percent,
    ReceiptText,
    ShoppingCart,
    TrendingUp,
    Wallet,
} from 'lucide-vue-next';

const props = defineProps({

    todayRevenue: {
        type: Number,
        default: 0,
    },

    todaySales: {
        type: Number,
        default: 0,
    },

    todayCollection: {
        type: Number,
        default: 0,
    },

    totalCash: {
        type: Number,
        default: 0,
    },

    totalReceivable: {
        type: Number,
        default: 0,
    },

    openInvoiceCount: {
        type: Number,
        default: 0,
    },

    monthVatTotal: {
        type: Number,
        default: 0,
    },

    monthGrossProfit: {
        type: Number,
        default: 0,
    },

    monthProfitMargin: {
        type: Number,
        default: 0,
    },

    hideSensitive: {
        type: Boolean,
        default: false,
    },

});

const money = (value) => `₺${Number(value).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
})}`;

const hiddenValue = '••••••';

const cards = [
    {
        label: 'Bugünkü Satış',
        value: () => props.hideSensitive ? hiddenValue : money(props.todayRevenue),
        icon: ShoppingCart,
        accent: 'text-emerald-600',
        bg: 'bg-emerald-50',
    },
    {
        label: 'Bugünkü Tahsilat',
        value: () => props.hideSensitive ? hiddenValue : money(props.todayCollection),
        icon: Banknote,
        accent: 'text-blue-600',
        bg: 'bg-blue-50',
    },
    {
        label: 'Kasalar',
        value: () => props.hideSensitive ? hiddenValue : money(props.totalCash),
        icon: Landmark,
        accent: 'text-violet-600',
        bg: 'bg-violet-50',
    },
    {
        label: 'Cari Alacak',
        value: () => props.hideSensitive ? hiddenValue : money(props.totalReceivable),
        icon: Wallet,
        accent: 'text-red-600',
        bg: 'bg-red-50',
    },
    {
        label: 'Açık Fatura',
        value: () => props.openInvoiceCount,
        icon: FileText,
        accent: 'text-orange-600',
        bg: 'bg-orange-50',
    },
    {
        label: 'Satış Adedi',
        value: () => props.todaySales,
        icon: ReceiptText,
        accent: 'text-indigo-600',
        bg: 'bg-indigo-50',
    },
    {
        label: 'Bu Ay KDV',
        value: () => props.hideSensitive ? hiddenValue : money(props.monthVatTotal),
        icon: Calculator,
        accent: 'text-cyan-600',
        bg: 'bg-cyan-50',
    },
    {
        label: 'Bu Ay Brüt Kâr',
        value: () => props.hideSensitive ? hiddenValue : money(props.monthGrossProfit),
        icon: TrendingUp,
        accent: 'text-emerald-700',
        bg: 'bg-emerald-50',
    },
    {
        label: 'Kâr Oranı',
        value: () => props.hideSensitive ? hiddenValue : `%${Number(props.monthProfitMargin).toLocaleString('tr-TR',{ minimumFractionDigits:2 })}`,
        icon: Percent,
        accent: 'text-slate-900 dark:text-slate-100',
        bg: 'bg-slate-100',
    },
];

</script>

<template>

<div class="grid grid-cols-2 gap-3 md:grid-cols-2 xl:grid-cols-3 xl:gap-6">
    <div
        v-for="card in cards"
        :key="card.label"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6"
    >
        <div class="flex items-center justify-between gap-3">
            <p class="min-w-0 truncate text-xs font-bold text-slate-500 sm:text-sm">
                {{ card.label }}
            </p>

            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl sm:h-11 sm:w-11"
                :class="[card.bg, card.accent]"
            >
                <component
                    :is="card.icon"
                    class="h-4 w-4 sm:h-5 sm:w-5"
                />
            </div>
        </div>

        <h2
            class="mt-4 truncate text-xl font-black sm:text-3xl"
            :class="card.accent"
        >
            {{ card.value() }}
        </h2>
    </div>
</div>

</template>
