<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    canonical: {
        type: String,
        default: 'https://nexoraerp.com.tr',
    },
    image: {
        type: String,
        default: 'https://nexoraerp.com.tr/landing/custom/hero-reception.png',
    },
    type: {
        type: String,
        default: 'website',
    },
    robots: {
        type: String,
        default: 'index, follow',
    },
});

const fullTitle = computed(() => (
    props.title.includes('Nexora')
        ? props.title
        : `${props.title} | Nexora ERP`
));
</script>

<template>
    <Head>
        <title>{{ fullTitle }}</title>
        <meta
            name="description"
            :content="description"
        />
        <meta
            name="robots"
            :content="robots"
        />
        <link
            rel="canonical"
            :href="canonical"
        />

        <meta
            property="og:locale"
            content="tr_TR"
        />
        <meta
            property="og:type"
            :content="type"
        />
        <meta
            property="og:title"
            :content="fullTitle"
        />
        <meta
            property="og:description"
            :content="description"
        />
        <meta
            property="og:url"
            :content="canonical"
        />
        <meta
            property="og:site_name"
            content="Nexora ERP"
        />
        <meta
            property="og:image"
            :content="image"
        />

        <meta
            name="twitter:card"
            content="summary_large_image"
        />
        <meta
            name="twitter:title"
            :content="fullTitle"
        />
        <meta
            name="twitter:description"
            :content="description"
        />
        <meta
            name="twitter:image"
            :content="image"
        />
    </Head>
</template>
