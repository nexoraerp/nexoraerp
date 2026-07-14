<script setup>
defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>

    <div class="h-full rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">

        <div class="mb-4 flex items-center justify-between sm:mb-6">

            <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 sm:text-xl">
                Kritik Stoklar
            </h2>

            <span
                class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm font-medium"
            >
                {{ products.length }}
            </span>

        </div>

        <div
            v-if="products.length"
            class="space-y-3 sm:space-y-5"
        >

            <div
                v-for="product in products"
                :key="product.id"
                class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3 last:border-0 dark:border-slate-800 sm:pb-4"
            >

                <div class="min-w-0">

                    <p class="truncate font-semibold text-slate-800 dark:text-slate-100">
                        {{ product.name }}
                    </p>

                    <p class="text-xs font-semibold text-slate-500 sm:text-sm">
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
            class="flex flex-col items-center justify-center py-8 text-center sm:py-12"
        >

            <p class="font-semibold text-slate-700 dark:text-slate-200">
                Kritik stok bulunmuyor.
            </p>

            <p class="text-sm text-slate-500 mt-2">
                Tüm ürünlerin stok seviyesi güvenli görünüyor.
            </p>

        </div>

    </div>

</template>
