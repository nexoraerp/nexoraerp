<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import NxLayout from '@/Layouts/NxLayout.vue';

const props = defineProps({
    payments: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');

const filteredPayments = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('tr-TR');

    if (!term) {
        return props.payments;
    }

    return props.payments.filter((payment) => [
        payment.payment_no,
        payment.customer?.name,
        payment.payment_date,
        payment.payment_method,
        payment.amount,
    ].some((value) => String(value ?? '').toLocaleLowerCase('tr-TR').includes(term)));
});
</script>

<template>

    <Head title="Tahsilatlar" />

    <NxLayout>

        <div class="space-y-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">
                        Tahsilatlar
                    </h1>

                    <p class="text-slate-500 mt-1 dark:text-slate-400">
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

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden dark:border-slate-800 dark:bg-slate-950">
                <div class="flex flex-col gap-3 border-b border-slate-100 bg-white p-4 md:flex-row md:items-center md:justify-between dark:border-slate-800 dark:bg-slate-950">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">Tahsilat Listesi</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ filteredPayments.length }} kayıt görüntüleniyor
                        </p>
                    </div>

                    <div class="relative w-full md:max-w-sm">
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Tahsilat no, cari, tarih veya yöntem ara"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:bg-slate-900 dark:focus:ring-blue-900/40"
                        >
                    </div>
                </div>

                <table class="w-full">

                    <thead class="bg-slate-50 dark:bg-slate-900">

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
                            v-for="payment in filteredPayments"
                            :key="payment.id"
                            class="border-t border-slate-100 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-100 dark:hover:bg-slate-900"
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

                        <tr v-if="filteredPayments.length === 0">

                            <td
                                colspan="5"
                                class="p-10 text-center text-slate-500"
                            >

                                {{ search ? 'Aramanıza uygun tahsilat bulunamadı.' : 'Henüz tahsilat bulunmuyor.' }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </NxLayout>

</template>
