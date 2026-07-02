<script setup>
import { useShowcase } from "./useShowcase";

const { current } = useShowcase();
</script>

<template>

<Transition
    mode="out-in"
    enter-active-class="duration-500 ease-out"
    enter-from-class="opacity-0 translate-y-5 scale-[.98]"
    enter-to-class="opacity-100 translate-y-0 scale-100"
    leave-active-class="duration-300 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
>

<div
    :key="current.id"
    class="overflow-hidden rounded-[32px]
           border border-slate-200
           bg-white
           shadow-[0_30px_90px_rgba(15,23,42,.08)]">

    <img
        :src="current.image"
        :alt="current.title"
        class="w-full select-none"
    >

    <div class="border-t border-slate-200 p-8">

        <h3
            class="text-2xl font-bold text-slate-900">

            {{ current.title }}

        </h3>

        <p
            class="mt-3 text-lg leading-8 text-slate-600">

            {{ current.description }}

        </p>

    </div>

</div>

</Transition>

</template>