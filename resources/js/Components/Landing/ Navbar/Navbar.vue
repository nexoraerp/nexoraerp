<script setup>
import NavLinks from "./NavLinks.vue";
import NavActions from "./NavActions.vue";
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    >
        <div class="mx-auto max-w-[1600px] px-6 lg:px-10">

            <nav
                class="mt-5 flex h-20 items-center justify-between rounded-2xl border border-slate-200/60 bg-white/80 px-8 backdrop-blur-xl shadow-[0_10px_40px_rgba(15,23,42,.06)]"
            >

                <!-- Logo -->

                <div class="flex items-center">

                    <img
                        src="/logo.svg"
                        alt="Nexora"
                        class="h-9 w-auto"
                    />

                </div>

                <!-- Menü -->

                <NavLinks />

                <!-- Sağ Alan -->

                <NavActions />

            </nav>

        </div>
    </header>
</template>