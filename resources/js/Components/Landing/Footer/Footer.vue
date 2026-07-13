<script setup>
import FooterBrand from './FooterBrand.vue'
import FooterLinks from './FooterLinks.vue'
import FooterBottom from './FooterBottom.vue'
</script>

<template>

<footer class="bg-slate-950 text-white">

    <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">

        <div class="grid gap-16 lg:grid-cols-5">

            <div class="lg:col-span-2">

                <FooterBrand />

            </div>

            <div class="lg:col-span-3">

                <FooterLinks />

            </div>

        </div>

        <FooterBottom />

    </div>

</footer>

</template>