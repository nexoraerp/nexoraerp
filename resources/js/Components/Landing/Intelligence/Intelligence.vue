<script setup>
import LandingImageFrame from '../LandingImageFrame.vue';
</script>

<template>
    <section id="solutions" class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-center gap-14 lg:grid-cols-[1fr_0.95fr]">
                <div>
                    <span class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        YAPAY ZEKA DESTEKLİ RAPORLAMA
                    </span>

                    <h2 class="mt-7 text-4xl font-bold tracking-tight text-slate-950 lg:text-5xl">
                        Verilerinizi sadece listelemeyin, yapay zeka ile yorumlanabilir hale getirin.
                    </h2>

                    <p class="mt-6 text-lg leading-8 text-slate-600">
                        Nexora, yapay zeka destekli raporlama altyapısıyla operasyon verilerini anlaşılır rapor özetlerine dönüştürür.
                        Satış performansı, kritik stok, geciken tahsilatlar ve finans akışı
                        yönetici diliyle açıklanır; ekipler neye odaklanacağını daha hızlı görür.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-md border border-slate-200 p-5">
                            <h3 class="font-semibold text-slate-950">Yönetici özetleri</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Günlük satış, kasa, açık bakiye ve stok durumunu kısa metinlerle özetler.
                            </p>
                        </div>

                        <div class="rounded-md border border-slate-200 p-5">
                            <h3 class="font-semibold text-slate-950">Risk ve fırsat uyarıları</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Kritik stok, düşen satış, geciken tahsilat ve nakit sıkışıklığını erken görünür kılar.
                            </p>
                        </div>

                        <div class="rounded-md border border-slate-200 p-5">
                            <h3 class="font-semibold text-slate-950">Doğal dille sorgulama</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                “Bu ay en çok satan ürünler hangileri?” gibi sorularla veriye hızlı ulaşılır.
                            </p>
                        </div>

                        <div class="rounded-md border border-slate-200 p-5">
                            <h3 class="font-semibold text-slate-950">Karar destek çıktıları</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Raporların yanında önerilen aksiyonlar ve takip edilmesi gereken noktalar sunulur.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-[0_24px_70px_rgba(15,23,42,.10)]">
                    <LandingImageFrame
                            src="/landing/custom/finance.png"
                            alt="Nexora finans ve raporlama ekranı"
                            body-class="h-[260px] sm:h-[360px] lg:h-[420px]"
                            image-class="h-full w-full object-cover object-left-top"
                    />

                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-md bg-white p-4">
                            <div class="text-sm text-slate-500">Açık bakiye</div>
                            <div class="mt-2 text-xl font-bold text-slate-950">Takipte</div>
                        </div>

                        <div class="rounded-md bg-white p-4">
                            <div class="text-sm text-slate-500">Kritik stok</div>
                            <div class="mt-2 text-xl font-bold text-slate-950">Öncelikli</div>
                        </div>

                        <div class="rounded-md bg-white p-4">
                            <div class="text-sm text-slate-500">Satış trendi</div>
                            <div class="mt-2 text-xl font-bold text-slate-950">Canlı</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
