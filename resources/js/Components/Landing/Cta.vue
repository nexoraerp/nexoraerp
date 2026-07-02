<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

<section
    class="relative overflow-hidden py-28"
>

    <!-- Background -->

    <div
        class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-slate-900"
    ></div>

    <div
        class="absolute -top-32 left-0 h-96 w-96 rounded-full bg-white/10 blur-3xl"
    ></div>

    <div
        class="absolute bottom-0 right-0 h-[30rem] w-[30rem] rounded-full bg-blue-300/10 blur-3xl"
    ></div>

    <!-- Content -->

    <div
        class="relative mx-auto max-w-5xl px-6 text-center text-white"
    >

        <span
            class="rounded-full border border-white/20 bg-white/10 px-5 py-2 text-sm font-semibold backdrop-blur"
        >
            Bugün başlayın • İlk ödemenizi 2 ay sonra yapın
        </span>

        <h2
            class="mt-8 text-5xl font-black leading-tight lg:text-6xl"
        >
            İşletmenizi
            <br>

            Dijitalleştirmeye Hazır mısınız?
        </h2>

        <p
            class="mx-auto mt-8 max-w-3xl text-xl leading-9 text-blue-100"
        >
            Nexora ile satış, stok, finans ve müşteri yönetimini
            tek platform üzerinden yönetin.
            Hesabınızı oluşturun, dakikalar içinde kullanmaya başlayın.
        </p>

        <div
            class="mt-12 flex flex-wrap justify-center gap-5"
        >

            <Link
                href="#"
                class="rounded-2xl bg-white px-10 py-5 text-lg font-bold text-blue-700 transition hover:-translate-y-1 hover:shadow-2xl"
            >
                Hemen Hesap Oluştur
            </Link>

            <Link
                href="#pricing"
                class="rounded-2xl border border-white/30 px-10 py-5 text-lg font-semibold text-white transition hover:bg-white/10"
            >
                Fiyatlandırmayı İncele
            </Link>

        </div>

    </div>

</section>

</template>