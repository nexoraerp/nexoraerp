<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
        default: null,
    },
});

const page = usePage();
const form = useForm({});

const resendVerification = () => {
    form.post(route('verification.send'), {
        preserveScroll: true,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <GuestLayout>
        <Head title="E-posta Doğrulama" />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-600">
                Güvenli Hesap Aktivasyonu
            </p>

            <h1 class="mt-3 text-3xl font-black text-slate-950">
                E-posta adresinizi doğrulayın
            </h1>

            <p class="mt-3 text-sm leading-6 text-slate-500">
                Nexora ERP hesabınızı korumak için gönderdiğimiz doğrulama bağlantısını onaylayın.
                Doğrulama tamamlandıktan sonra dashboard ve ERP modüllerine erişebilirsiniz.
            </p>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-900">
            Doğrulama bağlantısı
            <strong>{{ page.props.auth.user.email }}</strong>
            adresine gönderildi.
        </div>

        <div
            v-if="status === 'verification-link-sent'"
            class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800"
        >
            Yeni doğrulama bağlantısı e-posta adresinize gönderildi.
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="resendVerification"
            >
                Doğrulama Mailini Tekrar Gönder
            </PrimaryButton>

            <button
                type="button"
                class="rounded-md px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
                @click="logout"
            >
                Çıkış Yap
            </button>
        </div>

        <div class="mt-8 border-t border-slate-200 pt-5">
            <Link
                :href="route('landing')"
                class="text-sm font-semibold text-blue-700 hover:text-blue-900"
            >
                Ana sayfaya dön
            </Link>
        </div>
    </GuestLayout>
</template>
