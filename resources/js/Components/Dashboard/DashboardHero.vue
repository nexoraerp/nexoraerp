<script setup>
const today = new Date().toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});
</script>

<template>

    <div
        class="rounded-3xl overflow-hidden bg-gradient-to-r
               from-slate-900
               via-slate-800
               to-blue-900
               p-10
               shadow-xl text-white"
    >

        <div class="flex items-center justify-between">

            <div>

                <p class="uppercase tracking-[0.35em] text-blue-300 text-sm">
                    NEXORA ERP
                </p>

                <h1 class="text-4xl font-black mt-4">
                    İşletmenizin Genel Durumu
                </h1>

                <p class="mt-4 text-slate-300 text-lg">
                    Tüm işletmenizi tek panelden yönetin.
                </p>

            </div>

            <div class="hidden lg:block text-right">

                <div class="text-7xl">
                    🚀
                </div>

                <p class="mt-4 text-blue-200">
                    {{ today }}
                </p>

                <span
                    class="inline-block mt-3 px-4 py-2 rounded-full
                           bg-white/10 border border-white/20
                           text-sm"
                >
                    Version 0.5
                </span>

            </div>

        </div>

    </div>

</template>