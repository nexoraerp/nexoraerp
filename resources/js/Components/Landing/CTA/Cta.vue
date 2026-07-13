<script setup>
import CtaContent from './CtaContent.vue'
import CtaStats from './CtaStats.vue'
</script>

<template>

<section class="relative overflow-hidden py-24 lg:py-28">

    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div
            class="relative overflow-hidden rounded-lg
                   bg-slate-950
                   px-10
                   py-16
                   lg:px-14
                   lg:py-16
                   shadow-[0_30px_90px_rgba(37,99,235,.22)]">

            <CtaContent />

            <CtaStats />

        </div>

    </div>

</section>

</template>
