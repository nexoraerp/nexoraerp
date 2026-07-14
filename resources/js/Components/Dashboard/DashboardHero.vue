<script setup>
const today = new Date().toLocaleDateString('tr-TR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});
</script>

<template>

    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 text-slate-950 shadow-sm dark:border-slate-800 dark:bg-gradient-to-r
               dark:from-slate-900
               dark:via-slate-800
               dark:to-blue-900
               dark:text-white sm:p-8 lg:p-10"
    >

        <div class="flex items-start justify-between gap-4">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300 sm:text-sm sm:tracking-[0.35em]">
                    NEXORA ERP
                </p>

                <h1 class="mt-3 text-2xl font-black leading-tight sm:mt-4 sm:text-4xl">
                    İşletmenizin Genel Durumu
                </h1>

                <p class="mt-3 text-sm text-slate-500 dark:text-slate-300 sm:mt-4 sm:text-lg">
                    Tüm işletmenizi tek panelden yönetin.
                </p>

            </div>

            <div class="hidden shrink-0 text-right sm:block">

                <p class="text-slate-500 dark:text-blue-200">
                    {{ today }}
                </p>

                <span
                    class="inline-block mt-3 px-4 py-2 rounded-full
                           border border-slate-200 bg-slate-100 dark:border-white/20 dark:bg-white/10
                           text-sm text-slate-700 dark:text-white"
                >
                    Version 0.5
                </span>

            </div>

        </div>

    </div>

</template>
