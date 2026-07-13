<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSearch from '@/Components/UI/NxSearch.vue';
import QuoteStatusBadge from '@/Components/Quotes/QuoteStatusBadge.vue';

const props = defineProps({
    quotes: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');

const filteredQuotes = computed(() => {
    const keyword = search.value.toLowerCase();

    return props.quotes.filter(quote => (
        quote.quote_no?.toLowerCase().includes(keyword) ||
        quote.customer?.name?.toLowerCase().includes(keyword) ||
        quote.status?.toLowerCase().includes(keyword)
    ));
});

const formatMoney = (value) => Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});
</script>

<template>
    <Head title="Teklifler" />

    <NxLayout>
        <NxPageHeader
            title="Teklif Yönetimi"
            subtitle="Teklifleri hazırlayın, analiz edin ve satışa dönüştürün."
        >
            <Link :href="route('quotes.create')">
                <NxButton>Yeni Teklif</NxButton>
            </Link>
        </NxPageHeader>

        <div class="mb-6 max-w-md">
            <NxSearch
                v-model="search"
                placeholder="Teklif ara..."
            />
        </div>

        <NxCard>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-slate-50">
                        <tr>
                            <th class="px-4 py-4 text-left">Teklif No</th>
                            <th class="text-left">Cari</th>
                            <th class="text-center">Tarih</th>
                            <th class="text-center">Geçerlilik</th>
                            <th class="text-right">Toplam</th>
                            <th class="text-center">Olasılık</th>
                            <th class="text-center">Durum</th>
                            <th class="pr-4 text-right">İşlemler</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="quote in filteredQuotes"
                            :key="quote.id"
                            class="border-b transition hover:bg-slate-50"
                        >
                            <td class="px-4 py-4 font-semibold text-blue-700">
                                {{ quote.quote_no }}
                            </td>
                            <td>{{ quote.customer?.name }}</td>
                            <td class="text-center">{{ quote.quote_date }}</td>
                            <td class="text-center">{{ quote.valid_until ?? '-' }}</td>
                            <td class="text-right font-bold">₺{{ formatMoney(quote.grand_total) }}</td>
                            <td class="text-center">
                                <span class="font-semibold">%{{ quote.probability }}</span>
                            </td>
                            <td class="text-center">
                                <QuoteStatusBadge :status="quote.status" />
                            </td>
                            <td class="pr-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('quotes.show', quote.id)">
                                        <NxButton
                                            size="sm"
                                            variant="secondary"
                                        >
                                            Detay
                                        </NxButton>
                                    </Link>

                                    <Link
                                        v-if="!['Converted', 'Cancelled'].includes(quote.status)"
                                        :href="route('quotes.edit', quote.id)"
                                    >
                                        <NxButton
                                            size="sm"
                                            variant="ghost"
                                        >
                                            Düzenle
                                        </NxButton>
                                    </Link>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="filteredQuotes.length === 0">
                            <td
                                colspan="8"
                                class="px-4 py-12 text-center text-slate-500"
                            >
                                Henüz teklif bulunamadı.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </NxCard>
    </NxLayout>
</template>
