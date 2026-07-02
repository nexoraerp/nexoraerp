<script setup>
import HeroBackground from "./HeroBackground.vue";
import HeroContent from "./HeroContent.vue";
import HeroVisual from "./HeroVisual.vue";
</script>

<template>

<section
    class="relative overflow-hidden py-24">

    <HeroBackground />

    <div
        class="relative mx-auto max-w-7xl px-6">

        <div
            class="grid items-center gap-14 lg:grid-cols-2">

            <HeroContent />

            <HeroVisual />

        </div>

    </div>

</section>

</template>