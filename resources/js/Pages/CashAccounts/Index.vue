<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSearch from '@/Components/UI/NxSearch.vue';
import NxActionButtons from '@/Components/UI/NxActionButtons.vue';

const props = defineProps({

    cashAccounts: {
        type: Array,
        default: () => [],
    },

});

const search = ref('');

const filteredCashAccounts = computed(() => {

    const keyword = search.value.toLowerCase();

    return props.cashAccounts.filter(account =>

        account.code?.toLowerCase().includes(keyword) ||

        account.name?.toLowerCase().includes(keyword) ||

        account.currency?.toLowerCase().includes(keyword)

    );

});

const deleteCashAccount = (id) => {

    if (!confirm('Bu kasayı silmek istediğinize emin misiniz?')) {

        return;

    }

    router.delete(route('cash-accounts.destroy', id));

};
</script>

<template>

<Head title="Kasalar" />

<NxLayout>

    <NxPageHeader
        title="Kasa Yönetimi"
        subtitle="Kasalarınızı buradan yönetin."
    >

        <Link :href="route('cash-accounts.create')">

            <NxButton>

                + Yeni Kasa

            </NxButton>

        </Link>

    </NxPageHeader>

    <div class="mb-6 max-w-md">

        <NxSearch
            v-model="search"
            placeholder="Kasa ara..."
        />

    </div>

    <NxCard>

        <table class="w-full">

            <thead class="border-b">

                <tr>

                    <th class="text-left py-4">
                        Kod
                    </th>

                    <th class="text-left py-4">
                        Kasa
                    </th>

                    <th class="text-left py-4">
                        Para Birimi
                    </th>

                    <th class="text-right py-4">
                        İşlemler
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="account in filteredCashAccounts"
                    :key="account.id"
                    class="border-b hover:bg-slate-50"
                >

                    <td class="py-4">

                        {{ account.code }}

                    </td>

                    <td>

                        {{ account.name }}

                    </td>

                    <td>

                        {{ account.currency }}

                    </td>

                    <td class="text-right">

                        <NxActionButtons

                            :edit-url="route('cash-accounts.edit', account.id)"

                            :show-url="route('cash-accounts.show', account.id)"

                            @delete="deleteCashAccount(account.id)"

                        />

                    </td>

                </tr>

                <tr
                    v-if="filteredCashAccounts.length === 0"
                >

                    <td
                        colspan="4"
                        class="py-10 text-center text-slate-500"
                    >

                        Henüz kasa bulunmuyor.

                    </td>

                </tr>

            </tbody>

        </table>

    </NxCard>

</NxLayout>

</template>