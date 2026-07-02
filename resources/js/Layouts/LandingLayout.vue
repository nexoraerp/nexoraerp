<script setup>
import Navbar from '@/Components/Landing/Navbar/Navbar.vue';
</script>

<template>

<div class="min-h-screen bg-slate-50 text-slate-900 overflow-x-hidden">

    <Navbar />

    <main>

        <slot />

    </main>

</div>

</template>