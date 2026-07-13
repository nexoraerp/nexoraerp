<script setup>
import { Bot } from 'lucide-vue-next'
</script>

<template>

<div
    class="border-b bg-gradient-to-r
           from-blue-600
           via-indigo-600
           to-sky-600
           px-6 py-5 text-white"
>

    <div class="flex items-center gap-4">

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 backdrop-blur"
        >

            <Bot class="h-7 w-7"/>

        </div>

        <div>

            <h2 class="font-bold text-lg">

                Nexora AI Assistant

            </h2>

            <div class="flex items-center gap-2">

                <div class="h-2 w-2 rounded-full bg-green-400 animate-pulse"/>

                <span class="text-xs text-blue-100">

                    Online • GPT Destekli

                </span>

            </div>

        </div>

    </div>

</div>

</template>