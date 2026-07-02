<script setup>
import { Head, Link } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

import CustomerSummaryCards from '@/Components/Customers/CustomerSummaryCards.vue';
import CustomerMovementTable from '@/Components/Customers/CustomerMovementTable.vue';

defineProps({

    customer: Object,

    summary: Object,

    movements: {
        type: Array,
        default: () => [],
    },

});
</script>

<template>

<Head title="Cari Detayı" />

<NxLayout>

    <NxPageHeader
        title="Cari Detayı"
        subtitle="Cari bilgilerini görüntüleyin."
    >
        <Link :href="route('customers.edit', customer.id)">
            <NxButton>
                Düzenle
            </NxButton>
        </Link>
    </NxPageHeader>

    <CustomerSummaryCards
        :summary="summary"
    />

    <CustomerMovementTable
        class="mt-6"
        :movements="movements"
    />

    <NxCard class="mt-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <p class="text-sm text-slate-500">Cari Kodu</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.code }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Cari Adı</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Firma</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.company || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Telefon</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.phone || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">E-Posta</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.email || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Vergi No</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.tax_number || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Vergi Dairesi</p>
                <p class="text-lg font-semibold mt-1">
                    {{ customer.tax_office || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Bakiye</p>
                <p class="text-lg font-bold text-red-600 mt-1">
                    ₺ {{ Number(summary.balance).toLocaleString('tr-TR', {
                        minimumFractionDigits: 2
                    }) }}
                </p>
            </div>

            <div class="md:col-span-2">

                <p class="text-sm text-slate-500">
                    Adres
                </p>

                <p class="text-lg font-semibold mt-1 whitespace-pre-line">
                    {{ customer.address || '-' }}
                </p>

            </div>

        </div>

    </NxCard>

</NxLayout>

</template>