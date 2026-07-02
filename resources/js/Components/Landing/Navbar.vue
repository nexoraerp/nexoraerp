<script setup>
import { Link } from '@inertiajs/vue3';

const menus = [
    {
        title: 'Özellikler',
        href: '#features',
    },
    {
        title: 'Modüller',
        href: '#modules',
    },
    {
        title: 'Paketler',
        href: '#pricing',
    },
    {
        title: 'SSS',
        href: '#faq',
    },
    {
        title: 'İletişim',
        href: '#contact',
    },
];
</script>

<template>

<header
    class="fixed inset-x-0 top-0 z-50"
>

    <div class="mx-auto max-w-7xl px-6">

        <div
            class="mt-5 rounded-2xl border border-white/50 bg-white/80 backdrop-blur-xl shadow-lg"
        >

            <div
                class="flex h-20 items-center justify-between px-8"
            >

                <!-- Logo -->

                <Link
                    href="/"
                    class="flex items-center gap-3"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white font-bold text-lg shadow-md"
                    >
                        N
                    </div>

                    <div>

                        <h1
                            class="text-xl font-black tracking-tight text-slate-900"
                        >
                            NEXORA
                        </h1>

                        <p
                            class="-mt-1 text-[11px] uppercase tracking-[0.25em] text-slate-400"
                        >
                            Business OS
                        </p>

                    </div>

                </Link>

                <!-- Menü -->

                <nav
                    class="hidden lg:flex items-center gap-10"
                >

                    <a
                        v-for="menu in menus"
                        :key="menu.title"
                        :href="menu.href"
                        class="text-[15px] font-medium text-slate-600 transition hover:text-blue-600"
                    >
                        {{ menu.title }}
                    </a>

                </nav>

                <!-- Butonlar -->

                <div
                    class="hidden lg:flex items-center gap-3"
                >

                    <Link
                        :href="route('login')"
                        class="rounded-xl px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100"
                    >
                        Giriş Yap
                    </Link>

                    <button
                        class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
                    >
                        Demo Talep Et
                    </button>

                </div>

                <!-- Mobil -->

                <button
                    class="lg:hidden rounded-xl border p-3"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                    </svg>

                </button>

            </div>

        </div>

    </div>

</header>

</template>