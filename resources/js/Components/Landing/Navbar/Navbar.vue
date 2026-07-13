<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

import NavBrand from './Navbrand.vue'
import NavLinks from './NavLinks.vue'
import NavActions from './NavActions.vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
})

const scrolled = ref(false)

const handleScroll = () => {
    scrolled.value = window.scrollY > 40
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<template>
    <nav
        :class="[
            'fixed inset-x-0 z-50 mx-auto w-[95%] max-w-7xl transition-all duration-300 ease-out',
            scrolled ? 'top-3' : 'top-6'
        ]"
    >
        <div
            :class="[
                'flex items-center justify-between rounded-lg border transition-all duration-300 ease-out',
                props.variant === 'light'
                    ? (
                        scrolled
                            ? 'border-slate-200 bg-white/95 px-8 py-4 shadow-2xl shadow-slate-950/10 backdrop-blur-3xl'
                            : 'border-slate-200 bg-white/90 px-8 py-5 shadow-xl shadow-slate-950/10 backdrop-blur-xl'
                    )
                    : (
                        scrolled
                            ? 'border-white/10 bg-[#111a2d]/95 px-8 py-4 shadow-2xl shadow-black/25 backdrop-blur-3xl'
                            : 'border-white/10 bg-[#111a2d]/88 px-8 py-5 shadow-xl shadow-black/20 backdrop-blur-xl'
                    )
            ]"
        >
            <NavBrand :variant="props.variant" />

            <NavLinks :variant="props.variant" />

            <NavActions :variant="props.variant" />
        </div>
    </nav>
</template>
