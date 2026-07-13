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

<div class="hidden lg:flex items-center gap-4">

    <Link
        :href="route('login')"
        :class="[
            'rounded-md px-5 py-3 text-[15px] font-semibold transition',
            props.variant === 'light'
                ? 'text-slate-700 hover:bg-slate-100 hover:text-blue-700'
                : 'text-slate-200 hover:bg-white/10 hover:text-white'
        ]">

        Giriş Yap

    </Link>

    <Link
        :href="route('register')"
        class="rounded-md bg-blue-600 px-6 py-3 text-[15px] font-semibold text-white shadow-lg shadow-blue-950/25 transition hover:bg-blue-500">

        Ücretsiz Denemeyi Başlat

    </Link>

</div>

</template>
