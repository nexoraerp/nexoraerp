<script setup>
import HeroBackground from './HeroBackground.vue'
import HeroContent from './HeroContent.vue'
import HeroVisual from './HeroVisual.vue'
</script>

<template>
    <section class="relative overflow-hidden bg-[#0b1324]">

        <HeroBackground />

        <div class="relative mx-auto max-w-7xl px-6 pb-20 pt-40 lg:px-8 lg:pb-24 lg:pt-44">

            <div
                class="grid min-h-[78vh] items-center gap-14 lg:grid-cols-[0.9fr_1.1fr]">

                <HeroContent />

                <HeroVisual />

            </div>

        </div>

    </section>
</template>
