<script setup>
import { Link } from '@inertiajs/vue3';

const links = [
    { title: 'Özellikler', routeName: 'landing.features' },
    { title: 'Modüller', routeName: 'landing.modules' },
    { title: 'Çözümler', routeName: 'landing.solutions' },
    { title: 'Fiyatlar', routeName: 'landing.pricing' },
    { title: 'İletişim', routeName: 'landing.contact' },
];

const props = defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
});
</script>

<template>

<div class="hidden lg:flex items-center gap-10">

    <Link
        v-for="link in links"
        :key="link.title"
        :href="route(link.routeName)"
        :class="[
            'text-[15px] font-semibold transition',
            props.variant === 'light'
                ? 'text-slate-600 hover:text-blue-700'
                : 'text-slate-200 hover:text-white'
        ]"
    >
        {{ link.title }}
    </Link>

</div>

</template>
