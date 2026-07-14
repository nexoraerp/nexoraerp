<script setup>
import { ref } from 'vue'
import { Bot, X } from 'lucide-vue-next'

import AssistantPanel from './AssistantPanel.vue'

defineProps({
    briefing: {
        type: Object,
        default: () => null,
    },
})

const open = ref(false)
</script>

<template>

<div
    class="fixed right-4 z-[999] transition-[bottom] duration-200 sm:bottom-6 sm:right-6 lg:bottom-8 lg:right-8"
    :class="open ? 'bottom-20' : 'bottom-4'"
>

    <!-- AI Butonu -->

    <button
        v-if="!open"
        @click="open=true"
        class="group relative flex h-12 w-12 items-center justify-center rounded-full
               bg-gradient-to-r from-blue-600 to-indigo-600
               text-white shadow-2xl transition-all duration-300
               hover:scale-105 sm:h-16 sm:w-16"
    >

        <Bot class="h-6 w-6 sm:h-8 sm:w-8" />

        <span
            class="absolute -right-0.5 -top-0.5 h-4 w-4 animate-pulse rounded-full bg-green-500 ring-4 ring-white dark:ring-slate-950"
        />

    </button>

    <!-- Panel -->

    <Transition
        enter-active-class="transition duration-300"
        enter-from-class="opacity-0 translate-y-10 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 translate-y-10"
    >

        <div
            v-if="open"
            class="h-[min(560px,72vh)] w-[min(360px,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,.18)] dark:border-slate-800 dark:bg-slate-950 sm:h-[min(720px,calc(100vh-2rem))] sm:w-[430px] sm:rounded-3xl"
        >

            <AssistantPanel :briefing="briefing" />

            <button
                @click="open=false"
                class="absolute right-3 top-3 rounded-full bg-white p-2 text-slate-700 shadow hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 sm:right-4 sm:top-4"
            >

                <X class="h-4 w-4"/>

            </button>

        </div>

    </Transition>

</div>

</template>
