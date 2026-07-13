<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    company_name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Nexora ERP Deneme Hesabı" />

        <form @submit.prevent="submit">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">
                    14 Gün Ücretsiz Deneme
                </p>

                <h1 class="mt-3 text-3xl font-black text-slate-950">
                    Nexora ERP hesabınızı oluşturun
                </h1>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                    Cari, stok, satış, finans ve Nexora AI özelliklerini 14 gün boyunca deneyin.
                    Kayıt sonrası hesabınızı korumak için e-posta doğrulama adımına yönlendirileceksiniz.
                </p>
            </div>

            <div>
                <InputLabel for="name" value="Ad Soyad" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="company_name" value="Firma Adı" />

                <TextInput
                    id="company_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.company_name"
                    required
                    autocomplete="organization"
                />

                <InputError class="mt-2" :message="form.errors.company_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="E-posta" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Telefon" />

                <TextInput
                    id="phone"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    autocomplete="tel"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Şifre" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
                <p class="mt-2 text-xs leading-5 text-slate-500">
                    En az 8 karakter kullanın. Güçlü bir şifre için harf, rakam ve özel karakterleri birlikte tercih edin.
                </p>
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Şifre Tekrar"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Zaten hesabınız var mı?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Kayıt Ol ve Denemeyi Başlat
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
