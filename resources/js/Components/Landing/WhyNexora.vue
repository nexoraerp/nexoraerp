<script setup>
import { Link } from '@inertiajs/vue3';

const items = [
    'Satıştan finansa tüm süreçler tek platformda',
    'Gerçek zamanlı raporlama ve analiz',
    'Bulut tabanlı güvenli altyapı',
    'İlk ödemenizi 2 ay sonra yapın',
];
</script>

<template>

<section class="bg-white py-28">

    <div class="mx-auto grid max-w-7xl items-center gap-20 px-6 lg:grid-cols-2">

        <!-- Sol -->

        <div>

            <div
                class="flex aspect-[4/5] items-center justify-center rounded-[32px] bg-gradient-to-br from-slate-100 to-slate-200"
            >

                <span class="text-slate-400 text-lg">
                    Kurumsal Görsel Alanı
                </span>

            </div>

        </div>

        <!-- Sağ -->

        <div>

            <span
                class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
            >
                Neden Nexora?
            </span>

            <h2
                class="mt-6 text-5xl font-black leading-tight text-slate-900"
            >
                İşletmenizi yönetmek hiç bu kadar kolay olmadı.
            </h2>

            <p
                class="mt-6 text-lg leading-8 text-slate-600"
            >
                Nexora, satıştan finansa, stoktan müşteri yönetimine kadar
                tüm süreçlerinizi tek platform üzerinden yönetmenizi sağlar.
            </p>

            <div class="mt-10 space-y-5">

                <div
                    v-for="item in items"
                    :key="item"
                    class="flex items-center gap-4"
                >

                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold"
                    >
                        ✓
                    </div>

                    <span class="text-slate-700 font-medium">
                        {{ item }}
                    </span>

                </div>

            </div>

            <div class="mt-12">

                <Link
                    href="#"
                    class="inline-flex rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 font-semibold text-white transition hover:-translate-y-1"
                >
                    Hemen Başlayın
                </Link>

            </div>

        </div>

    </div>

</section>

</template>