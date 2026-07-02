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

    <div class="bg-white rounded-3xl border shadow-sm p-8 h-full">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-xl font-bold text-slate-800">
                🕒 Son Hareketler
            </h2>

            <span
                class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-medium"
            >
                {{ movements.length }}
            </span>

        </div>

        <div
            v-if="movements.length"
            class="space-y-5"
        >

            <div
                v-for="movement in movements"
                :key="movement.id"
                class="flex items-center justify-between border-b pb-4 last:border-0"
            >

                <div>

                    <p class="font-semibold text-slate-800">
                        {{ movement.product?.name }}
                    </p>

                    <p class="text-sm text-slate-500">
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
            class="flex flex-col items-center justify-center py-12 text-center"
        >

            <div class="text-5xl mb-4">
                📦
            </div>

            <p class="font-semibold text-slate-700">
                Henüz stok hareketi bulunmuyor.
            </p>

            <p class="text-sm text-slate-500 mt-2">
                İlk stok girişini yaptığınızda burada görüntülenecek.
            </p>

        </div>

    </div>

</template>