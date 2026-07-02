<script setup>
const product = [
    'Özellikler',
    'Modüller',
    'Fiyatlandırma',
    'SSS',
];

const company = [
    'Hakkımızda',
    'İletişim',
    'Blog',
    'Kariyer',
];

const legal = [
    'Gizlilik Politikası',
    'Kullanım Şartları',
    'KVKK',
];
</script>

<template>

<footer class="bg-slate-950">

    <div class="mx-auto max-w-7xl px-6 py-24">

        <div class="grid gap-16 lg:grid-cols-5">

            <!-- Logo -->

            <div class="lg:col-span-2">

                <h2 class="text-4xl font-black text-white">
                    NEXORA
                </h2>

                <p class="mt-5 max-w-md leading-8 text-slate-400">

                    İşletmenizin Dijital İşletim Sistemi.

                    Satıştan finansa, stoktan raporlamaya kadar
                    tüm süreçlerinizi tek platform üzerinden yönetin.

                </p>

                <div class="mt-10 flex gap-3">

                    <div
                        class="rounded-xl border border-slate-800 bg-slate-900 px-4 py-2 text-sm text-slate-300"
                    >
                        Bulut ERP
                    </div>

                    <div
                        class="rounded-xl border border-slate-800 bg-slate-900 px-4 py-2 text-sm text-slate-300"
                    >
                        7/24 Destek
                    </div>

                </div>

            </div>

            <!-- Ürün -->

            <div>

                <h3 class="font-bold text-white">
                    Ürün
                </h3>

                <ul class="mt-6 space-y-4">

                    <li
                        v-for="item in product"
                        :key="item"
                    >
                        <a
                            href="#"
                            class="text-slate-400 transition hover:text-white"
                        >
                            {{ item }}
                        </a>
                    </li>

                </ul>

            </div>

            <!-- Şirket -->

            <div>

                <h3 class="font-bold text-white">
                    Şirket
                </h3>

                <ul class="mt-6 space-y-4">

                    <li
                        v-for="item in company"
                        :key="item"
                    >
                        <a
                            href="#"
                            class="text-slate-400 transition hover:text-white"
                        >
                            {{ item }}
                        </a>
                    </li>

                </ul>

            </div>

            <!-- Yasal -->

            <div>

                <h3 class="font-bold text-white">
                    Yasal
                </h3>

                <ul class="mt-6 space-y-4">

                    <li
                        v-for="item in legal"
                        :key="item"
                    >
                        <a
                            href="#"
                            class="text-slate-400 transition hover:text-white"
                        >
                            {{ item }}
                        </a>
                    </li>

                </ul>

            </div>

        </div>

        <div
            class="mt-20 flex flex-col items-center justify-between gap-6 border-t border-slate-800 pt-8 lg:flex-row"
        >

            <p class="text-sm text-slate-500">

                © 2026 Nexora ERP.
                Tüm hakları saklıdır.

            </p>

            <p class="text-sm text-slate-600">

                İşletmenizin Dijital İşletim Sistemi

            </p>

        </div>

    </div>

</footer>

</template>