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

<div class="fixed bottom-8 right-8 z-[999]">

    <!-- AI Butonu -->

    <button
        v-if="!open"
        @click="open=true"
        class="group relative flex h-16 w-16 items-center justify-center rounded-full
               bg-gradient-to-r from-blue-600 to-indigo-600
               text-white shadow-2xl transition-all duration-300
               hover:scale-110"
    >

        <Bot class="h-8 w-8" />

        <span
            class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-green-500 ring-4 ring-white animate-pulse"
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
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_30px_80px_rgba(15,23,42,.18)] w-[430px] h-[720px]"
        >

            <AssistantPanel :briefing="briefing" />

            <button
                @click="open=false"
                class="absolute top-4 right-4 rounded-full bg-white p-2 shadow hover:bg-slate-100"
            >

                <X class="h-4 w-4"/>

            </button>

        </div>

    </Transition>

</div>

</template>
