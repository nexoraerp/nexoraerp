<script setup>
defineProps({
    src: {
        type: String,
        required: true,
    },

    alt: {
        type: String,
        required: true,
    },

    imageClass: {
        type: String,
        default: 'h-full w-full object-cover object-left-top',
    },

    bodyClass: {
        type: String,
        default: 'h-[260px] sm:h-[360px] lg:h-[430px]',
    },
});
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,.10)]">
        <div class="flex h-10 items-center gap-2 border-b border-slate-200 bg-slate-50 px-4">
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            <div class="ml-3 h-2 w-28 rounded-full bg-slate-200"></div>
        </div>

        <div :class="['overflow-hidden bg-slate-100', bodyClass]">
            <img
                :src="src"
                :alt="alt"
                :class="imageClass"
                loading="lazy"
            >
        </div>
    </div>
</template>
