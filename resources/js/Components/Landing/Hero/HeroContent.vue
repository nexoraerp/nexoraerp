<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

<div
    class="relative max-w-[720px] animate-[fadeLeft_.8s_ease-out] rounded-xl border border-white/10 bg-white/[0.025] px-5 py-5 shadow-xl shadow-black/10 backdrop-blur-xl sm:px-6 sm:py-6 lg:px-7 lg:py-7">

    <!-- Badge -->

    <div
        class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-white/[0.04] px-4 py-2 shadow-sm shadow-black/10">

        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

        <span class="text-sm font-semibold text-slate-100">

            Bulut ERP, finans ve operasyon yönetimi

        </span>

    </div>

    <!-- Başlık -->

    <h1
        class="mt-6 text-5xl font-bold tracking-tight leading-[1.04] text-white lg:text-6xl">

        İşletmenizi tek merkezden,

        <span class="block text-blue-500">

            ölçülebilir verilerle yönetin.

        </span>

    </h1>

    <!-- Açıklama -->

    <p
        class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">

        Nexora; satış, stok, cari, tahsilat, finans ve yönetim raporlarını
        aynı çalışma alanında birleştirir. Yöneticiler gerçek zamanlı tablolar,
        akıllı rapor özetleri ve karar destek çıktılarıyla daha hızlı aksiyon alır.

    </p>

    <div
        class="mt-6 inline-flex max-w-full flex-wrap items-center gap-3 rounded-md border border-blue-400/30 bg-blue-500/10 px-4 py-3 text-sm font-semibold text-blue-100"
    >
        <span class="rounded bg-blue-500 px-2 py-1 text-xs uppercase tracking-wide text-white">
            Şimdi al
        </span>
        <span>
            2 ay sonra öde, ERP geçişini bugün başlat.
        </span>
    </div>

    <!-- CTA -->

    <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">

        <Link
            :href="route('register')"
            class="rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 sm:text-base">

            Kayıt Ol ve Ücretsiz Denemeyi Başlat

        </Link>

        <a
            href="#modules"
            class="rounded-md border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-semibold text-slate-100 transition hover:border-blue-400/60 hover:text-white sm:text-base">

            Modülleri İncele

        </a>

    </div>

    <!-- Avantajlar -->

    <div
        class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">

        <div
            class="flex items-center gap-2 rounded-md border border-white/10 bg-white/[0.04] px-4 py-3">

            <div
                class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">

                ✓

            </div>

            <span
                class="text-sm font-medium text-slate-200">

                Bulut Kurulum

            </span>

        </div>

        <div
            class="flex items-center gap-2 rounded-md border border-white/10 bg-white/[0.04] px-4 py-3">

            <div
                class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">

                ✓

            </div>

            <span
                class="text-sm font-medium text-slate-200">

                Canlı Raporlama

            </span>

        </div>

        <div
            class="flex items-center gap-2 rounded-md border border-white/10 bg-white/[0.04] px-4 py-3">

            <div
                class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-xs">

                ✓

            </div>

            <span
                class="text-sm font-medium text-slate-200">

                Uzman Destek

            </span>

        </div>

    </div>

    <div class="mt-8 grid grid-cols-3 gap-6 border-t border-white/10 pt-6">
        <div>
            <div class="text-3xl font-bold text-white">12+</div>
            <div class="mt-1 text-sm text-slate-400">iş süreci</div>
        </div>

        <div>
            <div class="text-3xl font-bold text-white">24/7</div>
            <div class="mt-1 text-sm text-slate-400">bulut erişim</div>
        </div>

        <div>
            <div class="text-3xl font-bold text-white">Anlık</div>
            <div class="mt-1 text-sm text-slate-400">raporlar</div>
        </div>
    </div>

</div>

</template>
