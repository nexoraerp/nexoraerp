<script setup>

const props = defineProps({

    sale: {
        type: Object,
        required: true,
    },

    selected: {
        type: Boolean,
        default: false,
    },

    amount: {
        type: Number,
        default: 0,
    },

});

const emit = defineEmits([
    'toggle',
    'update:amount',
]);

const toggle = () => {
    emit('toggle', props.sale);
};

const updateAmount = (event) => {
    emit('update:amount', Number(event.target.value));
};

</script>

<template>

<div
    @click="toggle"
    class="cursor-pointer rounded-2xl border-2 p-4 transition-all duration-300 hover:shadow-lg sm:p-6"
    :class="props.selected
        ? 'border-blue-600 bg-blue-50 shadow-md dark:bg-blue-950/30 dark:border-blue-500'
        : 'border-slate-200 bg-white hover:border-blue-300 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-blue-500'"
>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">

                {{ props.sale.sale_no }}

            </h3>

            <p class="text-slate-500 mt-1 dark:text-slate-400">

                {{ props.sale.sale_date }}

            </p>

        </div>

        <div>

            <span
                v-if="props.selected"
                class="px-3 py-1 rounded-full bg-blue-600 text-white text-xs font-bold"
            >
                ✓ Seçildi
            </span>

        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:mt-8 sm:grid-cols-3 sm:gap-6">

        <div>

            <p class="text-slate-500 text-sm dark:text-slate-400">
                Toplam
            </p>

            <h4 class="font-bold text-xl mt-2 text-slate-900 dark:text-slate-100">
                ₺{{ Number(props.sale.grand_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

        <div>

            <p class="text-slate-500 text-sm dark:text-slate-400">
                Tahsil Edilen
            </p>

            <h4 class="font-bold text-xl mt-2 text-emerald-600">
                ₺{{ Number(props.sale.paid_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

        <div>

            <p class="text-slate-500 text-sm dark:text-slate-400">
                Kalan
            </p>

            <h4 class="font-bold text-xl mt-2 text-red-600">
                ₺{{ Number(props.sale.remaining_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

    </div>

    <div
        class="mt-6 flex items-center justify-between border-t border-slate-200 pt-5 dark:border-slate-800 sm:mt-8"
    >

        <span class="text-slate-500 dark:text-slate-400">
            Vade
        </span>

        <strong class="text-slate-900 dark:text-slate-100">

            {{ props.sale.due_date ?? '-' }}

        </strong>

    </div>

    <div
        v-if="props.selected"
        class="mt-6 border-t border-slate-200 pt-6 dark:border-slate-800"
        @click.stop
    >

        <label
            class="block text-sm font-medium text-slate-600 mb-2 dark:text-slate-300"
        >
            Tahsil Edilecek Tutar
        </label>

        <input
            type="number"
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-blue-900/40"
            :value="props.amount"
            @input="updateAmount"
            min="0"
            :max="props.sale.remaining_total"
        />

        <p class="text-xs text-slate-500 mt-2 dark:text-slate-400">

            Maksimum:
            ₺{{ Number(props.sale.remaining_total).toLocaleString('tr-TR') }}

        </p>

    </div>

</div>

</template>
