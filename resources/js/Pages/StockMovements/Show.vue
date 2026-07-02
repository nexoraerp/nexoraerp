<script setup>
import { Head, Link } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';

const props = defineProps({
    movement: Object,
});

const badgeClass = (type) => {
    switch (type) {
        case 'IN':
            return 'bg-green-100 text-green-700';

        case 'OUT':
            return 'bg-red-100 text-red-700';

        case 'TRANSFER':
            return 'bg-blue-100 text-blue-700';

        case 'RETURN':
            return 'bg-yellow-100 text-yellow-700';

        case 'ADJUSTMENT':
            return 'bg-purple-100 text-purple-700';

        default:
            return 'bg-slate-100 text-slate-700';
    }
};
</script>

<template>

<Head title="Stok Hareketi" />

<NxLayout>

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold">
                📦 Stok Hareketi
            </h1>

            <p class="text-slate-500 mt-2">
                Stok hareketi detayları
            </p>

        </div>

        <Link :href="route('stock-movements.index')">

            <NxButton>

                ← Geri Dön

            </NxButton>

        </Link>

    </div>

    <NxCard>

        <div class="grid grid-cols-2 gap-8">

            <div>

                <p class="text-slate-500">Ürün</p>

                <h3 class="text-xl font-bold">

                    {{ movement.product?.name }}

                </h3>

            </div>

            <div>

                <p class="text-slate-500">Depo</p>

                <h3 class="text-xl font-bold">

                    {{ movement.warehouse?.name }}

                </h3>

            </div>

            <div>

                <p class="text-slate-500">Hareket Tipi</p>

                <span
                    class="inline-block mt-2 px-4 py-2 rounded-full font-semibold"
                    :class="badgeClass(movement.type)"
                >
                    {{ movement.type }}
                </span>

            </div>

            <div>

                <p class="text-slate-500">Miktar</p>

                <h3 class="text-xl font-bold">

                    {{ movement.quantity }}

                </h3>

            </div>

            <div>

                <p class="text-slate-500">Birim Maliyet</p>

                <h3 class="text-xl font-bold">

                    ₺{{ Number(movement.unit_cost ?? 0).toLocaleString('tr-TR',{
                        minimumFractionDigits:2,
                        maximumFractionDigits:2
                    }) }}

                </h3>

            </div>

            <div>

                <p class="text-slate-500">Oluşturan</p>

                <h3 class="text-xl font-bold">

                    {{ movement.user?.name }}

                </h3>

            </div>

        </div>

        <div class="mt-8">

            <p class="text-slate-500">
                Açıklama
            </p>

            <div class="bg-slate-50 rounded-xl p-5 mt-2">

                {{ movement.description || '-' }}

            </div>

        </div>

    </NxCard>

</div>

</NxLayout>

</template>