<script setup>
import LandingLayout from '@/Layouts/LandingLayout.vue';
import SeoHead from '@/Components/Seo/SeoHead.vue';

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

    <SeoHead
        title="Nexora ERP | Online Ön Muhasebe ve ERP Programı"
        description="Nexora ERP; satış, stok, cari, tahsilat, teklif, gider, rapor ve risk analizlerini tek panelde toplayan modern bulut ERP çözümüdür. 14 gün ücretsiz deneyin."
        canonical="https://nexoraerp.com.tr"
    />

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
