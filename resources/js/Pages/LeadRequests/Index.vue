<script setup>
import { computed, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { PhoneCall, Search, UserCheck, Users } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const props = defineProps({
    leads: {
        type: Array,
        default: () => [],
    },
    summary: {
        type: Object,
        default: () => ({ total: 0, new: 0, contacted: 0, closed: 0 }),
    },
    statuses: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref('');
const forms = reactive({});

const statusOptions = computed(() => Object.entries(props.statuses).map(([value, label]) => ({
    value,
    label,
})));

const filteredLeads = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('tr-TR');

    if (!term) {
        return props.leads;
    }

    return props.leads.filter((lead) => [
        lead.name,
        lead.company_name,
        lead.phone,
        lead.email,
        lead.message,
        lead.status_label,
    ].some(value => String(value ?? '').toLocaleLowerCase('tr-TR').includes(term)));
});

const formFor = (lead) => {
    if (!forms[lead.id]) {
        forms[lead.id] = {
            status: lead.status,
            admin_note: lead.admin_note ?? '',
        };
    }

    return forms[lead.id];
};

const updateLead = (lead) => {
    router.patch(route('admin.lead-requests.update', lead.id), formFor(lead), {
        preserveScroll: true,
    });
};

const statusClasses = {
    new: 'bg-blue-50 text-blue-700',
    contacted: 'bg-emerald-50 text-emerald-700',
    qualified: 'bg-amber-50 text-amber-700',
    closed: 'bg-slate-100 text-slate-700',
};
</script>

<template>
    <Head title="Bilgi Talepleri" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Bilgi Talepleri"
                subtitle="Tanıtım sayfasından gelen arama ve demo taleplerini takip edin."
            />

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Toplam</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ summary.total }}</h2>
                        </div>
                        <Users class="h-8 w-8 text-slate-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Yeni</p>
                            <h2 class="mt-2 text-3xl font-black text-blue-700">{{ summary.new }}</h2>
                        </div>
                        <PhoneCall class="h-8 w-8 text-blue-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Arandı</p>
                            <h2 class="mt-2 text-3xl font-black text-emerald-700">{{ summary.contacted }}</h2>
                        </div>
                        <UserCheck class="h-8 w-8 text-emerald-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Kapandı</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-700">{{ summary.closed }}</h2>
                        </div>
                        <UserCheck class="h-8 w-8 text-slate-700" />
                    </div>
                </NxCard>
            </div>

            <NxCard>
                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-950">Gelen Formlar</h2>
                        <p class="text-sm text-slate-500">Müşteri adayı bilgilerini ve arama durumunu buradan yönetin.</p>
                    </div>

                    <div class="relative">
                        <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input
                            v-model="search"
                            type="search"
                            placeholder="Form ara..."
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100 lg:w-80"
                        />
                    </div>
                </div>

                <div class="space-y-4">
                    <article
                        v-for="lead in filteredLeads"
                        :key="lead.id"
                        class="rounded-2xl border border-slate-200 p-5"
                    >
                        <div class="grid gap-5 xl:grid-cols-[1fr_320px]">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-black text-slate-950">{{ lead.name }}</h3>
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-black"
                                        :class="statusClasses[lead.status] ?? 'bg-slate-100 text-slate-700'"
                                    >
                                        {{ lead.status_label }}
                                    </span>
                                    <span class="text-sm font-semibold text-slate-500">{{ lead.created_human }}</span>
                                </div>

                                <div class="mt-3 grid gap-2 text-sm font-semibold text-slate-600 sm:grid-cols-2">
                                    <div>Firma: {{ lead.company_name ?? '-' }}</div>
                                    <div>Telefon: {{ lead.phone }}</div>
                                    <div>E-posta: {{ lead.email ?? '-' }}</div>
                                    <div>Tarih: {{ lead.created_at }}</div>
                                </div>

                                <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                                    {{ lead.message || 'Not bırakılmadı.' }}
                                </p>
                            </div>

                            <div class="space-y-3">
                                <NxSelect
                                    v-model="formFor(lead).status"
                                    label="Durum"
                                    :options="statusOptions"
                                    option-label="label"
                                    option-value="value"
                                />

                                <textarea
                                    v-model="formFor(lead).admin_note"
                                    rows="4"
                                    placeholder="Admin notu..."
                                    class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                ></textarea>

                                <button
                                    type="button"
                                    class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-black text-white transition hover:bg-blue-800"
                                    @click="updateLead(lead)"
                                >
                                    Güncelle
                                </button>
                            </div>
                        </div>
                    </article>

                    <div
                        v-if="filteredLeads.length === 0"
                        class="rounded-2xl bg-slate-50 p-8 text-center text-sm font-semibold text-slate-500"
                    >
                        Henüz bilgi talebi yok.
                    </div>
                </div>
            </NxCard>
        </div>
    </NxLayout>
</template>
