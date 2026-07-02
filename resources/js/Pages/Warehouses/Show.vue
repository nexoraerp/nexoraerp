<script setup>
import { Head, Link } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    warehouse: Object,
});
</script>

<template>

    <Head title="Depo Detayı" />

    <NxLayout>

        <NxPageHeader
            :title="warehouse.name"
            subtitle="Depo Detayları"
        >

            <Link :href="route('warehouses.edit', warehouse.id)">
                <NxButton>
                    Düzenle
                </NxButton>
            </Link>

        </NxPageHeader>

        <NxCard>

            <div class="grid grid-cols-2 gap-6">

                <div>
                    <strong>Depo Kodu</strong>
                    <p>{{ warehouse.code }}</p>
                </div>

                <div>
                    <strong>Depo Adı</strong>
                    <p>{{ warehouse.name }}</p>
                </div>

                <div>
                    <strong>Sorumlu</strong>
                    <p>{{ warehouse.manager || '-' }}</p>
                </div>

                <div>
                    <strong>Telefon</strong>
                    <p>{{ warehouse.phone || '-' }}</p>
                </div>

                <div class="col-span-2">
                    <strong>Adres</strong>
                    <p>{{ warehouse.address || '-' }}</p>
                </div>

                <div>
                    <strong>Durum</strong>

                    <span
                        class="px-3 py-1 rounded-full text-sm"
                        :class="
                            warehouse.is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700'
                        "
                    >
                        {{ warehouse.is_active ? 'Aktif' : 'Pasif' }}
                    </span>

                </div>

            </div>

        </NxCard>

    </NxLayout>

</template>