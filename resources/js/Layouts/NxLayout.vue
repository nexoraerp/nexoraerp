<script setup>
import Sidebar from '@/Components/Navigation/Sidebar.vue';
import Topbar from '@/Components/Navigation/Topbar.vue';
</script>

<template>

<div class="flex min-h-screen bg-slate-100">

    <Sidebar />

    <div class="flex-1 flex flex-col">

        <Topbar />

        <main class="flex-1 p-8">

            <slot />

        </main>

    </div>

</div>

</template>