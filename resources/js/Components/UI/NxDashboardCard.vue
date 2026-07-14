<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },

    subtitle: {
        type: String,
        default: '',
    },

    count: {
        type: [Number, String],
        default: '',
    },

    href: {
        type: String,
        default: '#',
    },

    icon: {
        type: String,
        default: '📦',
    },

    color: {
        type: String,
        default: 'blue',
    },
});

const colors = {

    blue: {
        bg: 'bg-blue-50',
        text: 'text-blue-600',
        border: 'hover:border-blue-500',
    },

    emerald: {
        bg: 'bg-emerald-50',
        text: 'text-emerald-600',
        border: 'hover:border-emerald-500',
    },

    green: {
        bg: 'bg-green-50',
        text: 'text-green-600',
        border: 'hover:border-green-500',
    },

    cyan: {
        bg: 'bg-cyan-50',
        text: 'text-cyan-600',
        border: 'hover:border-cyan-500',
    },

    purple: {
        bg: 'bg-purple-50',
        text: 'text-purple-600',
        border: 'hover:border-purple-500',
    },

    orange: {
        bg: 'bg-orange-50',
        text: 'text-orange-600',
        border: 'hover:border-orange-500',
    },

    red: {
        bg: 'bg-red-50',
        text: 'text-red-600',
        border: 'hover:border-red-500',
    },

    lime: {
        bg: 'bg-lime-50',
        text: 'text-lime-600',
        border: 'hover:border-lime-500',
    },

    indigo: {
        bg: 'bg-indigo-50',
        text: 'text-indigo-600',
        border: 'hover:border-indigo-500',
    },

    rose: {
        bg: 'bg-rose-50',
        text: 'text-rose-600',
        border: 'hover:border-rose-500',
    },

    amber: {
        bg: 'bg-amber-50',
        text: 'text-amber-600',
        border: 'hover:border-amber-500',
    },

};

</script>

<template>

    <Link :href="href">

        <div
            class="group h-full cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-950 sm:p-7 lg:hover:-translate-y-2"
            :class="colors[color]?.border || colors.blue.border"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-2xl text-2xl transition-all duration-300 group-hover:scale-110 sm:h-16 sm:w-16 sm:text-3xl"
                :class="[
                    colors[color]?.bg || colors.blue.bg,
                    colors[color]?.text || colors.blue.text
                ]"
            >
                {{ icon }}
            </div>

            <div class="mt-4 sm:mt-6">

                <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 sm:text-xl">
                    {{ title }}
                </h2>

                <div
                    v-if="count !== ''"
                    class="mt-3 break-words text-2xl font-black text-slate-900 dark:text-slate-100 sm:mt-6 sm:text-5xl"
                >
                    {{ count }}
                </div>

                <p
                    v-if="subtitle"
                    class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400 sm:mt-2 sm:text-base"
                >
                    {{ subtitle }}
                </p>

            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4 dark:border-slate-800 sm:mt-8 sm:pt-5">

                <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                    Yönet
                </span>

                <span
                    class="text-2xl transition-all duration-300 group-hover:translate-x-2"
                    :class="colors[color]?.text || colors.blue.text"
                >
                    →
                </span>

            </div>

        </div>

    </Link>

</template>
