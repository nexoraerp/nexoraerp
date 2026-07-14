<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

<div>

    <!-- Badge -->

    <div
        class="inline-flex rounded-md bg-white/10 px-4 py-2">

        <span
            class="text-xs font-semibold tracking-[0.18em] text-white">

            14 GÜN ÜCRETSİZ DENEME

        </span>

    </div>

    <!-- Başlık -->

    <h2
        class="mt-6 text-4xl font-bold leading-[1.08] text-white lg:text-5xl">

        Nexora’yı bugün kullanmaya başlayın.

    </h2>

    <!-- Açıklama -->

    <p
        class="mt-6 max-w-xl text-lg leading-8 text-slate-300">

        Satış, stok, finans, cari yönetimi ve akıllı raporlama özelliklerini
        14 gün boyunca ücretsiz deneyin. Kredi kartı gerekmez.

    </p>

    <!-- Avantajlar -->

    <div
        class="mt-8 grid gap-y-3 gap-x-10 sm:grid-cols-2">

        <div class="flex items-center gap-3 text-base text-white">

            ✓ 14 gün ücretsiz kullanım

        </div>

        <div class="flex items-center gap-3 text-base text-white">

            ✓ Kredi kartı gerekmez

        </div>

        <div class="flex items-center gap-3 text-base text-white">

            ✓ Hızlı kayıt

        </div>

        <div class="flex items-center gap-3 text-base text-white">

            ✓ Nexora AI destekli başlangıç

        </div>

    </div>

    <!-- Buton -->

    <Link
        :href="route('register')"
        class="mt-8 inline-flex items-center justify-center rounded-md bg-white px-7 py-3.5 text-base font-semibold text-slate-950 transition hover:bg-slate-100">

        Kayıt Ol ve Ücretsiz Denemeyi Başlat

    </Link>

</div>

</template>
