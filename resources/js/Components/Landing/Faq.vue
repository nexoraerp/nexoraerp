<script setup>
import { ref } from 'vue';

const faqs = [
    {
        question: 'Verilerim güvende mi?',
        answer: 'Evet. Tüm verileriniz güvenli bulut altyapısında saklanır, düzenli olarak yedeklenir ve modern güvenlik standartlarıyla korunur.',
    },
    {
        question: 'Kurulum yapmam gerekiyor mu?',
        answer: 'Hayır. Nexora tamamen bulut tabanlıdır. Herhangi bir sunucu kurulumu veya teknik altyapı gerektirmez. Hesabınızı oluşturduktan sonra dakikalar içinde kullanmaya başlayabilirsiniz.',
    },
    {
        question: 'İlk ödemeyi gerçekten 2 ay sonra mı yapacağım?',
        answer: 'Evet. Hesabınızı hemen oluşturabilir, sistemi kullanmaya başlayabilir ve ilk ödemenizi 2 ay sonra gerçekleştirebilirsiniz.',
    },
    {
        question: 'Destek hizmeti sunuyor musunuz?',
        answer: 'Evet. Kurulumdan günlük kullanıma kadar ihtiyaç duyduğunuz her konuda teknik destek ekibimiz yanınızdadır.',
    },
    {
        question: 'İstediğim zaman aboneliğimi iptal edebilir miyim?',
        answer: 'Evet. Uzun dönemli taahhüt zorunluluğu yoktur. Dilediğiniz zaman aboneliğinizi iptal edebilirsiniz.',
    },
];

const active = ref(0);

const toggle = (index) => {
    active.value = active.value === index ? -1 : index;
};
</script>

<template>

<section
    id="faq"
    class="bg-white py-28"
>

    <div class="mx-auto max-w-4xl px-6">

        <div class="text-center">

            <span
                class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
            >
                Sık Sorulan Sorular
            </span>

            <h2
                class="mt-6 text-5xl font-black text-slate-900"
            >
                Aklınıza Takılan Her Şey
            </h2>

            <p
                class="mt-6 text-lg leading-8 text-slate-600"
            >
                Nexora hakkında en çok merak edilen soruları sizin için cevapladık.
            </p>

        </div>

        <div class="mt-16 space-y-4">

            <div
                v-for="(faq,index) in faqs"
                :key="index"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition"
            >

                <button
                    @click="toggle(index)"
                    class="flex w-full items-center justify-between px-6 py-5 text-left"
                >

                    <span
                        class="text-lg font-semibold text-slate-900"
                    >
                        {{ faq.question }}
                    </span>

                    <span
                        class="text-2xl text-blue-600"
                    >
                        {{ active === index ? '−' : '+' }}
                    </span>

                </button>

                <div
                    v-if="active === index"
                    class="border-t border-slate-100 px-6 py-5 text-slate-600 leading-8"
                >
                    {{ faq.answer }}
                </div>

            </div>

        </div>

    </div>

</section>

</template>