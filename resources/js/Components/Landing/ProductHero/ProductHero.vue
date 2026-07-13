<script setup>
import LandingImageFrame from '../LandingImageFrame.vue';
</script>

<template>
<section id="features" class="relative overflow-hidden bg-white py-24">

    <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">

        <div>
            <span
                class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">

                NEXORA PLATFORM

            </span>

            <h2 class="mt-7 text-4xl font-bold tracking-tight text-slate-950 lg:text-5xl">

                Operasyon, finans ve müşteri yönetimi aynı veri üzerinde çalışır.

            </h2>

            <p class="mt-6 text-lg leading-8 text-slate-600">
                Nexora’da satış kaydı, stok hareketi, cari bakiye, tahsilat ve raporlama
                birbirinden kopuk modüller değil; tek akışın parçalarıdır. Böylece ekipler
                aynı bilgiye bakar, yönetim ise güncel veriye göre karar alır.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-md border border-slate-200 p-5">
                    <h3 class="font-semibold text-slate-950">Satıştan tahsilata</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Teklif, satış, cari hareket ve ödeme takibi uçtan uca izlenir.
                    </p>
                </div>

                <div class="rounded-md border border-slate-200 p-5">
                    <h3 class="font-semibold text-slate-950">Stoktan rapora</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Depo hareketleri ve kritik stoklar yönetim ekranına anlık yansır.
                    </p>
                </div>

                <div class="rounded-md border border-slate-200 p-5">
                    <h3 class="font-semibold text-slate-950">Finans görünürlüğü</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Gelir, gider, kasa ve açık bakiye tek yerden takip edilir.
                    </p>
                </div>

                <div class="rounded-md border border-slate-200 p-5">
                    <h3 class="font-semibold text-slate-950">Akıllı özetler</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Raporlar sadeleştirilir, riskler ve fırsatlar anlaşılır hale gelir.
                    </p>
                </div>
            </div>
        </div>

        <div>
            <LandingImageFrame
                src="/landing/custom/dashboard.png"
                alt="Nexora platform ekranı"
                body-class="h-[300px] sm:h-[420px] lg:h-[560px]"
                image-class="h-full w-full object-cover object-left-top"
            />
        </div>

    </div>

</section>
</template>
