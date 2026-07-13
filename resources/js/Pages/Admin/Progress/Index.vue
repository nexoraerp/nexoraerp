<script setup>
import { Head, router } from '@inertiajs/vue3';
import { LifeBuoy, Mail, Phone, ShieldCheck, UserCheck, UsersRound } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});

const activeUsers = () => props.users.filter(user => user.is_active).length;
const passiveUsers = () => props.users.filter(user => !user.is_active).length;
const averageProgress = () => {
    if (props.users.length === 0) {
        return 0;
    }

    return Math.round(
        props.users.reduce((total, user) => total + Number(user.progress_score || 0), 0) / props.users.length
    );
};

const activateAnnualLicense = (user) => {
    router.patch(route('admin.users.activate-annual-license', user.id), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Admin Panel" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Admin Panel"
                subtitle="Kullanıcı iletişim bilgileri, lisans durumu, kurulum ilerlemesi ve destek talebi yoğunluğunu izleyin."
            />

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-4">
                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Kullanıcı</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ users.length }}</h2>
                        </div>
                        <UserCheck class="h-8 w-8 text-blue-600" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Aktif Kullanıcı</p>
                            <h2 class="mt-2 text-3xl font-black text-emerald-700">{{ activeUsers() }}</h2>
                        </div>
                        <UsersRound class="h-8 w-8 text-emerald-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Pasif Kullanıcı</p>
                            <h2 class="mt-2 text-3xl font-black text-amber-700">{{ passiveUsers() }}</h2>
                        </div>
                        <ShieldCheck class="h-8 w-8 text-amber-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Destek Talepleri</p>
                            <h2 class="mt-2 text-3xl font-black text-purple-700">
                                {{ users.reduce((total, user) => total + Number(user.support_count || 0), 0) }}
                            </h2>
                        </div>
                        <LifeBuoy class="h-8 w-8 text-purple-700" />
                    </div>
                </NxCard>
            </div>

            <NxCard>
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">Müşteri İletişim Bilgileri</h2>
                        <p class="text-sm text-slate-500">Kayıt olan kullanıcıların şirket, telefon ve e-posta bilgileri.</p>
                    </div>
                    <div class="rounded-2xl bg-blue-50 px-4 py-2 text-sm font-black text-blue-700">
                        Ortalama ilerleme %{{ averageProgress() }}
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="user in users"
                        :key="`contact-${user.id}`"
                        class="rounded-2xl border border-slate-200 p-4"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-black text-slate-950">{{ user.company_name || user.name }}</h3>
                                <p class="mt-1 text-sm font-semibold text-slate-500">{{ user.name }}</p>
                            </div>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-bold"
                                :class="user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                            >
                                {{ user.is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <Mail class="h-4 w-4 text-slate-400" />
                                <span>{{ user.email }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Phone class="h-4 w-4 text-slate-400" />
                                <span>{{ user.phone || 'Telefon girilmemiş' }}</span>
                            </div>
                            <div class="rounded-xl bg-slate-50 px-3 py-2 font-semibold">
                                Lisans bitiş: {{ user.license?.license_ends_at ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </NxCard>

            <NxCard>
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-slate-950">Kullanıcı Kurulum ve İlerleme Durumu</h2>
                    <p class="text-sm text-slate-500">Admin yalnızca kullanıcıların sistemi ne kadar benimsediğini ve kurulum seviyesini takip eder.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1220px]">
                        <thead class="border-b bg-slate-50">
                            <tr>
                                <th class="px-4 py-4 text-left">Kullanıcı</th>
                                <th class="text-left">İletişim</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Durum</th>
                                <th class="text-center">Lisans</th>
                                <th class="text-center">Teklif</th>
                                <th class="text-center">Satış</th>
                                <th class="text-center">Tahsilat</th>
                                <th class="text-center">Destek</th>
                                <th class="text-center">İlerleme</th>
                                <th class="text-right">Son Hareket</th>
                                <th class="pr-4 text-right">İşlem</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="border-b hover:bg-slate-50"
                            >
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-950">{{ user.name }}</div>
                                    <div class="text-sm text-slate-500">{{ user.company_name || '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-slate-700">{{ user.email }}</div>
                                    <div class="text-sm text-slate-500">{{ user.phone || '-' }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-bold"
                                        :class="user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                                    >
                                        {{ user.is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div
                                        class="mx-auto inline-flex min-w-36 flex-col rounded-xl bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600"
                                    >
                                        <span
                                            :class="user.license?.can_write ? 'text-emerald-700' : 'text-red-700'"
                                        >
                                            {{ user.license?.status === 'licensed' ? 'Yıllık Lisans' : (user.license?.can_write ? 'Deneme Aktif' : 'Süresi Bitti') }}
                                        </span>
                                        <span class="mt-1 text-slate-500">
                                            {{ user.license?.license_ends_at ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center font-semibold">{{ user.quotes_count }}</td>
                                <td class="text-center font-semibold">{{ user.sales_count }}</td>
                                <td class="text-center font-semibold">{{ user.payments_count }}</td>
                                <td class="text-center font-semibold">{{ user.support_count }}</td>
                                <td class="text-center">
                                    <div class="mx-auto h-2 w-28 rounded-full bg-slate-100">
                                        <div
                                            class="h-2 rounded-full bg-blue-600"
                                            :style="{ width: `${user.progress_score}%` }"
                                        />
                                    </div>
                                    <div class="mt-1 text-xs font-bold text-slate-500">%{{ user.progress_score }}</div>
                                </td>
                                <td class="text-right">
                                    <div class="font-semibold text-slate-700">{{ user.last_action ?? '-' }}</div>
                                    <div class="text-sm text-slate-500">{{ user.last_action_at ?? '-' }}</div>
                                </td>
                                <td class="pr-4 text-right">
                                    <button
                                        v-if="user.can_activate_license"
                                        type="button"
                                        class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-black text-blue-700 transition hover:bg-blue-100"
                                        @click="activateAnnualLicense(user)"
                                    >
                                        1 Yıl Aktifleştir
                                    </button>

                                    <span
                                        v-else
                                        class="text-xs font-bold text-slate-400"
                                    >
                                        -
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </NxCard>
        </div>
    </NxLayout>
</template>
