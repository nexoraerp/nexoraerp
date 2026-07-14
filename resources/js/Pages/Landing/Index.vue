<script setup>
import { Head } from '@inertiajs/vue3';

import LandingLayout from '@/Layouts/LandingLayout.vue';

import Hero from '@/Components/Landing/Hero/Hero.vue';
import ProductHero from '@/Components/Landing/ProductHero/ProductHero.vue';
import Why from '@/Components/Landing/Why/Why.vue';
import Showcase from '@/Components/Landing/Showcase/Showcase.vue';
import Intelligence from '@/Components/Landing/Intelligence/Intelligence.vue';
import Pricing from '@/Components/Landing/Pricing/Pricing.vue';
import Faq from '@/Components/Landing/Faq/Faq.vue';
import LeadForm from '@/Components/Landing/LeadForm.vue';
import Cta from '@/Components/Landing/CTA/Cta.vue';
import Footer from '@/Components/Landing/Footer/Footer.vue';
</script>

<template>

    <Head title="Nexora ERP" />

    <LandingLayout>

        <!-- Hero -->
        <Hero />

        <!-- Premium Ürün Tanıtımı -->
        <ProductHero />

        <!-- Neden Nexora -->
        <Why />

        <!-- Modül Gösterimi -->
        <Showcase />

        <!-- Akıllı Raporlama -->
        <Intelligence />

        <!-- Fiyatlandırma -->
        <Pricing />

        <!-- Sık Sorulan Sorular -->
        <Faq />

        <!-- Bilgi Talebi -->
        <LeadForm />

        <!-- Son Çağrı -->
        <Cta />

        <!-- Footer -->
        <Footer />

    </LandingLayout>

</template>
