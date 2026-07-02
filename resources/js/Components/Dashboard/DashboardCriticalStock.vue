<script setup>
defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>

    <div class="bg-white rounded-3xl border shadow-sm p-8 h-full">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-xl font-bold text-slate-800">
                ⚠ Kritik Stoklar
            </h2>

            <span
                class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm font-medium"
            >
                {{ products.length }}
            </span>

        </div>

        <div
            v-if="products.length"
            class="space-y-5"
        >

            <div
                v-for="product in products"
                :key="product.id"
                class="flex items-center justify-between border-b pb-4 last:border-0"
            >

                <div>

                    <p class="font-semibold text-slate-800">
                        {{ product.name }}
                    </p>

                    <p class="text-sm text-slate-500">
                        Minimum Stok :
                        {{ product.min_stock }}
                    </p>

                </div>

                <span
                    class="px-3 py-1 rounded-full bg-red-100 text-red-600 font-semibold"
                >
                    {{ product.current_stock }}
                </span>

            </div>

        </div>

        <div
            v-else
            class="flex flex-col items-center justify-center py-12 text-center"
        >

            <div class="text-5xl mb-4">
                ✅
            </div>

            <p class="font-semibold text-slate-700">
                Kritik stok bulunmuyor.
            </p>

            <p class="text-sm text-slate-500 mt-2">
                Tüm ürünlerin stok seviyesi güvenli görünüyor.
            </p>

        </div>

    </div>

</template>