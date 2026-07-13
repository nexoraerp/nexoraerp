<template>
    <div class="relative flex items-center justify-center lg:justify-end">

        <!-- Banner -->
        <div
            class="relative z-10 w-full max-w-[860px] overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-[0_30px_90px_rgba(15,23,42,.14)] transition-all duration-500 hover:-translate-y-1">

            <img
                src="/landing/custom/hero-reception.png"
                alt="Nexora karşılama alanı"
                class="h-[300px] w-full object-cover object-center sm:h-[430px] lg:h-[520px]"
                loading="eager"
            >

        </div>

        <!-- Günlük Satış -->
        <div
            class="absolute left-0 top-16 z-20
                   rounded-md
                   border border-white/70
                   bg-white/95
                   px-6 py-5
                   backdrop-blur-xl
                   shadow-2xl">

            <div class="text-xs text-slate-500">
                Günlük satış
            </div>

            <div class="mt-2 text-3xl font-black text-slate-900">
                ₺48.920
            </div>

            <div class="mt-1 text-sm font-medium text-emerald-600">
                +18.4%
            </div>

        </div>

        <!-- Aktif Müşteri -->
        <div
            class="absolute right-0 bottom-14 z-20
                   rounded-md
                   border border-white/70
                   bg-white/95
                   px-6 py-5
                   backdrop-blur-xl
                   shadow-2xl">

            <div class="text-xs text-slate-500">
                Aktif Müşteri
            </div>

            <div class="mt-2 text-3xl font-black text-slate-900">
                1.284
            </div>

            <div class="mt-1 text-sm font-medium text-blue-600">
                Canlı
            </div>

        </div>

    </div>
</template>
