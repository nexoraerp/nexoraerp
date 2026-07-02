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
    class="rounded-2xl border-2 cursor-pointer transition-all duration-300 p-6 hover:shadow-lg"
    :class="props.selected
        ? 'border-blue-600 bg-blue-50 shadow-md'
        : 'border-slate-200 bg-white hover:border-blue-300'"
>

    <div class="flex items-start justify-between">

        <div>

            <h3 class="text-lg font-bold text-slate-800">

                {{ props.sale.sale_no }}

            </h3>

            <p class="text-slate-500 mt-1">

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

    <div class="grid grid-cols-3 gap-6 mt-8">

        <div>

            <p class="text-slate-500 text-sm">
                Toplam
            </p>

            <h4 class="font-bold text-xl mt-2">
                ₺{{ Number(props.sale.grand_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

        <div>

            <p class="text-slate-500 text-sm">
                Tahsil Edilen
            </p>

            <h4 class="font-bold text-xl mt-2 text-emerald-600">
                ₺{{ Number(props.sale.paid_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

        <div>

            <p class="text-slate-500 text-sm">
                Kalan
            </p>

            <h4 class="font-bold text-xl mt-2 text-red-600">
                ₺{{ Number(props.sale.remaining_total).toLocaleString('tr-TR') }}
            </h4>

        </div>

    </div>

    <div
        class="mt-8 pt-5 border-t flex items-center justify-between"
    >

        <span class="text-slate-500">
            Vade
        </span>

        <strong>

            {{ props.sale.due_date ?? '-' }}

        </strong>

    </div>

    <div
        v-if="props.selected"
        class="mt-6 border-t pt-6"
        @click.stop
    >

        <label
            class="block text-sm font-medium text-slate-600 mb-2"
        >
            Tahsil Edilecek Tutar
        </label>

        <input
            type="number"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            :value="props.amount"
            @input="updateAmount"
            min="0"
            :max="props.sale.remaining_total"
        />

        <p class="text-xs text-slate-500 mt-2">

            Maksimum:
            ₺{{ Number(props.sale.remaining_total).toLocaleString('tr-TR') }}

        </p>

    </div>

</div>

</template>