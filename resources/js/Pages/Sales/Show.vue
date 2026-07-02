<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';

const props = defineProps({
    sale: Object,
});

const badgeClass = (status) => {
    switch (status) {
        case 'Completed':
            return 'bg-emerald-100 text-emerald-700';
        case 'Draft':
            return 'bg-yellow-100 text-yellow-700';
        case 'Cancelled':
            return 'bg-red-100 text-red-700';
        default:
            return 'bg-slate-100 text-slate-700';
    }
};

const statusText = (status) => {
    switch (status) {
        case 'Completed':
            return 'Tamamlandı';
        case 'Draft':
            return 'Taslak';
        case 'Cancelled':
            return 'İptal';
        default:
            return status;
    }
};

// Boşluk kontrolleri eklenen yeni fonksiyon:
const cancelSale = () => {
    const reason = prompt('İptal sebebini yazınız');

    if (!reason || !reason.trim()) {
        return;
    }

    router.patch(route('sales.cancel', props.sale.id), {
        cancel_reason: reason.trim(),
    });
};
</script>

<template>
    <Head :title="sale.sale_no" />

    <NxLayout>
        <div class="space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold">
                        🧾 Satış Detayı
                    </h1>
                    <p class="text-slate-500 mt-2">
                        {{ sale.sale_no }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <Link :href="route('sales.index')">
                        <NxButton type="button">
                            ← Liste
                        </NxButton>
                    </Link>

                    <Link
                        v-if="sale.status === 'Completed'"
                        :href="route('sales.edit', sale.id)"
                    >
                        <NxButton>
                            ✏ Düzenle
                        </NxButton>
                    </Link>

                    <NxButton
                        v-if="sale.status === 'Completed'"
                        class="bg-red-600 hover:bg-red-700 text-white"
                        @click="cancelSale"
                    >
                        🚫 Satışı İptal Et
                    </NxButton>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-8">
                    <NxCard>
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <p class="text-sm text-slate-500">
                                    Cari
                                </p>
                                <h3 class="text-xl font-bold mt-1">
                                    {{ sale.customer?.name }}
                                </h3>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">
                                    Satış Tarihi
                                </p>
                                <h3 class="text-xl font-bold mt-1">
                                    {{ sale.sale_date }}
                                </h3>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">
                                    Durum
                                </p>
                                <span
                                    class="inline-flex mt-2 px-4 py-2 rounded-full font-semibold"
                                    :class="badgeClass(sale.status)"
                                >
                                    {{ statusText(sale.status) }}
                                </span>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">
                                    Oluşturan
                                </p>
                                <h3 class="text-xl font-bold mt-1">
                                    {{ sale.user?.name }}
                                </h3>
                            </div>
                        </div>
                    </NxCard>
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <div class="sticky top-6">
                        <NxCard>
                            <h2 class="text-xl font-bold mb-6">
                                💰 Satış Özeti
                            </h2>

                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span>Ara Toplam</span>
                                    <strong>
                                        ₺{{ Number(sale.subtotal).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                    </strong>
                                </div>

                                <div class="flex justify-between">
                                    <span>KDV</span>
                                    <strong>
                                        ₺{{ Number(sale.vat).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                    </strong>
                                </div>

                                <hr>

                                <div class="flex justify-between text-xl font-bold">
                                    <span>Genel Toplam</span>
                                    <span class="text-blue-600">
                                        ₺{{ Number(sale.grand_total).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                    </span>
                                </div>
                            </div>
                        </NxCard>
                    </div>
                </div>
            </div>

            <NxCard>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">
                        📦 Satış Kalemleri
                    </h2>
                    <span class="text-slate-500">
                        {{ sale.items.length }} Kalem
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-slate-50">
                            <tr>
                                <th class="text-left py-4 px-4">Ürün</th>
                                <th class="text-left">Depo</th>
                                <th class="text-center">Adet</th>
                                <th class="text-right">Birim Fiyat</th>
                                <th class="text-right">İskonto</th>
                                <th class="text-center">KDV</th>
                                <th class="text-right pr-4">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in sale.items"
                                :key="item.id"
                                class="border-b hover:bg-slate-50 transition"
                            >
                                <td class="py-4 px-4">
                                    <div class="font-semibold">
                                        {{ item.product?.name }}
                                    </div>
                                </td>
                                <td>{{ item.warehouse?.name }}</td>
                                <td class="text-center font-semibold">
                                    {{ item.quantity }}
                                </td>
                                <td class="text-right">
                                    ₺{{ Number(item.unit_price).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                </td>
                                <td class="text-right">
                                    ₺{{ Number(item.discount).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                </td>
                                <td class="text-center">%{{ item.vat }}</td>
                                <td class="text-right pr-4 font-bold text-blue-600">
                                    ₺{{ Number(item.line_total).toLocaleString('tr-TR', { minimumFractionDigits: 2 }) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </NxCard>

            <NxCard v-if="sale.notes">
                <h2 class="text-xl font-bold mb-5">
                    📝 Satış Notları
                </h2>
                <div class="rounded-xl bg-slate-50 p-6 leading-7">
                    {{ sale.notes }}
                </div>
            </NxCard>
        </div>
    </NxLayout>
</template>