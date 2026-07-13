<script setup>
import NavBrand from './NavBrand.vue'
import NavLinks from './NavLinks.vue'
import NavActions from './NavActions.vue'
</script>

<template>
    <nav class="fixed inset-x-0 top-5 z-50 mx-auto w-[95%] max-w-7xl">

        <div
            class="flex items-center justify-between rounded-3xl border border-white/60 bg-white/80 px-8 py-5 backdrop-blur-2xl shadow-[0_10px_40px_rgba(15,23,42,.08)]">

            <NavBrand />

            <NavLinks />

            <NavActions />

        </div>

    </nav>
</template>