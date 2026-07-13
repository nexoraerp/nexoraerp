<script setup>
import LandingImageFrame from '../LandingImageFrame.vue';
import { showcaseItems } from './showcaseData';
</script>

<template>
    <section
        id="modules"
        class="relative overflow-hidden bg-slate-50 py-28"
    >
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="inline-flex rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                    MODÜL SAYFALARI
                </span>

                <h2 class="mt-7 text-4xl font-bold tracking-tight text-slate-950 lg:text-5xl">
                    Her departman için ayrı, detaylı ve okunabilir çalışma alanları.
                </h2>

                <p class="mt-6 text-lg leading-8 text-slate-600">
                    Yönetim panelinden cari takibine kadar her modül, gerçek ekran görüntüleriyle
                    desteklenen profesyonel sayfa düzeninde anlatılır. Görseller banner oranında
                    ölçeklenir, içerikler ise müşterinin hızlıca faydayı görmesi için sade tutulur.
                </p>
            </div>

            <div class="mt-16 space-y-16">
                <article
                    v-for="(item, index) in showcaseItems"
                    :key="item.id"
                    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,.08)]"
                >
                    <div
                        :class="[
                            'grid items-stretch gap-0 lg:grid-cols-[0.95fr_1.05fr]',
                            index % 2 === 1 ? 'lg:[&>*:first-child]:order-2' : ''
                        ]"
                    >
                        <div class="flex flex-col justify-between p-7 sm:p-9 lg:p-10">
                            <div>
                                <span class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-700">
                                    {{ item.eyebrow }}
                                </span>

                                <h3 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">
                                    {{ item.title }}
                                </h3>

                                <p class="mt-4 text-base leading-7 text-slate-600">
                                    {{ item.summary }}
                                </p>

                                <div class="mt-8 space-y-3">
                                    <div
                                        v-for="feature in item.features"
                                        :key="feature"
                                        class="flex gap-3 text-sm leading-6 text-slate-700"
                                    >
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-700"></span>
                                        <span>{{ feature }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 grid grid-cols-3 gap-3">
                                <div
                                    v-for="stat in item.stats"
                                    :key="stat.label"
                                    class="rounded-md border border-slate-200 bg-slate-50 p-4"
                                >
                                    <div class="text-lg font-bold text-slate-950">
                                        {{ stat.value }}
                                    </div>
                                    <div class="mt-1 text-xs leading-5 text-slate-500">
                                        {{ stat.label }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-100 p-4 sm:p-6 lg:p-8">
                            <LandingImageFrame
                                :src="item.image"
                                :alt="`${item.title} ekran görüntüsü`"
                                body-class="h-[260px] sm:h-[360px] lg:h-full lg:min-h-[430px]"
                                image-class="h-full w-full object-cover object-left-top"
                            />
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
