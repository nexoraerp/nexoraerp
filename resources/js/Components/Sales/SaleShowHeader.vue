<script setup>
import { Link } from '@inertiajs/vue3';

import NxButton from '@/Components/UI/NxButton.vue';
import SaleStatusBadge from './SaleStatusBadge.vue';

const props = defineProps({

    sale: Object,

});
</script>

<template>

<div class="flex items-start justify-between mb-8">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            {{ sale.sale_no }}

        </h1>

        <div class="mt-3">

            <SaleStatusBadge
                :status="sale.status"
            />

        </div>

    </div>

    <div class="flex gap-3">

        <Link
            :href="route('sales.edit', sale.id)"
            v-if="sale.status !== 'Cancelled'"
        >
            <NxButton>

                ✏️ Düzenle

            </NxButton>
        </Link>

        <NxButton>

            🖨 Yazdır

        </NxButton>

    </div>

</div>

</template>