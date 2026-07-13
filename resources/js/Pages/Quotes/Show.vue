<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import QuoteStatusBadge from '@/Components/Quotes/QuoteStatusBadge.vue';

const props = defineProps({
    quote: {
        type: Object,
        required: true,
    },
});

const formatMoney = (value) => Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const grossProfit = () => props.quote.items.reduce((total, item) => {
    const purchasePrice = Number(item.product?.purchase_price || 0);
    const quantity = Number(item.quantity || 0);
    const discount = Number(item.discount || 0);
    const unitPrice = Number(item.unit_price || 0);

    return total + ((unitPrice - purchasePrice) * quantity - discount);
}, 0);

const profitMargin = () => {
    const total = Number(props.quote.subtotal || 0);

    if (total <= 0) {
        return 0;
    }

    return (grossProfit() / total) * 100;
};

const patch = (routeName) => {
    router.patch(route(routeName, props.quote.id), {}, {
        preserveScroll: true,
    });
};

const convertToSale = () => {
    router.post(route('quotes.convert', props.quote.id));
};
</script>

<template>
    <Head :title="quote.quote_no" />

    <NxLayout>
        <div class="space-y-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold text-slate-950">Teklif Detayı</h1>
                        <QuoteStatusBadge :status="quote.status" />
                    </div>
                    <p class="mt-2 text-slate-500">{{ quote.quote_no }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link :href="route('quotes.index')">
                        <NxButton variant="secondary">Liste</NxButton>
                    </Link>

                    <Link :href="route('quotes.print', quote.id)">
                        <NxButton variant="secondary">Teklif Çıktısı</NxButton>
                    </Link>

                    <Link
                        v-if="!['Converted', 'Cancelled'].includes(quote.status)"
                        :href="route('quotes.edit', quote.id)"
                    >
                        <NxButton variant="ghost">Düzenle</NxButton>
                    </Link>

                    <NxButton
                        v-if="quote.status === 'Draft'"
                        variant="secondary"
                        @click="patch('quotes.send')"
                    >
                        Gönderildi İşaretle
                    </NxButton>

                    <NxButton
                        v-if="['Draft', 'Sent'].includes(quote.status)"
                        variant="success"
                        @click="patch('quotes.accept')"
                    >
                        Kabul Et
                    </NxButton>

                    <NxButton
                        v-if="['Draft', 'Sent'].includes(quote.status)"
                        variant="danger"
                        @click="patch('quotes.reject')"
                    >
                        Reddet
                    </NxButton>

                    <NxButton
                        variant="secondary"
                        @click="patch('quotes.analyze')"
                    >
                        Analizi Yenile
                    </NxButton>

                    <NxButton
                        v-if="quote.is_convertible"
                        @click="convertToSale"
                    >
                        Satışa Dönüştür
                    </NxButton>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 space-y-6 xl:col-span-8">
                    <NxCard>
                        <div class="grid gap-6 md:grid-cols-4">
                            <div>
                                <p class="text-sm text-slate-500">Cari</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-950">{{ quote.customer?.name }}</h3>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Teklif Tarihi</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-950">{{ quote.quote_date }}</h3>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Geçerlilik</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-950">{{ quote.valid_until ?? '-' }}</h3>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Olasılık</p>
                                <h3 class="mt-1 text-lg font-bold text-blue-700">%{{ quote.probability }}</h3>
                            </div>
                        </div>
                    </NxCard>

                    <NxCard>
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-950">Teklif Kalemleri</h2>
                            <span class="text-sm text-slate-500">{{ quote.items.length }} kalem</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="border-b bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-4 text-left">Ürün</th>
                                        <th class="text-left">Depo</th>
                                        <th class="text-center">Miktar</th>
                                        <th class="text-right">Birim Fiyat</th>
                                        <th class="text-right">İskonto</th>
                                        <th class="text-center">KDV</th>
                                        <th class="pr-4 text-right">Toplam</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="item in quote.items"
                                        :key="item.id"
                                        class="border-b transition hover:bg-slate-50"
                                    >
                                        <td class="px-4 py-4 font-semibold">{{ item.product?.name }}</td>
                                        <td>{{ item.warehouse?.name }}</td>
                                        <td class="text-center">{{ item.quantity }}</td>
                                        <td class="text-right">₺{{ formatMoney(item.unit_price) }}</td>
                                        <td class="text-right">₺{{ formatMoney(item.discount) }}</td>
                                        <td class="text-center">%{{ item.vat }}</td>
                                        <td class="pr-4 text-right font-bold text-blue-700">
                                            ₺{{ formatMoney(item.line_total) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </NxCard>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <NxCard v-if="quote.notes">
                            <h2 class="mb-4 text-lg font-bold text-slate-950">Notlar</h2>
                            <p class="leading-7 text-slate-600">{{ quote.notes }}</p>
                        </NxCard>

                        <NxCard v-if="quote.terms">
                            <h2 class="mb-4 text-lg font-bold text-slate-950">Şartlar</h2>
                            <p class="leading-7 text-slate-600">{{ quote.terms }}</p>
                        </NxCard>
                    </div>
                </div>

                <div class="col-span-12 space-y-6 xl:col-span-4">
                    <NxCard>
                        <h2 class="mb-6 text-xl font-bold text-slate-950">Teklif Özeti</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span>Ara Toplam</span>
                                <strong>₺{{ formatMoney(quote.subtotal) }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>İskonto</span>
                                <strong>₺{{ formatMoney(quote.discount) }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>KDV</span>
                                <strong>₺{{ formatMoney(quote.vat) }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Tahmini Kar</span>
                                <strong class="text-emerald-700">₺{{ formatMoney(grossProfit()) }}</strong>
                            </div>

                            <div class="flex justify-between">
                                <span>Kar Oranı</span>
                                <strong>%{{ Number(profitMargin()).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</strong>
                            </div>

                            <hr>

                            <div class="flex justify-between text-xl font-bold">
                                <span>Genel Toplam</span>
                                <span class="text-blue-700">₺{{ formatMoney(quote.grand_total) }}</span>
                            </div>
                        </div>

                        <Link
                            class="mt-6 block"
                            :href="route('quotes.print', quote.id)"
                        >
                            <NxButton
                                variant="secondary"
                                block
                            >
                                PDF / Yazdırma Şablonunu Aç
                            </NxButton>
                        </Link>

                        <Link
                            v-if="quote.sale"
                            class="mt-6 block"
                            :href="route('sales.show', quote.sale.id)"
                        >
                            <NxButton
                                variant="secondary"
                                block
                            >
                                Oluşan Satışı Aç
                            </NxButton>
                        </Link>
                    </NxCard>

                    <NxCard v-if="quote.analysis">
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-950">Teklif Analizi</h2>
                            <div class="rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700">
                                {{ quote.analysis.score }}/100
                            </div>
                        </div>

                        <p class="leading-7 text-slate-600">{{ quote.analysis.summary }}</p>

                        <div
                            v-if="quote.analysis.signals?.length"
                            class="mt-5"
                        >
                            <h3 class="font-semibold text-slate-950">Güçlü Sinyaller</h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <li
                                    v-for="signal in quote.analysis.signals"
                                    :key="signal"
                                >
                                    {{ signal }}
                                </li>
                            </ul>
                        </div>

                        <div
                            v-if="quote.analysis.risks?.length"
                            class="mt-5"
                        >
                            <h3 class="font-semibold text-slate-950">Riskler</h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <li
                                    v-for="risk in quote.analysis.risks"
                                    :key="risk"
                                >
                                    {{ risk }}
                                </li>
                            </ul>
                        </div>

                        <div class="mt-5">
                            <h3 class="font-semibold text-slate-950">Öneriler</h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <li
                                    v-for="suggestion in quote.analysis.suggestions"
                                    :key="suggestion"
                                >
                                    {{ suggestion }}
                                </li>
                            </ul>
                        </div>
                    </NxCard>
                </div>
            </div>
        </div>
    </NxLayout>
</template>
