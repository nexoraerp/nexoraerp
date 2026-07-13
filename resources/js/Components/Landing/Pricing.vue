<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

<section
    id="pricing"
    class="bg-slate-50 py-28"
>

    <div class="mx-auto max-w-7xl px-6">

        <!-- Başlık -->

        <div class="mx-auto max-w-3xl text-center">

            <span
                class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
            >
                Basit Fiyatlandırma
            </span>

            <h2
                class="mt-6 text-5xl font-black text-slate-900"
            >
                Net Paketler,
                <span class="text-blue-600">
                    Şeffaf Fiyatlar
                </span>
            </h2>

            <p
                class="mt-6 text-lg leading-8 text-slate-600"
            >
                Gizli ücret yok. Başlangıç, profesyonel ve kurumsal ihtiyaçlar için sade paketler.
            </p>

        </div>

        <!-- Kart -->

        <div class="mx-auto mt-20 max-w-5xl">

            <div
                class="overflow-hidden rounded-[36px] border border-slate-200 bg-white shadow-2xl"
            >

                <div class="grid lg:grid-cols-2">

                    <!-- Sol -->

                    <div class="p-12">

                        <span
                            class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700"
                        >
                            En Çok Tercih Edilen
                        </span>

                        <h3
                            class="mt-8 text-4xl font-black text-slate-900"
                        >
                            Nexora ERP
                        </h3>

                        <p
                            class="mt-4 text-slate-600"
                        >
                            İşletmeniz için ihtiyaç duyacağınız tüm modüller tek pakette.
                        </p>

                        <div
                            class="mt-10 flex items-end gap-2"
                        >

                            <span
                                class="text-6xl font-black text-slate-900"
                            >
                                ₺3.990
                            </span>

                            <span
                                class="pb-2 font-black text-slate-500"
                            >
                                /Ay
                            </span>

                        </div>

                        <p
                            class="mt-4 text-sm text-slate-500"
                        >
                            Professional paket aylık başlangıç fiyatıdır. Starter paket ₺1.990 /Ay olarak sunulur.
                        </p>

                    </div>

                    <!-- Sağ -->

                    <div
                        class="bg-gradient-to-br from-blue-600 to-indigo-700 p-12 text-white"
                    >

                        <h4
                            class="text-3xl font-black"
                        >
                            Bugün Başlayın
                        </h4>

                        <p
                            class="mt-4 text-blue-100 leading-8"
                        >
                            İşletmenizi bugün dijitalleştirin.
                            İlk ödemenizi 2 ay sonra yapın.
                        </p>

                        <div class="mt-10 space-y-5">

                            <div>✓ Kurulum ücretsiz</div>

                            <div>✓ Kredi kartı gerektirmez</div>

                            <div>✓ Tüm modüller dahil</div>

                            <div>✓ Bulut tabanlı altyapı</div>

                            <div>✓ Ücretsiz güncellemeler</div>

                            <div>✓ 7/24 destek</div>

                        </div>

                        <Link
                            href="#"
                            class="mt-12 inline-flex w-full items-center justify-center rounded-2xl bg-white px-8 py-4 text-lg font-bold text-blue-700 transition hover:-translate-y-1"
                        >
                            Hemen Başlayın
                        </Link>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

</template>
