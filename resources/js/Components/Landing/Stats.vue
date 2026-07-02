<script setup>
const stats = [
    {
        value: '500+',
        label: 'Aktif İşletme',
        description: 'Türkiye genelinde Nexora kullanan firmalar',
    },
    {
        value: '50.000+',
        label: 'Oluşturulan Fatura',
        description: 'Platform üzerinden oluşturulan işlem sayısı',
    },
    {
        value: '%99.9',
        label: 'Sistem Erişilebilirliği',
        description: 'Kesintisiz ve güvenli bulut altyapısı',
    },
    {
        value: '7/24',
        label: 'Teknik Destek',
        description: 'Uzman ekibimiz her zaman yanınızda',
    },
];
</script>

<template>

<section class="relative bg-slate-50 py-24">

    <div class="mx-auto max-w-7xl px-6">

        <!-- Başlık -->

        <div class="mx-auto max-w-3xl text-center">

            <span
                class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
            >
                Nexora Rakamlarla
            </span>

            <h2
                class="mt-6 text-4xl font-black tracking-tight text-slate-900 lg:text-5xl"
            >
                İşletmelerin Güvendiği
                <span class="text-blue-600">
                    ERP Platformu
                </span>
            </h2>

            <p
                class="mt-5 text-lg leading-8 text-slate-600"
            >
                Güçlü altyapımız ve kullanıcı odaklı yaklaşımımız ile
                işletmelerin dijital dönüşümüne katkı sağlıyoruz.
            </p>

        </div>

        <!-- İstatistikler -->

        <div
            class="mt-16 grid gap-6 sm:grid-cols-2 xl:grid-cols-4"
        >

            <div
                v-for="item in stats"
                :key="item.label"
                class="group rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm transition duration-300 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl"
            >

                <div
                    class="text-5xl font-black text-blue-600 transition group-hover:scale-105"
                >
                    {{ item.value }}
                </div>

                <h3
                    class="mt-5 text-lg font-bold text-slate-900"
                >
                    {{ item.label }}
                </h3>

                <p
                    class="mt-3 text-sm leading-7 text-slate-500"
                >
                    {{ item.description }}
                </p>

            </div>

        </div>

    </div>

</section>

</template>