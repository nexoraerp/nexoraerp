<script setup>
import { Link } from '@inertiajs/vue3';

const groups = [
    {
        title: 'Ürün',
        links: [
            { label: 'Özellikler', routeName: 'landing.features' },
            { label: 'Modüller', routeName: 'landing.modules' },
            { label: 'Fiyatlandırma', routeName: 'landing.pricing' },
            { label: 'Ücretsiz Deneme', routeName: 'register' },
        ],
    },
    {
        title: 'Şirket',
        links: [
            { label: 'Hakkımızda', routeName: 'landing.about' },
            { label: 'Blog', routeName: 'landing.blog' },
            { label: 'İletişim', routeName: 'landing.contact' },
        ],
    },
    {
        title: 'Destek',
        links: [
            { label: 'SSS', routeName: 'landing.faq' },
            { label: 'Dokümantasyon', routeName: 'landing.documentation' },
            { label: 'Destek Merkezi', routeName: 'landing.support' },
        ],
    },
    {
        title: 'Yasal',
        links: [
            { label: 'Kullanıcı Sözleşmesi', routeName: 'landing.terms' },
            { label: 'Açık Rıza Metni', routeName: 'landing.explicit-consent' },
            { label: 'KVKK', routeName: 'landing.kvkk' },
            { label: 'Gizlilik', routeName: 'landing.privacy' },
            { label: 'Çerez Politikası', routeName: 'landing.cookies' },
        ],
    },
];
</script>

<template>

<div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

    <div
        v-for="group in groups"
        :key="group.title"
    >
        <h3 class="font-bold">
            {{ group.title }}
        </h3>

        <ul class="mt-5 space-y-3 text-slate-400">
            <li
                v-for="link in group.links"
                :key="link.label"
            >
                <Link
                    :href="route(link.routeName)"
                    class="transition hover:text-white"
                >
                    {{ link.label }}
                </Link>
            </li>
        </ul>
    </div>

</div>

</template>
