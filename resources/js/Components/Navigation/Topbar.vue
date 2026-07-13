<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, LogOut, Moon, Sun } from 'lucide-vue-next';
import OnboardingProgress from '@/Components/Onboarding/OnboardingProgress.vue';
import DashboardExchangeRates from '@/Components/Dashboard/DashboardExchangeRates.vue';

const page = usePage();
const menuOpen = ref(false);
const theme = ref('light');

const user = computed(() => page.props.auth?.user ?? {});
const license = computed(() => page.props.license ?? null);
const exchangeRates = computed(() => page.props.exchangeRates ?? null);
const onboarding = computed(() => page.props.onboarding ?? null);
const initial = computed(() => (user.value.name ?? 'N').charAt(0).toUpperCase());
const roleLabel = computed(() => user.value.role === 'admin' ? 'Yönetici' : 'Kullanıcı');
const pageMeta = computed(() => {
    const url = page.url;

    if (url.startsWith('/customers')) {
        return { title: 'Cariler', subtitle: 'Cari hesap ve müşteri yönetimi' };
    }

    if (url.startsWith('/quotes')) {
        return { title: 'Teklifler', subtitle: 'Teklif oluşturma ve satışa dönüştürme' };
    }

    if (url.startsWith('/sales')) {
        return { title: 'Satışlar', subtitle: 'Satış, stok ve finans hareketleri' };
    }

    if (url.startsWith('/payments')) {
        return { title: 'Tahsilatlar', subtitle: 'Cari tahsilat ve ödeme takibi' };
    }

    if (url.startsWith('/products')) {
        return { title: 'Ürünler', subtitle: 'Ürün, fiyat ve stok kartları' };
    }

    if (url.startsWith('/warehouses')) {
        return { title: 'Depolar', subtitle: 'Depo ve stok lokasyonları' };
    }

    if (url.startsWith('/stock-movements')) {
        if (url.startsWith('/stock-movements/transfer')) {
            return { title: 'Depolar Arası Transfer', subtitle: 'Ürünleri depolar arasında kontrollü aktarın' };
        }

        return { title: 'Stok Hareketleri', subtitle: 'Giriş, çıkış ve transfer hareketleri' };
    }

    if (url.startsWith('/cash-accounts')) {
        return { title: 'Kasa', subtitle: 'Kasa hesapları ve finans akışı' };
    }

    if (url.startsWith('/expenses')) {
        return { title: 'Gider Yönetimi', subtitle: 'Faaliyet giderleri ve ödeme takibi' };
    }

    if (url.startsWith('/reports/profit-loss')) {
        return { title: 'Kâr/Zarar Raporu', subtitle: 'Gelir, maliyet ve faaliyet gideri analizi' };
    }

    if (url.startsWith('/reports')) {
        return { title: 'Raporlar', subtitle: 'Satış, kâr ve performans analizleri' };
    }

    if (url.startsWith('/risk-analysis')) {
        return { title: 'Risk Analizi', subtitle: 'Vade, stok ve operasyon uyarıları' };
    }

    if (url.startsWith('/audit-logs')) {
        return { title: 'İşlem Geçmişi', subtitle: 'Kullanıcı işlem kayıtları ve detayları' };
    }

    if (url.startsWith('/support-tickets')) {
        return { title: 'Çözüm Öneri ve Destek', subtitle: 'Destek talepleri ve ürün geliştirme önerileri' };
    }

    if (url.startsWith('/admin')) {
        return { title: 'Admin Panel', subtitle: 'Müşteri ve lisans yönetimi' };
    }

    if (url.startsWith('/settings')) {
        return { title: 'Ayarlar', subtitle: 'Kullanıcı, yetki ve sistem tanımları' };
    }

    return { title: 'Dashboard', subtitle: 'Nexora ERP Yönetim Paneli' };
});
const licenseStatusClass = computed(() => {
    if (!license.value) {
        return 'bg-slate-100 text-slate-600';
    }

    return license.value.can_write
        ? 'bg-emerald-50 text-emerald-700'
        : 'bg-red-50 text-red-700';
});

const isDark = computed(() => theme.value === 'dark');

const applyTheme = (value) => {
    document.documentElement.classList.toggle('dark', value === 'dark');
    localStorage.setItem('nexora-theme', value);
};

const toggleTheme = () => {
    theme.value = isDark.value ? 'light' : 'dark';
};

onMounted(() => {
    theme.value = localStorage.getItem('nexora-theme') ?? 'light';
    applyTheme(theme.value);
});

watch(theme, applyTheme);
</script>

<template>

<header
    class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 transition-colors dark:border-slate-800 dark:bg-slate-950">

    <div class="flex min-w-0 items-center gap-5">
        <div class="shrink-0">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                {{ pageMeta.title }}
            </h1>

            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ pageMeta.subtitle }}
            </p>
        </div>

        <DashboardExchangeRates
            v-if="exchangeRates"
            :rates="exchangeRates"
            compact
            class="hidden xl:block"
        />
    </div>

    <div class="flex items-center gap-6">

        <OnboardingProgress :onboarding="onboarding" />

        <button
            type="button"
            :aria-label="isDark ? 'Açık temaya geç' : 'Koyu temaya geç'"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
            @click="toggleTheme"
        >
            <Sun
                v-if="isDark"
                class="h-5 w-5"
            />
            <Moon
                v-else
                class="h-5 w-5"
            />
        </button>

        <div class="relative">

            <button
                type="button"
                @click="menuOpen = !menuOpen"
                class="flex items-center gap-3 rounded-2xl px-3 py-2 transition hover:bg-slate-50 dark:hover:bg-slate-900"
            >
                <div
                    class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                    {{ initial }}

                </div>

                <div class="text-left">

                    <div class="font-semibold dark:text-slate-100">
                        {{ user.name }}
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        {{ roleLabel }}
                    </div>

                </div>

                <ChevronDown class="h-4 w-4 text-slate-400" />
            </button>

            <div
                v-if="menuOpen"
                class="absolute right-0 z-50 mt-3 w-80 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl dark:border-slate-800 dark:bg-slate-950"
            >
                <div class="border-b border-slate-100 pb-4">
                    <div class="font-black text-slate-950 dark:text-slate-100">{{ user.name }}</div>
                    <div class="text-sm text-slate-500">{{ user.email }}</div>
                </div>

                <div
                    v-if="license"
                    class="mt-4 space-y-3"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500">Durum</span>
                        <span
                            class="rounded-full px-3 py-1 text-xs font-black"
                            :class="licenseStatusClass"
                        >
                            {{ license.can_write ? 'Aktif' : 'Süresi Bitti' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-bold text-slate-500">Lisans Başlangıç</div>
                            <div class="mt-1 font-black text-slate-950">{{ license.license_started_at ?? '-' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-bold text-slate-500">Lisans Bitiş</div>
                            <div class="mt-1 font-black text-slate-950">{{ license.license_ends_at ?? '-' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-bold text-slate-500">Deneme Bitiş</div>
                            <div class="mt-1 font-black text-slate-950">{{ license.trial_ends_at ?? '-' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-bold text-slate-500">Kalan Süre</div>
                            <div class="mt-1 font-black text-slate-950">
                                {{ license.remaining_days_label ?? `${Math.floor(Number(license.remaining_days ?? 0))} gün` }}
                            </div>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-black text-red-700 transition hover:bg-red-100"
                >
                    <LogOut class="h-4 w-4" />
                    Çıkış
                </Link>
            </div>

        </div>

    </div>

</header>

</template>
