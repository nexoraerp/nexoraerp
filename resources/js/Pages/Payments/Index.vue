<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';

defineProps({
    payments: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>

    <Head title="Tahsilatlar" />

    <NxLayout>

        <div class="space-y-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-3xl font-bold text-slate-800">
                        💰 Tahsilatlar
                    </h1>

                    <p class="text-slate-500 mt-1">
                        Sisteme girilen tüm tahsilatlar
                    </p>

                </div>

                <Link
                    :href="route('payments.create')"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
                >
                    + Tahsilat Al
                </Link>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                <table class="w-full">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="text-left p-4">No</th>

                            <th class="text-left p-4">Cari</th>

                            <th class="text-left p-4">Tarih</th>

                            <th class="text-right p-4">Tutar</th>

                            <th class="text-center p-4">Yöntem</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr
                            v-for="payment in payments"
                            :key="payment.id"
                            class="border-t hover:bg-slate-50"
                        >

                            <td class="p-4">
                                {{ payment.payment_no }}
                            </td>

                            <td class="p-4">
                                {{ payment.customer?.name }}
                            </td>

                            <td class="p-4">
                                {{ payment.payment_date }}
                            </td>

                            <td class="p-4 text-right font-semibold">
                                ₺{{ Number(payment.amount).toLocaleString('tr-TR', {
                                    minimumFractionDigits: 2
                                }) }}
                            </td>

                            <td class="p-4 text-center">
                                {{ payment.payment_method }}
                            </td>

                        </tr>

                        <tr v-if="payments.length === 0">

                            <td
                                colspan="5"
                                class="p-10 text-center text-slate-500"
                            >

                                Henüz tahsilat bulunmuyor.

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </NxLayout>

</template>