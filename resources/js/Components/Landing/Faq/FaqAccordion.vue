<script setup>
import { ref } from 'vue'
import { faqs } from './faqData'

const openIndex = ref(0)

const toggle = (index) => {
    openIndex.value = openIndex.value === index ? null : index
}
</script>

<template>

<div class="mt-16 space-y-5">

    <div
        v-for="(faq,index) in faqs"
        :key="index"
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white transition-all duration-300 hover:border-blue-300 hover:shadow-lg">

        <button
            @click="toggle(index)"
            class="flex w-full items-center justify-between px-8 py-6 text-left">

            <span
                class="text-xl font-semibold text-slate-900">

                {{ faq.question }}

            </span>

            <div
                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 transition duration-300"
                :class="{
                    'rotate-45 bg-blue-600 text-white': openIndex === index
                }">

                +

            </div>

        </button>

        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-96"
            leave-from-class="opacity-100 max-h-96"
            leave-to-class="opacity-0 max-h-0">

            <div
                v-if="openIndex === index"
                class="px-8 pb-8 text-lg leading-8 text-slate-600">

                {{ faq.answer }}

            </div>

        </Transition>

    </div>

</div>

</template>