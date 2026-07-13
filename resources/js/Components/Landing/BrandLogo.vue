<script setup>
defineProps({
    tone: {
        type: String,
        default: 'light',
    },
});
</script>

<template>
    <div class="select-none">
        <div
            :class="[
                'text-3xl font-black tracking-tight leading-none',
                tone === 'dark' ? 'text-white' : 'text-slate-900'
            ]"
        >
            ne<span class="text-blue-600">x</span>ora
        </div>

        <div
            :class="[
                'mt-1 text-[10px] uppercase tracking-[0.45em]',
                tone === 'dark' ? 'text-slate-400' : 'text-slate-500'
            ]"
        >
            ERP & MUHASEBE
        </div>
    </div>
</template>
