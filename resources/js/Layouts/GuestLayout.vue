
<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { gsap } from 'gsap';

onMounted(() => {
    gsap.from('.hero', {
        opacity: 0,
        x: -80,
        duration: 1,
        ease: 'power3.out'
    });

    gsap.from('.login-card', {
        opacity: 0,
        x: 80,
        duration: 1,
        delay: 0.2,
        ease: 'power3.out'
    });
});
</script>

<template>
    <div class="relative min-h-screen bg-slate-100 flex items-center justify-center overflow-hidden">

        <!-- Arka Plan Animasyonu -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="blob blob1"></div>
            <div class="blob blob2"></div>
        </div>

        <!-- Ana Kart -->
        <div class="relative w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden grid md:grid-cols-2">

            <!-- Sol Taraf -->
        <div class="hero bg-slate-900 text-white p-16 flex flex-col justify-center">
                <Link href="/" class="text-4xl font-extrabold tracking-widest">
                    NEXORA
                </Link>

                <h2 class="mt-6 text-3xl font-bold">
                    Modern Business Platform
                </h2>

                <p class="mt-6 text-slate-300 leading-8">
                    Cari hesap, stok, satış, finans ve muhasebe
                    süreçlerinizi tek platformdan yönetin.
                </p>

                <div class="mt-12 text-sm text-slate-400">
                    © 2026 Nexora ERP
                </div>

            </div>

            <!-- Sağ Taraf -->
           <div class="login-card p-14 flex items-center">
                <div class="w-full">
                    <slot />
                </div>
            </div>

        </div>

    </div>
</template>
<style scoped>

.blob{
    position:absolute;
    width:350px;
    height:350px;
    border-radius:9999px;
    filter:blur(100px);
    opacity:.25;
    animation:float 12s ease-in-out infinite;
}

.blob1{
    background:#2563eb;
    top:-120px;
    left:-120px;
}

.blob2{
    background:#06b6d4;
    bottom:-120px;
    right:-120px;
    animation-delay:4s;
}

@keyframes float{
    0%,100%{
        transform:translate(0,0);
    }

    50%{
        transform:translate(40px,-40px);
    }
}

</style>