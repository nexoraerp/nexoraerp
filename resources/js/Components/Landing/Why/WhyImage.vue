<script setup>
import LandingImageFrame from '../LandingImageFrame.vue';
</script>

<template>

<div class="relative">

    <!-- Fotoğraf -->

    <LandingImageFrame
        src="/landing/custom/dashboard.png"
        alt="Nexora yönetim paneli"
        body-class="h-[280px] sm:h-[380px] lg:h-[460px]"
        image-class="h-full w-full object-cover object-left-top"
    />

    <!-- Alt Bilgi -->

    <div
        class="absolute -bottom-8 left-8 right-8
               rounded-md
               border border-white/70
               bg-white/95
               p-6
               backdrop-blur-xl
               shadow-xl">

        <div class="text-lg font-bold text-slate-900">

            Veriler tek merkezde toplanır.

        </div>

        <p class="mt-2 text-slate-600 leading-7">

            Yönetim paneli; satış, finans, stok ve cari durumunu aynı ekranda izlenebilir hale getirir.

        </p>

    </div>

</div>

</template>
