<script setup>
import WhyContent from "./WhyContent.vue";
import WhyFeatures from "./WhyFeatures.vue";
import WhyImage from "./WhyImage.vue";
</script>

<template>

<section class="relative overflow-hidden bg-white py-32">

    <!-- Arka Plan Glow -->

    <div
        class="absolute left-0 top-20 h-[500px] w-[500px]
               rounded-full bg-blue-100/50 blur-[140px]">
    </div>

    <div
        class="absolute right-0 bottom-0 h-[450px] w-[450px]
               rounded-full bg-indigo-100/40 blur-[140px]">
    </div>

    <div class="relative mx-auto max-w-7xl px-6">

        <!-- Başlık -->

        <WhyContent />

        <!-- İçerik -->

        <div
            class="mt-24 grid items-center gap-20 lg:grid-cols-2">

            <WhyFeatures />

            <WhyImage />

        </div>

    </div>

</section>

</template>