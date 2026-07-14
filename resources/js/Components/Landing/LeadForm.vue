<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    company_name: '',
    phone: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post(route('lead-requests.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <section
        id="bilgi-formu"
        class="bg-white py-20"
    >
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-8">
                <p class="text-sm font-bold uppercase tracking-[0.22em] text-blue-700">
                    Bilgi Talebi
                </p>

                <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                    Sizi arayalım, Nexora ERP'yi birlikte netleştirelim.
                </h2>

                <p class="mt-5 text-base leading-8 text-slate-600">
                    Paket seçimi, geçiş süreci, demo ve kurulum sorularınız için form bırakın.
                    Talebiniz admin panelimize düşer ve ekibimiz sizinle iletişime geçer.
                </p>

                <div class="mt-8 grid gap-3 text-sm font-semibold text-slate-700">
                    <div class="rounded-md border border-slate-200 bg-white px-4 py-3">✓ Satış öncesi ücretsiz danışma</div>
                    <div class="rounded-md border border-slate-200 bg-white px-4 py-3">✓ Firmanıza uygun paket önerisi</div>
                    <div class="rounded-md border border-slate-200 bg-white px-4 py-3">✓ ERP geçiş süreci planlaması</div>
                </div>
            </div>

            <form
                class="rounded-lg border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8"
                @submit.prevent="submit"
            >
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Ad Soyad</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm font-semibold outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            placeholder="Adınız soyadınız"
                        />
                        <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Firma</label>
                        <input
                            v-model="form.company_name"
                            type="text"
                            class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm font-semibold outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            placeholder="Firma adı"
                        />
                        <p v-if="form.errors.company_name" class="text-sm text-red-600">{{ form.errors.company_name }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Telefon</label>
                        <input
                            v-model="form.phone"
                            type="tel"
                            class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm font-semibold outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            placeholder="05xx xxx xx xx"
                        />
                        <p v-if="form.errors.phone" class="text-sm text-red-600">{{ form.errors.phone }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">E-posta</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm font-semibold outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            placeholder="ornek@firma.com"
                        />
                        <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <label class="text-sm font-bold text-slate-700">Notunuz</label>
                    <textarea
                        v-model="form.message"
                        rows="5"
                        class="w-full resize-none rounded-md border border-slate-300 px-4 py-3 text-sm font-semibold outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        placeholder="Bilgi almak istediğiniz konuyu yazabilirsiniz."
                    ></textarea>
                    <p v-if="form.errors.message" class="text-sm text-red-600">{{ form.errors.message }}</p>
                </div>

                <button
                    type="submit"
                    class="mt-5 inline-flex w-full items-center justify-center rounded-md bg-blue-700 px-6 py-3.5 text-sm font-black text-white transition hover:bg-blue-800 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Bilgi Talebi Gönder
                </button>

                <p
                    v-if="form.recentlySuccessful"
                    class="mt-4 rounded-md bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700"
                >
                    Talebiniz alındı. En kısa sürede sizinle iletişime geçeceğiz.
                </p>
            </form>
        </div>
    </section>
</template>
