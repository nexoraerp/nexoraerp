<script setup>
import { Link } from '@inertiajs/vue3';
import LandingLayout from '@/Layouts/LandingLayout.vue';
import Footer from '@/Components/Landing/Footer/Footer.vue';
import SeoHead from '@/Components/Seo/SeoHead.vue';

defineProps({
    page: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <SeoHead
        :title="`${page.title} | Nexora ERP`"
        :description="page.description"
        :canonical="page.canonical"
        :robots="page.robots ?? 'index, follow'"
    />

    <LandingLayout variant="light">
        <section class="border-b border-slate-200 bg-slate-50 pt-36">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 pb-16 pt-10 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-blue-700">
                        Nexora ERP
                    </p>

                    <h1 class="mt-5 max-w-3xl text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">
                        {{ page.title }}
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                        {{ page.description }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link
                            :href="route('register')"
                            class="rounded-lg bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/10 transition hover:bg-blue-800"
                        >
                            Ücretsiz Denemeyi Başlat
                        </Link>

                        <Link
                            :href="route('landing.contact')"
                            class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-800 transition hover:border-blue-200 hover:text-blue-700"
                        >
                            Satış Ekibiyle Görüş
                        </Link>
                    </div>
                </div>

                <aside class="rounded-lg border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-500">
                        Öne Çıkanlar
                    </h2>

                    <ul class="mt-5 space-y-4">
                        <li
                            v-for="highlight in page.highlights"
                            :key="highlight"
                            class="flex gap-3 rounded-lg border border-slate-100 bg-slate-50 p-4 text-sm font-semibold leading-6 text-slate-700"
                        >
                            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-blue-700"></span>
                            <span>{{ highlight }}</span>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="bg-white py-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div
                    v-if="page.pricingPlans?.length"
                    class="mb-12 grid gap-5 lg:grid-cols-3"
                >
                    <article
                        v-for="plan in page.pricingPlans"
                        :key="plan.name"
                        class="rounded-xl border border-slate-200 bg-white p-7 shadow-sm"
                        :class="plan.featured ? 'border-blue-300 shadow-blue-100' : ''"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-black text-slate-950">{{ plan.name }}</h2>
                                <p class="mt-1 text-sm font-semibold text-slate-500">{{ plan.subtitle }}</p>
                            </div>

                            <span
                                v-if="plan.featured"
                                class="rounded-full bg-blue-50 px-3 py-1 text-xs font-black text-blue-700"
                            >
                                En Popüler
                            </span>
                        </div>

                        <div class="mt-7 flex items-end gap-2">
                            <span class="text-4xl font-black tracking-tight text-slate-950">{{ plan.price }}</span>
                            <span
                                v-if="plan.period"
                                class="pb-1 text-base font-black text-slate-500"
                            >
                                {{ plan.period }}
                            </span>
                        </div>

                        <ul class="mt-7 space-y-3">
                            <li
                                v-for="feature in plan.features"
                                :key="feature"
                                class="flex gap-3 text-sm font-semibold leading-6 text-slate-700"
                            >
                                <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs text-white">✓</span>
                                <span>{{ feature }}</span>
                            </li>
                        </ul>

                        <Link
                            :href="plan.routeName === 'register' ? route('register') : route('landing.contact')"
                            class="mt-8 inline-flex w-full items-center justify-center rounded-lg px-5 py-3 text-sm font-black transition"
                            :class="plan.featured ? 'bg-blue-700 text-white hover:bg-blue-800' : 'border border-slate-300 text-slate-800 hover:border-blue-300 hover:text-blue-700'"
                        >
                            {{ plan.button }}
                        </Link>
                    </article>
                </div>

                <div
                    v-if="page.type === 'legal'"
                    class="mx-auto max-w-5xl rounded-xl border border-slate-200 bg-white px-7 py-8 shadow-sm md:px-10 md:py-10"
                >
                    <article
                        v-for="section in page.sections"
                        :key="section.heading"
                        class="border-b border-slate-100 py-7 last:border-b-0 first:pt-0 last:pb-0"
                    >
                        <h2 class="text-xl font-black text-slate-950">
                            {{ section.heading }}
                        </h2>

                        <p class="mt-4 whitespace-pre-line text-base leading-8 text-slate-600">
                            {{ section.body }}
                        </p>
                    </article>
                </div>

                <div
                    v-else
                    class="grid gap-5 md:grid-cols-2 xl:grid-cols-3"
                >
                    <article
                        v-for="section in page.sections"
                        :key="section.heading"
                        class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-200 hover:shadow-md"
                    >
                        <h2 class="text-lg font-black text-slate-950">
                            {{ section.heading }}
                        </h2>

                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            {{ section.body }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="border-y border-slate-200 bg-slate-50 py-14">
            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-950">
                        Nexora ERP ile işletmenizi tek ekrandan yönetin.
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        14 gün ücretsiz deneyin. Deneme sonunda verilerinize erişmeye devam edin, yeni kayıt ekleme lisansla aktif olur.
                    </p>
                </div>

                <Link
                    :href="route('register')"
                    class="inline-flex items-center justify-center rounded-lg bg-slate-950 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800"
                >
                    Kayıt Ol ve Denemeyi Başlat
                </Link>
            </div>
        </section>

        <Footer />
    </LandingLayout>
</template>
