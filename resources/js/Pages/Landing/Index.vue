<script setup>
import { Head } from '@inertiajs/vue3';

import LandingLayout from '@/Layouts/LandingLayout.vue';

import Hero from '@/Components/Landing/Hero/Hero.vue';
import Why from '@/Components/Landing/Why/Why.vue';
import Showcase from '@/Components/Landing/Showcase/Showcase.vue';
</script>

<template>

    <Head title="Nexora ERP" />

    <LandingLayout>

        <Hero />

        <Why />

        <Showcase />

    </LandingLayout>

</template>