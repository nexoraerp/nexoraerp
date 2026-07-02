<script setup>

const props = defineProps({

    customers: {
        type: Array,
        default: () => [],
    },

    cashAccounts: {
        type: Array,
        default: () => [],
    },

    form: {
        type: Object,
        required: true,
    },

    customerSummary: {
        type: Object,
        required: true,
    },

});

</script>

<template>

<div class="w-96 space-y-6">

    <!-- Cari Seçimi -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            👤 Cari
        </h2>

        <select
            v-model="form.customer_id"
            class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
        >

            <option value="">
                Cari Seçiniz
            </option>

            <option
                v-for="customer in customers"
                :key="customer.value"
                :value="customer.value"
            >
                {{ customer.label }}
            </option>

        </select>

    </div>

    <!-- Kasa Seçimi -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            💰 Kasa
        </h2>

        <select
            v-model="form.cash_account_id"
            class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
        >

            <option value="">
                Kasa Seçiniz
            </option>

            <option
                v-for="cash in cashAccounts"
                :key="cash.value"
                :value="cash.value"
            >
                {{ cash.label }}
            </option>

        </select>

    </div>

    <!-- Cari Özeti -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            📊 Cari Özeti
        </h2>

        <div class="space-y-5">

            <div class="flex justify-between">

                <span class="text-slate-500">
                    Açık Borç
                </span>

                <strong class="text-slate-800">
                    ₺{{ Number(customerSummary.open_balance).toLocaleString('tr-TR',{
                        minimumFractionDigits:2
                    }) }}
                </strong>

            </div>

            <div class="flex justify-between">

                <span class="text-slate-500">
                    Vadesi Geçmiş
                </span>

                <strong class="text-red-600">
                    ₺{{ Number(customerSummary.overdue_balance).toLocaleString('tr-TR',{
                        minimumFractionDigits:2
                    }) }}
                </strong>

            </div>

            <div class="flex justify-between">

                <span class="text-slate-500">
                    Açık Fatura
                </span>

                <strong>

                    {{ customerSummary.open_invoice_count }}

                </strong>

            </div>

        </div>

    </div>

    <!-- Ödeme Bilgileri -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            💳 Ödeme Bilgileri
        </h2>

        <div class="space-y-5">

            <div>

                <label class="block text-sm font-medium mb-2">
                    Ödeme Tipi
                </label>

                <select
                    v-model="form.payment_method"
                    class="w-full rounded-xl border-slate-300"
                >

                    <option value="Cash">Nakit</option>
                    <option value="Bank">Havale / EFT</option>
                    <option value="Card">Kredi Kartı</option>
                    <option value="Mixed">Karma</option>

                </select>

            </div>

            <div>

                <label class="block text-sm font-medium mb-2">
                    Referans No
                </label>

                <input
                    v-model="form.reference_no"
                    type="text"
                    class="w-full rounded-xl border-slate-300"
                />

            </div>

            <div>

                <label class="block text-sm font-medium mb-2">
                    Açıklama
                </label>

                <textarea
                    v-model="form.notes"
                    rows="4"
                    class="w-full rounded-xl border-slate-300 resize-none"
                />

            </div>

        </div>

    </div>

</div>

</template>