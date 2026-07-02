<script setup>
import { useShowcase } from "./useShowcase";

const { active, showcaseItems } = useShowcase();
</script>

<template>
    <div class="flex justify-center">
        <div
            class="inline-flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">

            <button
                v-for="item in showcaseItems"
                :key="item.id"
                @click="active = item.id"
                :class="[
                    'rounded-xl px-6 py-3 text-sm font-semibold transition-all duration-300',
                    active === item.id
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'text-slate-600 hover:bg-slate-100'
                ]">

                {{ item.title }}

            </button>

        </div>
    </div>
</template>