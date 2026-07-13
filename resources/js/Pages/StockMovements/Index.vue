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
    movements: Object,
});

const search = ref('');

const showDeleteModal = ref(false);
const selectedMovement = ref(null);

const rows = computed(() => props.movements.data ?? []);

const filteredMovements = computed(() => {

    const keyword = search.value.toLowerCase();

    return rows.value.filter(movement =>

        movement.product?.name?.toLowerCase().includes(keyword) ||

        movement.warehouse?.name?.toLowerCase().includes(keyword) ||

        movement.type?.toLowerCase().includes(keyword)

    );

});

const deleteMovement = (movement) => {

    selectedMovement.value = movement;

    showDeleteModal.value = true;

};

const destroyMovement = () => {

    router.delete(
        route('stock-movements.destroy', selectedMovement.value.id),
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedMovement.value = null;
            },
        }
    );

};

const typeColor = (type) => {

    switch (type) {

        case 'IN':
            return 'bg-green-100 text-green-700';

        case 'OUT':
            return 'bg-red-100 text-red-700';

        case 'TRANSFER':
            return 'bg-blue-100 text-blue-700';

        case 'RETURN':
            return 'bg-yellow-100 text-yellow-700';

        default:
            return 'bg-slate-100 text-slate-700';

    }

};
</script>

<template>

<Head title="Stok Hareketleri" />

<NxLayout>

<NxPageHeader
    title="Stok Hareketleri"
    subtitle="Tüm stok giriş ve çıkışlarını görüntüleyin."
>

<div class="flex flex-wrap gap-3">
<Link :href="route('stock-movements.transfer')">
    <NxButton variant="secondary">
        Depolar Arası Transfer
    </NxButton>
</Link>

<Link :href="route('stock-movements.create')">

    <NxButton>

        + Yeni Hareket

    </NxButton>

</Link>
</div>

</NxPageHeader>

<div class="mb-6 max-w-md">

<NxSearch
    v-model="search"
    placeholder="Ürün veya depo ara..."
/>

</div>

<NxCard>

<table class="w-full">

<thead class="border-b">

<tr>

<th class="py-4 text-left">Tarih</th>

<th class="text-left">Ürün</th>

<th class="text-left">Depo</th>

<th class="text-center">Hareket</th>

<th class="text-right">Miktar</th>

<th class="text-right">İşlemler</th>

</tr>

</thead>

<tbody>

<tr
v-for="movement in filteredMovements"
:key="movement.id"
class="border-b hover:bg-slate-50"
>

<td class="py-4">

{{ movement.created_at?.substring(0,10) }}

</td>

<td>

{{ movement.product?.name }}

</td>

<td>

{{ movement.warehouse?.name }}

</td>

<td class="text-center">

<span
:class="[
'px-3 py-1 rounded-full text-xs font-semibold',
typeColor(movement.type)
]"
>

{{ movement.type }}

</span>

</td>

<td class="text-right font-semibold">

{{ movement.quantity }}

</td>

<td class="text-right">

<NxActionButtons
:edit-url="route('stock-movements.edit', movement.id)"
:show-url="route('stock-movements.show', movement.id)"
@delete="deleteMovement(movement)"
/>

</td>

</tr>

<tr
v-if="filteredMovements.length===0"
>

<td
colspan="6"
class="py-10 text-center text-slate-500"
>

Henüz stok hareketi bulunmuyor.

</td>

</tr>

</tbody>

</table>

</NxCard>

<NxModal
:show="showDeleteModal"
title="Stok Hareketini Sil"
@close="showDeleteModal=false"
@confirm="destroyMovement"
>

<p>

Bu stok hareketini silmek istediğinize emin misiniz?

</p>

</NxModal>

</NxLayout>

</template>
