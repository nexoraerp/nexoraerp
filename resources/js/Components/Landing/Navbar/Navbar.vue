<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Menu, X } from 'lucide-vue-next'

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
const mobileMenuOpen = ref(false)

const mobileLinks = [
    { title: 'Özellikler', routeName: 'landing.features' },
    { title: 'Modüller', routeName: 'landing.modules' },
    { title: 'Çözümler', routeName: 'landing.solutions' },
    { title: 'Fiyatlar', routeName: 'landing.pricing' },
    { title: 'Bilgi Formu', href: '/#bilgi-formu' },
    { title: 'İletişim', routeName: 'landing.contact' },
]

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
                            ? 'border-slate-200 bg-white/95 px-4 py-3 shadow-2xl shadow-slate-950/10 backdrop-blur-3xl sm:px-6 lg:px-8 lg:py-4'
                            : 'border-slate-200 bg-white/90 px-4 py-3 shadow-xl shadow-slate-950/10 backdrop-blur-xl sm:px-6 lg:px-8 lg:py-5'
                    )
                    : (
                        scrolled
                            ? 'border-white/10 bg-[#111a2d]/95 px-4 py-3 shadow-2xl shadow-black/25 backdrop-blur-3xl sm:px-6 lg:px-8 lg:py-4'
                            : 'border-white/10 bg-[#111a2d]/88 px-4 py-3 shadow-xl shadow-black/20 backdrop-blur-xl sm:px-6 lg:px-8 lg:py-5'
                    )
            ]"
        >
            <NavBrand :variant="props.variant" />

            <NavLinks :variant="props.variant" />

            <div class="flex items-center gap-2">
                <NavActions :variant="props.variant" />

                <button
                    type="button"
                    :class="[
                        'inline-flex h-10 w-10 items-center justify-center rounded-md border transition lg:hidden',
                        props.variant === 'light'
                            ? 'border-slate-200 text-slate-700 hover:bg-slate-100'
                            : 'border-white/10 text-slate-100 hover:bg-white/10'
                    ]"
                    :aria-label="mobileMenuOpen ? 'Mobil menüyü kapat' : 'Mobil menüyü aç'"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <X
                        v-if="mobileMenuOpen"
                        class="h-5 w-5"
                    />
                    <Menu
                        v-else
                        class="h-5 w-5"
                    />
                </button>
            </div>
        </div>

        <div
            v-if="mobileMenuOpen"
            :class="[
                'mt-2 rounded-lg border p-3 shadow-xl backdrop-blur-xl lg:hidden',
                props.variant === 'light'
                    ? 'border-slate-200 bg-white/95 shadow-slate-950/10'
                    : 'border-white/10 bg-[#111a2d]/95 shadow-black/20'
            ]"
        >
            <Link
                v-for="link in mobileLinks"
                :key="link.title"
                :href="link.href ?? route(link.routeName)"
                :class="[
                    'block rounded-md px-4 py-3 text-sm font-semibold transition',
                    props.variant === 'light'
                        ? 'text-slate-700 hover:bg-slate-100 hover:text-blue-700'
                        : 'text-slate-200 hover:bg-white/10 hover:text-white'
                ]"
                @click="mobileMenuOpen = false"
            >
                {{ link.title }}
            </Link>
        </div>
    </nav>
</template>
