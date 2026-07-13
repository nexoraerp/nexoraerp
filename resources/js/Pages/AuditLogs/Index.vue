<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { CheckCircle2, History, Search, Trash2, UserRoundPen } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

const props = defineProps({
    logs: {
        type: Array,
        default: () => [],
    },
    summary: {
        type: Object,
        default: () => ({
            total: 0,
            create_count: 0,
            update_count: 0,
            delete_count: 0,
        }),
    },
});

const search = ref('');

const filteredLogs = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('tr-TR');

    if (!term) {
        return props.logs;
    }

    return props.logs.filter((log) => [
        log.user,
        log.email,
        log.action_label,
        log.module_label,
        log.method_label,
        log.route_name,
        log.url,
        log.ip_address,
        log.detail_sentence,
    ].some((value) => String(value ?? '').toLocaleLowerCase('tr-TR').includes(term)));
});

const methodClasses = {
    POST: 'bg-emerald-50 text-emerald-700',
    PUT: 'bg-blue-50 text-blue-700',
    PATCH: 'bg-blue-50 text-blue-700',
    DELETE: 'bg-red-50 text-red-700',
};

const statusClasses = (status) => {
    if (Number(status) >= 200 && Number(status) < 300) {
        return 'bg-emerald-50 text-emerald-700';
    }

    if (Number(status) >= 300 && Number(status) < 400) {
        return 'bg-amber-50 text-amber-700';
    }

    return 'bg-red-50 text-red-700';
};
</script>

<template>
    <Head title="İşlem Geçmişi" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="İşlem Geçmişi"
                subtitle="Kimin, ne zaman, hangi modülde işlem yaptığını ve gönderilen işlem detaylarını takip edin."
            />

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Toplam İşlem</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ summary.total }}</h2>
                        </div>
                        <History class="h-8 w-8 text-slate-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Oluşturma</p>
                            <h2 class="mt-2 text-3xl font-black text-emerald-700">{{ summary.create_count }}</h2>
                        </div>
                        <CheckCircle2 class="h-8 w-8 text-emerald-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Güncelleme</p>
                            <h2 class="mt-2 text-3xl font-black text-blue-700">{{ summary.update_count }}</h2>
                        </div>
                        <UserRoundPen class="h-8 w-8 text-blue-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Silme</p>
                            <h2 class="mt-2 text-3xl font-black text-red-700">{{ summary.delete_count }}</h2>
                        </div>
                        <Trash2 class="h-8 w-8 text-red-700" />
                    </div>
                </NxCard>
            </div>

            <NxCard>
                <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-950">Kayıtlar</h2>
                        <p class="text-sm text-slate-500">{{ filteredLogs.length }} işlem görüntüleniyor.</p>
                    </div>

                    <label class="relative w-full xl:max-w-md">
                        <Search class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Kullanıcı, modül, işlem veya IP ara"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        >
                    </label>
                </div>

                <div class="space-y-4">
                    <article
                        v-for="log in filteredLogs"
                        :key="log.id"
                        class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-200 hover:bg-slate-50"
                    >
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-black"
                                        :class="methodClasses[log.method] ?? 'bg-slate-100 text-slate-700'"
                                    >
                                        {{ log.method_label }}
                                    </span>
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-black"
                                        :class="statusClasses(log.response_status)"
                                    >
                                        {{ log.status_label }} {{ log.response_status }}
                                    </span>
                                </div>

                                <h3 class="mt-3 text-lg font-black text-slate-950">{{ log.action_label }}</h3>
                                <p class="mt-1 text-sm font-semibold text-slate-600">
                                    {{ log.detail_sentence }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Kullanıcı: {{ log.user }} / {{ log.email ?? 'E-posta yok' }}
                                </p>
                            </div>

                            <div class="shrink-0 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 xl:text-right">
                                <div class="font-bold text-slate-950">{{ log.module_label }}</div>
                                <div class="mt-1">{{ log.route_name ?? log.url }}</div>
                                <div class="mt-1">IP: {{ log.ip_address ?? '-' }}</div>
                            </div>
                        </div>

                        <div
                            v-if="log.payload?.length"
                            class="mt-5 rounded-2xl border border-slate-100 bg-white p-4"
                        >
                            <div class="mb-3 text-sm font-black text-slate-950">İşlemde gönderilen bilgiler</div>
                            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                <div
                                    v-for="field in log.payload"
                                    :key="`${log.id}-${field.key}`"
                                    class="rounded-xl bg-slate-50 px-3 py-2"
                                >
                                    <div class="text-xs font-bold text-slate-400">{{ field.label }}</div>
                                    <div class="mt-1 break-words text-sm font-semibold text-slate-700">{{ field.value || '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="log.changes?.length"
                            class="mt-5 rounded-2xl border border-amber-100 bg-amber-50 p-4"
                        >
                            <div class="mb-3 text-sm font-black text-amber-950">Değişen alanlar</div>
                            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                <div
                                    v-for="change in log.changes"
                                    :key="`${log.id}-change-${change.key}`"
                                    class="rounded-xl border border-amber-100 bg-white px-3 py-2"
                                >
                                    <div class="text-xs font-bold text-amber-600">{{ change.label }}</div>
                                    <div class="mt-2 text-xs font-bold text-slate-400">Eski</div>
                                    <div class="break-words text-sm font-semibold text-slate-600">{{ change.old || '-' }}</div>
                                    <div class="mt-2 text-xs font-bold text-emerald-600">Yeni</div>
                                    <div class="break-words text-sm font-semibold text-slate-900">{{ change.new || '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div
                        v-if="filteredLogs.length === 0"
                        class="rounded-2xl border border-slate-100 bg-slate-50 p-10 text-center text-sm font-semibold text-slate-500"
                    >
                        Görüntülenecek işlem kaydı bulunamadı.
                    </div>
                </div>
            </NxCard>
        </div>
    </NxLayout>
</template>
