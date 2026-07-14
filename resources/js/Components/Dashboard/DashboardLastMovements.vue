<script setup>
defineProps({
    movements: {
        type: Array,
        default: () => [],
    },
});

const getTypeLabel = (type) => {
    switch (type) {
        case 'IN':
            return 'Stok Girişi';
        case 'OUT':
            return 'Stok Çıkışı';
        case 'TRANSFER':
            return 'Depo Transferi';
        case 'RETURN':
            return 'İade';
        case 'ADJUSTMENT':
            return 'Stok Düzeltme';
        default:
            return type;
    }
};

const getTypeClass = (type) => {
    switch (type) {
        case 'IN':
        case 'RETURN':
            return 'text-emerald-600';
        case 'OUT':
        case 'TRANSFER':
            return 'text-red-600';
        default:
            return 'text-slate-600';
    }
};

const getQuantityPrefix = (type) => {
    switch (type) {
        case 'IN':
        case 'RETURN':
            return '+';
        case 'OUT':
        case 'TRANSFER':
            return '-';
        default:
            return '';
    }
};
</script>

<template>

    <div class="h-full rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">

        <div class="mb-4 flex items-center justify-between sm:mb-6">

            <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 sm:text-xl">
                Son Hareketler
            </h2>

            <span
                class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-medium"
            >
                {{ movements.length }}
            </span>

        </div>

        <div
            v-if="movements.length"
            class="space-y-3 sm:space-y-5"
        >

            <div
                v-for="movement in movements"
                :key="movement.id"
                class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3 last:border-0 dark:border-slate-800 sm:pb-4"
            >

                <div class="min-w-0">

                    <p class="truncate font-semibold text-slate-800 dark:text-slate-100">
                        {{ movement.product?.name }}
                    </p>

                    <p class="text-xs font-semibold text-slate-500 sm:text-sm">
                        {{ getTypeLabel(movement.type) }}
                    </p>

                </div>

                <span
                    class="font-bold"
                    :class="getTypeClass(movement.type)"
                >
                    {{ getQuantityPrefix(movement.type) }}{{ movement.quantity }}
                </span>

            </div>

        </div>

        <div
            v-else
            class="flex flex-col items-center justify-center py-8 text-center sm:py-12"
        >

            <p class="font-semibold text-slate-700 dark:text-slate-200">
                Henüz stok hareketi bulunmuyor.
            </p>

            <p class="text-sm text-slate-500 mt-2">
                İlk stok girişini yaptığınızda burada görüntülenecek.
            </p>

        </div>

    </div>

</template>
