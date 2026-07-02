<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const active = ref('sales');

const modules = {
    sales: {
        title: 'Satış Yönetimi',
        description:
            'Tekliften siparişe, faturadan tahsilata kadar tüm satış sürecinizi tek ekrandan yönetin.',
        features: [
            'Satış Teklifleri',
            'Sipariş Yönetimi',
            'Satış Faturaları',
            'Tahsilat Takibi',
        ],
    },

    finance: {
        title: 'Finans Yönetimi',
        description:
            'Kasa, banka ve tüm finans hareketlerinizi anlık olarak takip edin.',
        features: [
            'Kasa',
            'Banka',
            'Gelir / Gider',
            'Nakit Akışı',
        ],
    },

    stock: {
        title: 'Stok Yönetimi',
        description:
            'Ürünlerinizi, depolarınızı ve stok hareketlerinizi kolayca yönetin.',
        features: [
            'Depolar',
            'Stok Hareketleri',
            'Minimum Stok',
            'Sayım',
        ],
    },

    crm: {
        title: 'Cari Yönetimi',
        description:
            'Müşterilerinizi ve tedarikçilerinizi tek merkezden yönetin.',
        features: [
            'Cari Kartları',
            'Bakiye Takibi',
            'Borç / Alacak',
            'İşlem Geçmişi',
        ],
    },

    reports: {
        title: 'Raporlama',
        description:
            'İşletmenizin performansını detaylı raporlarla analiz edin.',
        features: [
            'Satış Raporları',
            'Finans Raporları',
            'Stok Analizi',
            'Kârlılık',
        ],
    },
};

const current = computed(() => modules[active.value]);
</script>

<template>

<section class="bg-slate-50 py-28">

    <div class="mx-auto max-w-7xl px-6">

        <div class="mx-auto max-w-3xl text-center">

            <span
                class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
            >
                ERP Modülleri
            </span>

            <h2 class="mt-6 text-5xl font-black text-slate-900">
                Tüm İşletmeniz Tek Platformda
            </h2>

            <p class="mt-6 text-lg leading-8 text-slate-600">
                Satıştan finansa, stoktan raporlamaya kadar işletmenizin ihtiyaç
                duyduğu tüm modüller tek bir sistemde.
            </p>

        </div>

        <!-- Tabs -->

        <div class="mt-14 flex flex-wrap justify-center gap-3">

            <button
                v-for="(module,key) in modules"
                :key="key"
                @click="active = key"
                :class="[
                    'rounded-2xl px-6 py-3 font-semibold transition',
                    active === key
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-300'
                ]"
            >
                {{ module.title }}
            </button>

        </div>

        <!-- Content -->

        <div
            class="mt-16 grid items-center gap-16 lg:grid-cols-2"
        >

            <!-- Sol -->

            <div>

                <h3
                    class="text-4xl font-black text-slate-900"
                >
                    {{ current.title }}
                </h3>

                <p
                    class="mt-6 text-lg leading-8 text-slate-600"
                >
                    {{ current.description }}
                </p>

                <div class="mt-10 space-y-4">

                    <div
                        v-for="item in current.features"
                        :key="item"
                        class="flex items-center gap-3"
                    >
                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600"
                        >
                            ✓
                        </span>

                        {{ item }}

                    </div>

                </div>

                <Link
                    href="#"
                    class="mt-10 inline-flex rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 font-semibold text-white"
                >
                    Hemen Başlayın
                </Link>

            </div>

            <!-- Sağ -->

            <div>

                <div
                    class="flex aspect-[4/3] items-center justify-center rounded-[32px] border border-slate-200 bg-white shadow-xl"
                >

                    <span class="text-slate-400">
                        {{ current.title }} Görseli
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>

</template>