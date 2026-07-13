<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
});
</script>

<template>

<div class="flex items-center gap-2 lg:gap-4">

    <Link
        :href="route('login')"
        :class="[
            'rounded-md px-3 py-2 text-sm font-semibold transition sm:px-4 lg:px-5 lg:py-3 lg:text-[15px]',
            props.variant === 'light'
                ? 'text-slate-700 hover:bg-slate-100 hover:text-blue-700'
                : 'text-slate-200 hover:bg-white/10 hover:text-white'
        ]">

        Giriş Yap

    </Link>

    <Link
        :href="route('register')"
        class="hidden rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-950/25 transition hover:bg-blue-500 sm:inline-flex lg:px-6 lg:py-3 lg:text-[15px]">

        <span class="lg:hidden">Deneme</span>
        <span class="hidden lg:inline">Ücretsiz Denemeyi Başlat</span>

    </Link>

</div>

</template>
