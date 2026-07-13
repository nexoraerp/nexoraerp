<script setup>
import { computed, reactive, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { CheckCircle2, Clock3, LifeBuoy, Search, Send, TicketCheck } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const props = defineProps({
    tickets: {
        type: Array,
        default: () => [],
    },
    summary: {
        type: Object,
        default: () => ({
            total: 0,
            open: 0,
            in_progress: 0,
            resolved: 0,
        }),
    },
    options: {
        type: Object,
        default: () => ({
            categories: {},
            priorities: {},
            statuses: {},
        }),
    },
    isAdminView: {
        type: Boolean,
        default: false,
    },
});

const search = ref('');
const adminForms = reactive({});

const categoryOptions = computed(() => Object.entries(props.options.categories ?? {}).map(([value, label]) => ({
    value,
    label,
})));

const priorityOptions = computed(() => Object.entries(props.options.priorities ?? {}).map(([value, label]) => ({
    value,
    label,
})));

const statusOptions = computed(() => Object.entries(props.options.statuses ?? {}).map(([value, label]) => ({
    value,
    label,
})));

const ticketForm = useForm({
    subject: '',
    category: 'support',
    priority: 'normal',
    message: '',
});

const filteredTickets = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('tr-TR');

    if (!term) {
        return props.tickets;
    }

    return props.tickets.filter((ticket) => [
        ticket.ticket_no,
        ticket.subject,
        ticket.category_label,
        ticket.priority_label,
        ticket.status_label,
        ticket.message,
        ticket.user?.name,
        ticket.user?.email,
        ticket.user?.phone,
        ticket.tenant?.company_name,
    ].some(value => String(value ?? '').toLocaleLowerCase('tr-TR').includes(term)));
});

const submitTicket = () => {
    ticketForm.post(route('support-tickets.store'), {
        preserveScroll: true,
        onSuccess: () => ticketForm.reset(),
    });
};

const adminFormFor = (ticket) => {
    if (!adminForms[ticket.id]) {
        adminForms[ticket.id] = {
            status: ticket.status,
            admin_note: ticket.admin_note ?? '',
        };
    }

    return adminForms[ticket.id];
};

const updateTicket = (ticket) => {
    router.patch(route('support-tickets.update', ticket.id), adminFormFor(ticket), {
        preserveScroll: true,
    });
};

const statusClasses = {
    open: 'bg-blue-50 text-blue-700',
    in_progress: 'bg-amber-50 text-amber-700',
    resolved: 'bg-emerald-50 text-emerald-700',
    closed: 'bg-slate-100 text-slate-700',
};

const priorityClasses = {
    low: 'bg-slate-100 text-slate-700',
    normal: 'bg-blue-50 text-blue-700',
    high: 'bg-amber-50 text-amber-700',
    critical: 'bg-red-50 text-red-700',
};
</script>

<template>
    <Head :title="isAdminView ? 'Destek Talepleri' : 'Çözüm Öneri ve Destek'" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                :title="isAdminView ? 'Destek Talepleri' : 'Çözüm Öneri ve Destek'"
                :subtitle="isAdminView ? 'Müşterilerden gelen destek, hata ve çözüm önerilerini takip edin.' : 'Nexora ekibine destek talebi, hata bildirimi veya çözüm önerisi gönderin.'"
            />

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Toplam Talep</p>
                            <h2 class="mt-2 text-3xl font-black text-slate-950">{{ summary.total }}</h2>
                        </div>
                        <LifeBuoy class="h-8 w-8 text-slate-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Açık</p>
                            <h2 class="mt-2 text-3xl font-black text-blue-700">{{ summary.open }}</h2>
                        </div>
                        <Clock3 class="h-8 w-8 text-blue-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">İnceleniyor</p>
                            <h2 class="mt-2 text-3xl font-black text-amber-700">{{ summary.in_progress }}</h2>
                        </div>
                        <TicketCheck class="h-8 w-8 text-amber-700" />
                    </div>
                </NxCard>

                <NxCard>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Çözüldü</p>
                            <h2 class="mt-2 text-3xl font-black text-emerald-700">{{ summary.resolved }}</h2>
                        </div>
                        <CheckCircle2 class="h-8 w-8 text-emerald-700" />
                    </div>
                </NxCard>
            </div>

            <div class="grid gap-6 xl:grid-cols-[420px_1fr]">
                <NxCard v-if="!isAdminView">
                    <div class="mb-5">
                        <h2 class="text-xl font-black text-slate-950">Yeni Talep Oluştur</h2>
                        <p class="text-sm text-slate-500">Konuyu net yazarsanız çözüm süreci daha hızlı ilerler.</p>
                    </div>

                    <form
                        class="space-y-4"
                        @submit.prevent="submitTicket"
                    >
                        <NxInput
                            v-model="ticketForm.subject"
                            label="Konu"
                            placeholder="Örn. Teklif PDF çıktısında logo hizası"
                            :error="ticketForm.errors.subject"
                        />

                        <NxSelect
                            v-model="ticketForm.category"
                            label="Talep Tipi"
                            :options="categoryOptions"
                            option-label="label"
                            option-value="value"
                            :error="ticketForm.errors.category"
                        />

                        <NxSelect
                            v-model="ticketForm.priority"
                            label="Öncelik"
                            :options="priorityOptions"
                            option-label="label"
                            option-value="value"
                            :error="ticketForm.errors.priority"
                        />

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">Açıklama</label>
                            <textarea
                                v-model="ticketForm.message"
                                rows="7"
                                placeholder="Yaşadığınız durumu, beklediğiniz çözümü ve varsa örnek belge/sayfa adını yazın."
                                class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p
                                v-if="ticketForm.errors.message"
                                class="text-sm font-semibold text-red-600"
                            >
                                {{ ticketForm.errors.message }}
                            </p>
                        </div>

                        <NxButton
                            type="submit"
                            :loading="ticketForm.processing"
                            block
                        >
                            <Send class="mr-2 h-4 w-4" />
                            Talebi Gönder
                        </NxButton>
                    </form>
                </NxCard>

                <NxCard :class="isAdminView ? 'xl:col-span-2' : ''">
                    <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-950">Talep Kayıtları</h2>
                            <p class="text-sm text-slate-500">{{ filteredTickets.length }} kayıt görüntüleniyor.</p>
                        </div>

                        <label class="relative w-full xl:max-w-md">
                            <Search class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                            <input
                                v-model="search"
                                type="search"
                                placeholder="Talep no, müşteri, konu veya durum ara"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                        </label>
                    </div>

                    <div class="space-y-4">
                        <article
                            v-for="ticket in filteredTickets"
                            :key="ticket.id"
                            class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-200 hover:bg-slate-50"
                        >
                            <div class="flex flex-col gap-4 2xl:flex-row 2xl:items-start 2xl:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">
                                            {{ ticket.ticket_no }}
                                        </span>
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-black"
                                            :class="statusClasses[ticket.status]"
                                        >
                                            {{ ticket.status_label }}
                                        </span>
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-black"
                                            :class="priorityClasses[ticket.priority]"
                                        >
                                            {{ ticket.priority_label }}
                                        </span>
                                    </div>

                                    <h3 class="mt-3 text-lg font-black text-slate-950">{{ ticket.subject }}</h3>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ ticket.message }}</p>
                                </div>

                                <div class="shrink-0 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 2xl:w-72">
                                    <div class="font-black text-slate-950">{{ ticket.category_label }}</div>
                                    <div class="mt-1">Oluşturma: {{ ticket.created_at }}</div>
                                    <div class="mt-1">Çözüm: {{ ticket.resolved_at ?? '-' }}</div>
                                    <template v-if="isAdminView">
                                        <div class="mt-3 border-t border-slate-100 pt-3">
                                            <div class="font-bold text-slate-950">{{ ticket.user?.name ?? '-' }}</div>
                                            <div>{{ ticket.user?.email ?? '-' }}</div>
                                            <div>{{ ticket.user?.phone ?? '-' }}</div>
                                            <div>{{ ticket.tenant?.company_name ?? '-' }}</div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div
                                v-if="ticket.admin_note"
                                class="mt-4 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-900"
                            >
                                <div class="font-black">Admin Notu</div>
                                <p class="mt-1 whitespace-pre-line">{{ ticket.admin_note }}</p>
                            </div>

                            <form
                                v-if="isAdminView"
                                class="mt-5 grid gap-3 xl:grid-cols-[220px_1fr_auto]"
                                @submit.prevent="updateTicket(ticket)"
                            >
                                <NxSelect
                                    v-model="adminFormFor(ticket).status"
                                    label="Durum"
                                    :options="statusOptions"
                                    option-label="label"
                                    option-value="value"
                                />

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-slate-700">Admin Notu</label>
                                    <textarea
                                        v-model="adminFormFor(ticket).admin_note"
                                        rows="3"
                                        class="w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                                    />
                                </div>

                                <NxButton
                                    type="submit"
                                    class="self-end"
                                >
                                    Güncelle
                                </NxButton>
                            </form>
                        </article>

                        <div
                            v-if="filteredTickets.length === 0"
                            class="rounded-2xl border border-slate-100 bg-slate-50 p-10 text-center text-sm font-semibold text-slate-500"
                        >
                            Henüz destek veya çözüm öneri kaydı bulunamadı.
                        </div>
                    </div>
                </NxCard>
            </div>
        </div>
    </NxLayout>
</template>
