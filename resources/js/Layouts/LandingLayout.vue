<script setup>
import { onBeforeUnmount, onMounted } from 'vue';

import Navbar from '@/Components/Landing/Navbar/Navbar.vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
});

let hadDarkMode = false;

onMounted(() => {
    hadDarkMode = document.documentElement.classList.contains('dark');
    document.documentElement.classList.remove('dark');
});

onBeforeUnmount(() => {
    if (hadDarkMode) {
        document.documentElement.classList.add('dark');
    }
});
</script>

<template>
    <div
        :class="[
            'min-h-screen overflow-x-hidden',
            props.variant === 'light' ? 'bg-white text-slate-950' : 'bg-[#0b1324] text-slate-100'
        ]"
    >

        <Navbar :variant="props.variant" />

        <main>
            <slot />
        </main>

    </div>
</template>
