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
            class="group bg-white rounded-3xl border border-slate-200 p-7 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer h-full"
            :class="colors[color]?.border || colors.blue.border"
        >

            <div
                class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 group-hover:scale-110"
                :class="[
                    colors[color]?.bg || colors.blue.bg,
                    colors[color]?.text || colors.blue.text
                ]"
            >
                {{ icon }}
            </div>

            <div class="mt-6">

                <h2 class="text-xl font-bold text-slate-800">
                    {{ title }}
                </h2>

                <div
                    v-if="count !== ''"
                    class="text-5xl font-extrabold mt-6 text-slate-900 break-words"
                >
                    {{ count }}
                </div>

                <p
                    v-if="subtitle"
                    class="text-slate-500 mt-2"
                >
                    {{ subtitle }}
                </p>

            </div>

            <div class="mt-8 pt-5 border-t flex items-center justify-between">

                <span class="text-sm font-semibold text-slate-500">
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