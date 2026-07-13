<script setup>
import { Link } from '@inertiajs/vue3';

import BrandLogo from '../BrandLogo.vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
});
</script>

<template>
    <Link :href="route('landing')" class="block">
        <BrandLogo :tone="props.variant === 'light' ? 'light' : 'dark'" />
    </Link>
</template>
