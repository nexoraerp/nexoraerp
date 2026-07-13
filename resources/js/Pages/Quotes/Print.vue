<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ArrowLeft, Printer } from 'lucide-vue-next';

const props = defineProps({
    quote: {
        type: Object,
        required: true,
    },
    company: {
        type: Object,
        default: () => ({}),
    },
});

const formatMoney = (value) => Number(value || 0).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const formatDate = (value) => {
    if (!value) {
        return '-';
    }

    return new Date(value).toLocaleDateString('tr-TR');
};

const itemVatTotal = (item) => {
    const lineTotal = Number(item.line_total || 0);
    const vatRate = Number(item.vat || 0);

    return lineTotal * (vatRate / 100);
};

const customerTitle = computed(() => props.quote.customer?.company || props.quote.customer?.name || '-');

const printQuote = () => {
    window.print();
};
</script>

<template>
    <Head :title="`${quote.quote_no} Teklif Çıktısı`" />

    <div class="min-h-screen bg-slate-100 px-4 py-6 text-slate-900 print:bg-white print:p-0">
        <div class="mx-auto mb-5 flex max-w-5xl items-center justify-between print:hidden">
            <Link
                :href="route('quotes.show', quote.id)"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <ArrowLeft class="h-4 w-4" />
                Teklife Dön
            </Link>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700"
                @click="printQuote"
            >
                <Printer class="h-4 w-4" />
                PDF / Yazdır
            </button>
        </div>

        <main class="quote-sheet mx-auto max-w-5xl bg-white shadow-xl print:mx-0 print:max-w-none print:shadow-none">
            <section class="border-b border-slate-200 px-10 py-8">
                <div class="flex items-start justify-between gap-8">
                    <div>
                        <div class="text-2xl font-black tracking-wide text-slate-950">
                            {{ company.name }}
                        </div>
                        <div class="mt-3 space-y-1 text-sm text-slate-600">
                            <p v-if="company.contact_name">Yetkili: {{ company.contact_name }}</p>
                            <p v-if="company.email">{{ company.email }}</p>
                            <p v-if="company.phone">{{ company.phone }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-4xl font-black text-slate-950">TEKLIF</div>
                        <div class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3">
                            <div class="text-xs font-bold uppercase text-slate-500">Belge No</div>
                            <div class="mt-1 text-lg font-black text-blue-700">{{ quote.quote_no }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-2 gap-8 px-10 py-8">
                <div class="rounded-2xl border border-slate-200 p-5">
                    <div class="text-xs font-black uppercase tracking-wide text-slate-500">Musteri Bilgileri</div>
                    <h2 class="mt-3 text-xl font-black text-slate-950">{{ customerTitle }}</h2>
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <p v-if="quote.customer?.name && quote.customer?.company">Yetkili: {{ quote.customer.name }}</p>
                        <p v-if="quote.customer?.tax_office || quote.customer?.tax_number">
                            {{ quote.customer?.tax_office || '-' }} / {{ quote.customer?.tax_number || '-' }}
                        </p>
                        <p v-if="quote.customer?.email">{{ quote.customer.email }}</p>
                        <p v-if="quote.customer?.phone">{{ quote.customer.phone }}</p>
                        <p v-if="quote.customer?.address" class="leading-6">{{ quote.customer.address }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 p-5">
                    <div class="text-xs font-black uppercase tracking-wide text-slate-500">Teklif Bilgileri</div>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Teklif Tarihi</dt>
                            <dd class="font-bold text-slate-950">{{ formatDate(quote.quote_date) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Gecerlilik</dt>
                            <dd class="font-bold text-slate-950">{{ formatDate(quote.valid_until) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Durum</dt>
                            <dd class="font-bold text-slate-950">{{ quote.status }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Hazirlayan</dt>
                            <dd class="font-bold text-slate-950">{{ quote.user?.name || company.contact_name || '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="px-10 pb-8">
                <table class="w-full border-collapse overflow-hidden rounded-2xl text-sm">
                    <thead>
                        <tr class="bg-slate-950 text-white">
                            <th class="px-4 py-4 text-left font-bold">Urun / Hizmet</th>
                            <th class="px-4 py-4 text-right font-bold">Miktar</th>
                            <th class="px-4 py-4 text-right font-bold">Birim Fiyat</th>
                            <th class="px-4 py-4 text-right font-bold">Iskonto</th>
                            <th class="px-4 py-4 text-right font-bold">KDV</th>
                            <th class="px-4 py-4 text-right font-bold">Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in quote.items"
                            :key="item.id"
                            class="border-b border-slate-200"
                        >
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-950">{{ item.product?.name || '-' }}</div>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ item.product?.code || '-' }}
                                    <span v-if="item.warehouse?.name"> • {{ item.warehouse.name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right font-semibold">{{ Number(item.quantity || 0).toLocaleString('tr-TR') }}</td>
                            <td class="px-4 py-4 text-right font-semibold">₺{{ formatMoney(item.unit_price) }}</td>
                            <td class="px-4 py-4 text-right font-semibold">₺{{ formatMoney(item.discount) }}</td>
                            <td class="px-4 py-4 text-right font-semibold">
                                %{{ Number(item.vat || 0).toLocaleString('tr-TR') }}
                                <div class="mt-1 text-xs text-slate-500">₺{{ formatMoney(itemVatTotal(item)) }}</div>
                            </td>
                            <td class="px-4 py-4 text-right font-black text-slate-950">₺{{ formatMoney(item.line_total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="grid grid-cols-12 gap-8 px-10 pb-10">
                <div class="col-span-7 space-y-5">
                    <div
                        v-if="quote.notes"
                        class="rounded-2xl border border-slate-200 p-5"
                    >
                        <h3 class="font-black text-slate-950">Notlar</h3>
                        <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">{{ quote.notes }}</p>
                    </div>

                    <div
                        v-if="quote.terms"
                        class="rounded-2xl border border-slate-200 p-5"
                    >
                        <h3 class="font-black text-slate-950">Teklif Sartlari</h3>
                        <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">{{ quote.terms }}</p>
                    </div>
                </div>

                <div class="col-span-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Ara Toplam</span>
                                <strong>₺{{ formatMoney(quote.subtotal) }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Iskonto</span>
                                <strong>₺{{ formatMoney(quote.discount) }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">KDV</span>
                                <strong>₺{{ formatMoney(quote.vat) }}</strong>
                            </div>
                            <div class="border-t border-slate-300 pt-4">
                                <div class="flex items-end justify-between gap-4">
                                    <span class="text-base font-black text-slate-950">Genel Toplam</span>
                                    <strong class="text-2xl font-black text-blue-700">₺{{ formatMoney(quote.grand_total) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-4 text-center text-xs font-bold text-slate-500">
                        <div>
                            <div class="h-16 border-b border-slate-300"></div>
                            Firma Yetkilisi
                        </div>
                        <div>
                            <div class="h-16 border-b border-slate-300"></div>
                            Musteri Onayi
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<style scoped>
.quote-sheet {
    min-height: 297mm;
}

@page {
    size: A4;
    margin: 12mm;
}

@media print {
    .quote-sheet {
        min-height: auto;
        width: 100%;
    }
}
</style>
