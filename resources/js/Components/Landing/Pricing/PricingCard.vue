<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    plan: Object
})
</script>

<template>

<div
    :class="[
        'relative h-full rounded-[30px] border bg-white px-8 py-8 transition-all duration-300 hover:-translate-y-2',
        plan.featured
            ? 'border-blue-600 shadow-[0_25px_70px_rgba(37,99,235,.16)] scale-[1.02]'
            : 'border-slate-200 shadow-[0_15px_45px_rgba(15,23,42,.08)]'
    ]">

    <!-- Badge -->
    <div
        v-if="plan.featured"
        class="absolute right-6 top-6 rounded-full bg-blue-600 px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-white">

        EN POPÜLER

    </div>

    <!-- Başlık -->

    <h3
        class="text-2xl font-black text-slate-900">

        {{ plan.name }}

    </h3>

    <p
        class="mt-2 text-base text-slate-500">

        {{ plan.subtitle }}

    </p>

    <!-- Fiyat -->

    <div
        class="mt-7 flex items-end gap-2 text-slate-900">

        <span class="text-4xl font-black tracking-tight">{{ plan.price }}</span>
        <span
            v-if="plan.period"
            class="pb-1 text-base font-black text-slate-500"
        >
            {{ plan.period }}
        </span>

    </div>

    <!-- Özellikler -->

    <div
        class="mt-8 space-y-3">

        <div
            v-for="feature in plan.features"
            :key="feature"
            class="flex items-center gap-3">

            <div
                class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">

                ✓

            </div>

            <span class="text-[17px] text-slate-700">

                {{ feature }}

            </span>

        </div>

    </div>

    <!-- Buton -->

    <Link
        :href="plan.routeName === 'landing.contact' ? route('landing.contact') : route('register')"
        :class="[
            'mt-8 inline-flex w-full items-center justify-center rounded-2xl py-3 text-base font-semibold transition-all duration-300',
            plan.featured
                ? 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg'
                : 'border border-slate-300 bg-white text-slate-700 hover:border-blue-500 hover:text-blue-600'
        ]">

        {{ plan.button }}

    </Link>

</div>

</template>
