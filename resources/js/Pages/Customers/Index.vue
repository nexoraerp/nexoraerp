<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSearch from '@/Components/UI/NxSearch.vue';
import NxActionButtons from '@/Components/UI/NxActionButtons.vue';

const props = defineProps({
    customers: Array,
});

const search = ref('');

const filteredCustomers = computed(() => {
    const keyword = search.value.toLowerCase();

    return (props.customers || []).filter(customer =>
        customer.code?.toLowerCase().includes(keyword) ||
        customer.name?.toLowerCase().includes(keyword) ||
        customer.company?.toLowerCase().includes(keyword) ||
        customer.phone?.toLowerCase().includes(keyword)
    );
});

const deleteCustomer = (id) => {
    if (!confirm('Bu cariyi silmek istediğinize emin misiniz?')) {
        return;
    }

    router.delete(route('customers.destroy', id));
};
</script>

<template>

    <Head title="Cariler" />

    <NxLayout>

        <NxPageHeader
            title="Cari Yönetimi"
            subtitle="Müşteri ve tedarikçilerinizi buradan yönetin."
        >
            <Link :href="route('customers.create')">
                <NxButton>
                    + Yeni Cari
                </NxButton>
            </Link>
        </NxPageHeader>

        <div class="mb-6 max-w-md">
            <NxSearch
                v-model="search"
                placeholder="Cari ara..."
            />
        </div>

        <NxCard>

            <table class="w-full">

                <thead class="border-b">

                    <tr>

                        <th class="text-left py-4">Kod</th>
                        <th class="text-left py-4">Cari Adı</th>
                        <th class="text-left py-4">Firma</th>
                        <th class="text-left py-4">Telefon</th>
                        <th class="text-right py-4">İşlemler</th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="customer in filteredCustomers"
                        :key="customer.id"
                        class="border-b hover:bg-slate-50"
                    >

                        <td class="py-4">{{ customer.code }}</td>
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.company }}</td>
                        <td>{{ customer.phone }}</td>

                        <td class="text-right">

                            <NxActionButtons
                                :edit-url="route('customers.edit', customer.id)"
                                :show-url="route('customers.show', customer.id)"
                                @delete="deleteCustomer(customer.id)"
                            />

                        </td>

                    </tr>

                    <tr v-if="filteredCustomers.length === 0">

                        <td
                            colspan="5"
                            class="py-10 text-center text-slate-500"
                        >
                            Henüz kayıt bulunmuyor.
                        </td>

                    </tr>

                </tbody>

            </table>

        </NxCard>

    </NxLayout>

</template>