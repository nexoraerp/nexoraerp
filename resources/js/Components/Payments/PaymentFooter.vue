<script setup>

const props = defineProps({

    selectedSales: {
        type: Array,
        default: () => [],
    },

    items: {
        type: Array,
        default: () => [],
    },

});

const emit = defineEmits([
    'save',
]);

const total = () => {

    return props.items.reduce((sum, item) => {

        return sum + Number(item.amount);

    }, 0);

};

</script>

<template>

<div
    class="sticky bottom-0 bg-white border border-slate-200 rounded-2xl shadow-xl p-6"
>

    <div class="flex items-center justify-between">

        <div>

            <p class="text-slate-500 text-sm">

                Seçilen Fatura

            </p>

            <h2 class="text-2xl font-bold mt-1">

                {{ props.selectedSales.length }}

            </h2>

        </div>

        <div class="text-center">

            <p class="text-slate-500 text-sm">

                Toplam Tahsilat

            </p>

            <h2 class="text-4xl font-extrabold text-blue-600 mt-1">

                ₺{{ total().toLocaleString('tr-TR', {

                    minimumFractionDigits: 2

                }) }}

            </h2>

        </div>

        <button
            @click="emit('save')"
            :disabled="props.selectedSales.length === 0"
            class="px-8 py-4 rounded-xl text-white font-bold transition"
            :class="props.selectedSales.length === 0
                ? 'bg-slate-400 cursor-not-allowed'
                : 'bg-blue-600 hover:bg-blue-700'"
        >

            💰 Tahsilatı Kaydet

        </button>

    </div>

</div>

</template>