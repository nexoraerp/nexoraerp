<script setup>

defineProps({

    movements: {

        type: Array,

        default: () => [],

    },

});

</script>

<template>

<div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

    <div class="p-6 border-b">

        <h2 class="text-xl font-bold">
            Cari Hareketleri
        </h2>

    </div>

    <table class="w-full">

        <thead class="bg-slate-50">

            <tr>

                <th class="text-left p-4">
                    Tarih
                </th>

                <th class="text-left p-4">
                    İşlem
                </th>

                <th class="text-left p-4">
                    Belge No
                </th>

                <th class="text-left p-4">
                    Vade
                </th>

                <th class="text-right p-4">
                    Borç
                </th>

                <th class="text-right p-4">
                    Alacak
                </th>

            </tr>

        </thead>

        <tbody>

            <tr
                v-for="movement in movements"
                :key="movement.id"
                class="border-t hover:bg-slate-50 transition"
            >

                <td class="p-4">

                    {{ movement.movement_date }}

                </td>

                <td class="p-4">

                    <span
                        v-if="movement.type === 'SALE'"
                        class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold"
                    >
                        🧾 Satış
                    </span>

                    <span
                        v-else-if="movement.type === 'PAYMENT'"
                        class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold"
                    >
                        💰 Tahsilat
                    </span>

                    <span
                        v-else
                        class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"
                    >
                        {{ movement.type }}
                    </span>

                </td>

                <td class="p-4">

                    <span v-if="movement.reference?.sale_no">

                        {{ movement.reference.sale_no }}

                    </span>

                    <span v-else-if="movement.reference?.payment_no">

                        {{ movement.reference.payment_no }}

                    </span>

                    <span v-else>

                        -

                    </span>

                </td>

                <td class="p-4">

                    {{ movement.reference?.due_date ?? '-' }}

                </td>

                <td class="p-4 text-right font-semibold text-red-600">

                    {{ Number(movement.debit).toLocaleString('tr-TR',{
                        minimumFractionDigits:2
                    }) }}

                </td>

                <td class="p-4 text-right font-semibold text-green-600">

                    {{ Number(movement.credit).toLocaleString('tr-TR',{
                        minimumFractionDigits:2
                    }) }}

                </td>

            </tr>

            <tr v-if="movements.length === 0">

                <td
                    colspan="6"
                    class="p-10 text-center text-slate-500"
                >

                    Henüz hareket bulunmuyor.

                </td>

            </tr>

        </tbody>

    </table>

</div>

</template>