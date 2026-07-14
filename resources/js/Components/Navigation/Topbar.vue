<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, LogOut, Menu, Moon, Sun } from 'lucide-vue-next';
import OnboardingProgress from '@/Components/Onboarding/OnboardingProgress.vue';
import DashboardExchangeRates from '@/Components/Dashboard/DashboardExchangeRates.vue';

const page = usePage();
const emit = defineEmits(['toggleMobileMenu']);
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
watch(
    () => page.url,
    () => {
        menuOpen.value = false;
    }
);
</script>

<template>

<header
    class="sticky top-0 z-30 flex min-h-16 items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-4 py-3 shadow-sm shadow-slate-200/50 backdrop-blur-xl transition-colors dark:border-slate-800 dark:bg-slate-950/95 dark:shadow-black/20 sm:px-6 lg:h-20 lg:px-8 lg:py-0">

    <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-5">
        <button
            type="button"
            aria-label="Menüyü aç"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-blue-200 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-500/40 lg:hidden"
            @click="emit('toggleMobileMenu')"
        >
            <Menu class="h-5 w-5" />
        </button>

        <div class="min-w-0 flex-1">
            <h1 class="truncate text-lg font-black text-slate-800 dark:text-slate-100 sm:text-2xl">
                {{ pageMeta.title }}
            </h1>

            <p class="truncate text-xs font-semibold text-slate-500 dark:text-slate-400 sm:text-sm">
                {{ pageMeta.subtitle }}
            </p>
        </div>

        <DashboardExchangeRates
            v-if="exchangeRates"
            :rates="exchangeRates"
            compact
            class="hidden max-w-[560px] 2xl:block"
        />
    </div>

    <div class="flex shrink-0 items-center gap-2 sm:gap-4 lg:gap-6">

        <OnboardingProgress
            :onboarding="onboarding"
            class="hidden 2xl:block"
        />

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
                class="flex min-w-0 items-center gap-2 rounded-2xl px-1 py-1 transition hover:bg-slate-50 dark:hover:bg-slate-900 sm:gap-3 sm:px-3 sm:py-2"
            >
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-bold text-white sm:h-11 sm:w-11">

                    {{ initial }}

                </div>

                <div class="hidden min-w-0 max-w-44 text-left md:block">

                    <div class="truncate font-semibold dark:text-slate-100">
                        {{ user.name }}
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        {{ roleLabel }}
                    </div>

                </div>

                <ChevronDown class="hidden h-4 w-4 shrink-0 text-slate-400 sm:block" />
            </button>

            <div
                v-if="menuOpen"
                class="absolute right-0 z-50 mt-3 w-[calc(100vw-2rem)] max-w-80 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl dark:border-slate-800 dark:bg-slate-950"
            >
                <div class="border-b border-slate-100 pb-4 dark:border-slate-800">
                    <div class="break-words font-black text-slate-950 dark:text-slate-100">{{ user.name }}</div>
                    <div class="break-words text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</div>
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
                        <div class="min-w-0 rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                            <div class="text-xs font-bold text-slate-500">Lisans Başlangıç</div>
                            <div class="mt-1 break-words font-black text-slate-950 dark:text-slate-100">{{ license.license_started_at ?? '-' }}</div>
                        </div>

                        <div class="min-w-0 rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                            <div class="text-xs font-bold text-slate-500">Lisans Bitiş</div>
                            <div class="mt-1 break-words font-black text-slate-950 dark:text-slate-100">{{ license.license_ends_at ?? '-' }}</div>
                        </div>

                        <div class="min-w-0 rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                            <div class="text-xs font-bold text-slate-500">Deneme Bitiş</div>
                            <div class="mt-1 break-words font-black text-slate-950 dark:text-slate-100">{{ license.trial_ends_at ?? '-' }}</div>
                        </div>

                        <div class="min-w-0 rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                            <div class="text-xs font-bold text-slate-500">Kalan Süre</div>
                            <div class="mt-1 break-words font-black text-slate-950 dark:text-slate-100">
                                {{ license.remaining_days_label ?? `${Math.floor(Number(license.remaining_days ?? 0))} gün` }}
                            </div>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-black text-red-700 transition hover:bg-red-100 dark:border-red-500/20 dark:bg-red-950/30 dark:text-red-300 dark:hover:bg-red-950/50"
                >
                    <LogOut class="h-4 w-4" />
                    Çıkış
                </Link>
            </div>

        </div>

    </div>

</header>

</template>
