<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSearch from '@/Components/UI/NxSearch.vue';
import NxActionButtons from '@/Components/UI/NxActionButtons.vue';
import NxModal from '@/Components/UI/NxModal.vue';

const props = defineProps({
    sales: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');

const showDeleteModal = ref(false);
const selectedSale = ref(null);

const filteredSales = computed(() => {

    const keyword = search.value.toLowerCase();

    return props.sales.filter(sale =>

        sale.sale_no?.toLowerCase().includes(keyword) ||

        sale.customer?.name?.toLowerCase().includes(keyword) ||

        sale.status?.toLowerCase().includes(keyword)

    );

});

const deleteSale = (sale) => {

    selectedSale.value = sale;

    showDeleteModal.value = true;

};

const destroySale = () => {

    router.delete(route('sales.destroy', selectedSale.value.id), {

        onSuccess: () => {

            showDeleteModal.value = false;

            selectedSale.value = null;

        },

    });

};

const badgeClass = (status) => {

    switch (status) {

        case 'Draft':
            return 'bg-yellow-100 text-yellow-700';

        case 'Completed':
            return 'bg-emerald-100 text-emerald-700';

        case 'Cancelled':
            return 'bg-red-100 text-red-700';

        default:
            return 'bg-slate-100 text-slate-700';

    }

};

const statusText = (status) => {

    switch (status) {

        case 'Draft':
            return 'Taslak';

        case 'Completed':
            return 'Tamamlandı';

        case 'Cancelled':
            return 'İptal';

        default:
            return status;

    }

};
</script>

<template>

<Head title="Satışlar" />

<NxLayout>

    <NxPageHeader
        title="Satış Yönetimi"
        subtitle="Satış belgelerini yönetin."
    >

        <Link :href="route('sales.create')">

            <NxButton>

                + Yeni Satış

            </NxButton>

        </Link>

    </NxPageHeader>

    <div class="mb-6 max-w-md">

        <NxSearch
            v-model="search"
            placeholder="Satış ara..."
        />

    </div>

    <NxCard>

        <table class="w-full">

            <thead class="border-b bg-slate-50">

                <tr>

                    <th class="text-left py-4 px-4">

                        Satış No

                    </th>

                    <th class="text-left">

                        Cari

                    </th>

                    <th class="text-center">

                        Tarih

                    </th>

                    <th class="text-right">

                        Toplam

                    </th>

                    <th class="text-center">

                        Durum

                    </th>

                    <th class="text-right pr-4">

                        İşlemler

                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="sale in filteredSales"
                    :key="sale.id"
                    class="border-b hover:bg-slate-50 transition"
                >

                    <td class="py-4 px-4 font-semibold text-blue-700">

                        {{ sale.sale_no }}

                    </td>

                    <td>

                        {{ sale.customer?.name }}

                    </td>

                    <td class="text-center">

                        {{ sale.sale_date }}

                    </td>

                    <td class="text-right font-bold">

                        ₺{{ Number(sale.grand_total).toLocaleString('tr-TR',{
                            minimumFractionDigits:2
                        }) }}

                    </td>

                    <td class="text-center">

                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                            :class="badgeClass(sale.status)"
                        >

                            {{ statusText(sale.status) }}

                        </span>

                    </td>

                    <td class="text-right pr-4">

                        <NxActionButtons
                            :show-url="route('sales.show', sale.id)"
                            :edit-url="route('sales.edit', sale.id)"
                            @delete="deleteSale(sale)"
                        />

                    </td>

                </tr>

                <tr
                    v-if="filteredSales.length===0"
                >

                    <td
                        colspan="6"
                        class="py-12 text-center text-slate-500"
                    >

                        Henüz satış bulunmuyor.

                    </td>

                </tr>

            </tbody>

        </table>

    </NxCard>

    <NxModal
        :show="showDeleteModal"
        title="Satışı Sil"
        @close="showDeleteModal=false"
        @confirm="destroySale"
    >

        <p>

            Bu satışı silmek istediğinize emin misiniz?

        </p>

    </NxModal>

</NxLayout>

</template>