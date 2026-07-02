<script setup>
import { Head } from '@inertiajs/vue3';

import LandingLayout from '@/Layouts/LandingLayout.vue';

import Hero from '@/Components/Landing/Hero.vue';
import TrustBar from '@/Components/Landing/TrustBar.vue';
import Features from '@/Components/Landing/Features.vue';
import WhyNexora from '@/Components/Landing/WhyNexora.vue';
import Modules from '@/Components/Landing/Modules.vue';
import Pricing from '@/Components/Landing/Pricing.vue';
import Faq from '@/Components/Landing/Faq.vue';
import Cta from '@/Components/Landing/Cta.vue';
import Stats from '@/Components/Landing/Stats.vue';
import Footer from '@/Components/Landing/Footer.vue';
</script>

<template>

    <Head title="Nexora ERP" />

    <LandingLayout>

        <Hero />

        <TrustBar />

        <Features />

        <WhyNexora />

        <Modules />

        <Pricing />

        <Faq />

        <Cta />

        <Stats />

        <Footer />

    </LandingLayout>

</template>