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
    warehouses: Array,
});

const search = ref('');

const showDeleteModal = ref(false);
const selectedWarehouse = ref(null);

const filteredWarehouses = computed(() => {
    const keyword = search.value.toLowerCase();

    return (props.warehouses || []).filter(warehouse =>
        warehouse.code?.toLowerCase().includes(keyword) ||
        warehouse.name?.toLowerCase().includes(keyword) ||
        warehouse.manager?.toLowerCase().includes(keyword) ||
        warehouse.phone?.toLowerCase().includes(keyword)
    );
});

const deleteWarehouse = (warehouse) => {
    selectedWarehouse.value = warehouse;
    showDeleteModal.value = true;
};

const destroyWarehouse = () => {
    router.delete(route('warehouses.destroy', selectedWarehouse.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedWarehouse.value = null;
        },
    });
};
</script>

<template>

    <Head title="Depolar" />

    <NxLayout>

        <NxPageHeader
            title="Depo Yönetimi"
            subtitle="Depolarınızı buradan yönetin."
        >
            <Link :href="route('warehouses.create')">
                <NxButton>
                    + Yeni Depo
                </NxButton>
            </Link>
        </NxPageHeader>

        <div class="mb-6 max-w-md">
            <NxSearch
                v-model="search"
                placeholder="Depo ara..."
            />
        </div>

        <NxCard>

            <table class="w-full">

                <thead class="border-b">

                    <tr>

                        <th class="text-left py-4">Kod</th>
                        <th class="text-left py-4">Depo</th>
                        <th class="text-left py-4">Sorumlu</th>
                        <th class="text-left py-4">Telefon</th>
                        <th class="text-right py-4">İşlemler</th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="warehouse in filteredWarehouses"
                        :key="warehouse.id"
                        class="border-b hover:bg-slate-50"
                    >

                        <td class="py-4">{{ warehouse.code }}</td>
                        <td>{{ warehouse.name }}</td>
                        <td>{{ warehouse.manager }}</td>
                        <td>{{ warehouse.phone }}</td>

                        <td class="text-right">

                            <NxActionButtons
                                :edit-url="route('warehouses.edit', warehouse.id)"
                                :show-url="route('warehouses.show', warehouse.id)"
                                @delete="deleteWarehouse(warehouse)"
                            />

                        </td>

                    </tr>

                    <tr v-if="filteredWarehouses.length === 0">

                        <td
                            colspan="5"
                            class="py-10 text-center text-slate-500"
                        >
                            Henüz depo bulunmuyor.
                        </td>

                    </tr>

                </tbody>

            </table>

        </NxCard>

        <NxModal
            :show="showDeleteModal"
            title="Depo Sil"
            @close="showDeleteModal = false"
            @confirm="destroyWarehouse"
        >

            <p>

                <strong>{{ selectedWarehouse?.name }}</strong>

                adlı depoyu silmek istediğinize emin misiniz?

            </p>

            <p class="mt-3 text-sm text-red-500">

                Bu işlem geri alınamaz.

            </p>

        </NxModal>

    </NxLayout>

</template>