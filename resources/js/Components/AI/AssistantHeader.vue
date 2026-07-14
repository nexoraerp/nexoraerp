<script setup>
import { Bot } from 'lucide-vue-next'
</script>

<template>

<div
    class="border-b bg-gradient-to-r
           from-blue-600
           via-indigo-600
           to-sky-600
           px-4 py-3 text-white sm:px-6 sm:py-5"
>

    <div class="flex items-center gap-3 sm:gap-4">

        <div
            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 backdrop-blur sm:h-12 sm:w-12"
        >

            <Bot class="h-5 w-5 sm:h-7 sm:w-7"/>

        </div>

        <div>

            <h2 class="text-base font-bold sm:text-lg">

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
