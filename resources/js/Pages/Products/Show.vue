<script setup>
import { Head, Link } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    product: Object,
});
</script>

<template>

<Head title="Ürün Detayı" />

<NxLayout>

    <NxPageHeader
        title="Ürün Detayı"
        subtitle="Ürün bilgilerini görüntüleyin."
    >

        <Link :href="route('products.edit', product.id)">
            <NxButton>
                Düzenle
            </NxButton>
        </Link>

    </NxPageHeader>

    <NxCard>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <p class="text-sm text-slate-500">Ürün Kodu</p>
                <p class="text-lg font-semibold">{{ product.code }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Barkod</p>
                <p class="text-lg font-semibold">
                    {{ product.barcode || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Ürün Adı</p>
                <p class="text-lg font-semibold">
                    {{ product.name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Kategori</p>
                <p class="text-lg font-semibold">
                    {{ product.category || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Marka</p>
                <p class="text-lg font-semibold">
                    {{ product.brand || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Model</p>
                <p class="text-lg font-semibold">
                    {{ product.model || '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Alış Fiyatı</p>
                <p class="text-lg font-semibold">
                    ₺ {{ product.purchase_price }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Satış Fiyatı</p>
                <p class="text-lg font-semibold">
                    ₺ {{ product.sale_price }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Stok</p>
                <p class="text-lg font-semibold">
                    {{ product.stock }} {{ product.unit }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Minimum Stok</p>
                <p class="text-lg font-semibold">
                    {{ product.min_stock }}
                </p>
            </div>

        </div>

    </NxCard>

</NxLayout>

</template>