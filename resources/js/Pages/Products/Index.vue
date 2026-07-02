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
    products: Array,
});

const search = ref('');

const showDeleteModal = ref(false);
const selectedProduct = ref(null);

const filteredProducts = computed(() => {
    const keyword = search.value.toLowerCase();

    return (props.products || []).filter(product =>
        product.code?.toLowerCase().includes(keyword) ||
        product.name?.toLowerCase().includes(keyword) ||
        product.brand?.toLowerCase().includes(keyword) ||
        product.category?.toLowerCase().includes(keyword)
    );
});

const deleteProduct = (product) => {
    selectedProduct.value = product;
    showDeleteModal.value = true;
};

const destroyProduct = () => {
    router.delete(route('products.destroy', selectedProduct.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedProduct.value = null;
        },
    });
};
</script>

<template>

    <Head title="Ürünler" />

    <NxLayout>

        <NxPageHeader
            title="Ürün Yönetimi"
            subtitle="Ürünlerinizi buradan yönetin."
        >
            <Link :href="route('products.create')">
                <NxButton>
                    + Yeni Ürün
                </NxButton>
            </Link>
        </NxPageHeader>

        <div class="mb-6 max-w-md">
            <NxSearch
                v-model="search"
                placeholder="Ürün ara..."
            />
        </div>

        <NxCard>

            <table class="w-full">

                <thead class="border-b">
                    <tr>
                        <th class="text-left py-4">Kod</th>
                        <th class="text-left py-4">Ürün</th>
                        <th class="text-left py-4">Kategori</th>
                        <th class="text-left py-4">Marka</th>
                        <th class="text-center py-4">Stok</th>
                        <th class="text-right py-4">İşlemler</th>
                    </tr>
                </thead>

                <tbody>

                    <tr
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="border-b hover:bg-slate-50"
                    >

                        <td class="py-4">{{ product.code }}</td>

                        <td>{{ product.name }}</td>

                        <td>{{ product.category }}</td>

                        <td>{{ product.brand }}</td>

                        <td
                            class="text-center font-bold"
                            :class="product.current_stock <= product.min_stock
                                ? 'text-red-600'
                                : 'text-green-600'"
                        >
                            {{ product.current_stock }}
                        </td>

                        <td class="text-right">

                            <NxActionButtons
                                :edit-url="route('products.edit', product.id)"
                                :show-url="route('products.show', product.id)"
                                @delete="deleteProduct(product)"
                            />

                        </td>

                    </tr>

                    <tr v-if="filteredProducts.length === 0">

                        <td
                            colspan="6"
                            class="py-10 text-center text-slate-500"
                        >
                            Henüz ürün bulunmuyor.
                        </td>

                    </tr>

                </tbody>

            </table>

        </NxCard>

        <NxModal
            :show="showDeleteModal"
            title="Ürün Sil"
            @close="showDeleteModal = false"
            @confirm="destroyProduct"
        >

            <p>
                <strong>{{ selectedProduct?.name }}</strong>
                adlı ürünü silmek istediğinize emin misiniz?
            </p>

            <p class="mt-3 text-sm text-red-500">
                Bu işlem geri alınamaz.
            </p>

        </NxModal>

    </NxLayout>

</template>