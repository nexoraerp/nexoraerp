<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import {
    LayoutDashboard,
    Users,
    Package,
    Boxes,
    ArrowLeftRight,
    ReceiptText,
    FileText,
    Banknote,
    Wallet,
    BarChart3,
    Settings,
    ShieldCheck,
    History,
    UserCog,
    CircleDollarSign,
    LifeBuoy,
    LockKeyhole,
    PhoneCall,
    X,
} from 'lucide-vue-next';

const page = usePage();

defineProps({
    mobileOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['closeMobile']);

const canSeeAdmin = computed(() => page.props.auth?.user?.role === 'admin');
const user = computed(() => page.props.auth?.user ?? {});
const isSubUser = computed(() => Boolean(user.value.parent_user_id));
const canAccess = (permission) => {
    if (!permission || !isSubUser.value) {
        return true;
    }

    return (user.value.permissions ?? []).includes(permission);
};

const groups = [

    {
        title: 'GENEL',

        items: [
            {
                title: 'Dashboard',
                icon: LayoutDashboard,
                route: 'dashboard',
                prefix: '/dashboard',
            },
        ],
    },

    {
        title: 'SATIŞ',

        items: [
            {
                title: 'Cariler',
                icon: Users,
                route: 'customers.index',
                prefix: '/customers',
                permission: 'customers',
            },
            {
                title: 'Teklifler',
                icon: FileText,
                route: 'quotes.index',
                prefix: '/quotes',
                permission: 'quotes',
            },
            {
                title: 'Satışlar',
                icon: ReceiptText,
                route: 'sales.index',
                prefix: '/sales',
                permission: 'sales',
            },
        ],
    },

    {
        title: 'STOK',

        items: [
            {
                title: 'Ürünler',
                icon: Package,
                route: 'products.index',
                prefix: '/products',
                permission: 'products',
            },
            {
                title: 'Depolar',
                icon: Boxes,
                route: 'warehouses.index',
                prefix: '/warehouses',
                permission: 'warehouses',
            },
            {
                title: 'Stok Hareketleri',
                icon: BarChart3,
                route: 'stock-movements.index',
                prefix: '/stock-movements',
                exact: true,
                permission: 'stock',
            },
            {
                title: 'Depo Transferi',
                icon: ArrowLeftRight,
                route: 'stock-movements.transfer',
                prefix: '/stock-movements/transfer',
                permission: 'stock',
            },
        ],
    },

    {
    title: 'FİNANS',

    items: [
        {
            title: 'Tahsilatlar',
            icon: Banknote,
            route: 'payments.index',
            prefix: '/payments',
            permission: 'payments',
        },
        {
            title: 'Kasa Hesapları',
            icon: Wallet,
            route: 'cash-accounts.index',
            prefix: '/cash-accounts',
            permission: 'finance',
        },
        {
            title: 'Gider Yönetimi',
            icon: CircleDollarSign,
            route: 'expenses.index',
            prefix: '/expenses',
            permission: 'expenses.view',
        },
    ],
},

    {
        title: 'RAPORLAR',

        items: [
            {
                title: 'Raporlar',
                icon: BarChart3,
                route: 'reports.index',
                prefix: '/reports',
                exact: true,
                permission: 'reports',
            },
            {
                title: 'Kâr/Zarar Raporu',
                icon: BarChart3,
                route: 'reports.profit-loss',
                prefix: '/reports/profit-loss',
                permission: 'profit_loss.view',
            },
            {
                title: 'Risk Analizi',
                icon: BarChart3,
                route: 'risk-analysis.index',
                prefix: '/risk-analysis',
                permission: 'reports',
            },
        ],
    },

    {
        title: 'YÖNETİM',

        items: [
            {
                title: 'Kullanıcılar',
                icon: Users,
                href: '/settings?section=users',
                prefix: '/settings?section=users',
                permission: 'settings',
            },
            {
                title: 'Yetkiler',
                icon: UserCog,
                href: '/settings?section=permissions',
                prefix: '/settings?section=permissions',
                permission: 'settings',
            },
            {
                title: 'İşlem Geçmişi',
                icon: History,
                route: 'audit-logs.index',
                prefix: '/audit-logs',
                permission: 'settings',
            },
            {
                title: 'Destek Talepleri',
                icon: LifeBuoy,
                route: 'admin.support-tickets.index',
                prefix: '/admin/support-tickets',
                adminOnly: true,
            },
            {
                title: 'Bilgi Talepleri',
                icon: PhoneCall,
                route: 'admin.lead-requests.index',
                prefix: '/admin/lead-requests',
                adminOnly: true,
            },
            {
                title: 'Admin Panel',
                icon: ShieldCheck,
                route: 'admin.progress.index',
                prefix: '/admin/progress',
                adminOnly: true,
            },
        ],
    },

    {
        title: 'AYARLAR',

        items: [
            {
                title: 'Ayarlar',
                icon: Settings,
                href: '/settings?section=definitions',
                prefix: '/settings?section=definitions',
                permission: 'settings',
            },
            {
                title: 'Şifre ve Güvenlik',
                icon: LockKeyhole,
                href: '/settings?section=security',
                prefix: '/settings?section=security',
                permission: 'settings',
            },
            {
                title: 'Çözüm Öneri',
                icon: LifeBuoy,
                route: 'support-tickets.index',
                prefix: '/support-tickets',
                permission: 'support',
            },
        ],
    },

];

const isActive = (item) => {
    const current = page.url.split('#')[0];
    const target = item.href ?? item.prefix;

    if (item.exact || target.includes('?')) {
        return current === target;
    }

    return current === item.prefix || current.startsWith(`${item.prefix}/`);
};
</script>

<template>

<div
    v-if="mobileOpen"
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
    @click="emit('closeMobile')"
/>

<aside
    :class="[
        'fixed inset-y-0 left-0 z-50 flex h-screen w-72 flex-col bg-slate-900 text-white shadow-2xl transition-transform duration-300 lg:sticky lg:top-0 lg:z-auto lg:translate-x-0',
        mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
>

    <div class="flex items-start justify-between gap-4 border-b border-slate-800 px-8 py-8">

        <div>
            <h1 class="text-3xl font-black tracking-[0.30em]">
                NE<span class="text-blue-500">X</span>ORA
            </h1>

            <p class="text-slate-400 text-sm mt-2">
                Modern Business ERP
            </p>
        </div>

        <button
            type="button"
            aria-label="Menüyü kapat"
            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700 text-slate-300 transition hover:border-slate-500 hover:text-white lg:hidden"
            @click="emit('closeMobile')"
        >
            <X class="h-5 w-5" />
        </button>

    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6">

        <div
            v-for="group in groups"
            :key="group.title"
            class="mb-8"
        >

            <p
                class="px-4 mb-3 text-xs font-bold tracking-widest uppercase text-slate-500"
            >
                {{ group.title }}
            </p>

            <div class="space-y-1">

                <template
                    v-for="item in group.items"
                    :key="item.title"
                >

                    <Link
                        v-if="!item.disabled && (!item.adminOnly || canSeeAdmin) && canAccess(item.permission)"
                        :href="item.href ?? route(item.route)"
                        :class="[
                            'flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200',
                            isActive(item)
                                ? 'bg-blue-600 text-white shadow-lg'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                        ]"
                        @click="emit('closeMobile')"
                    >

                        <component
                            :is="item.icon"
                            class="w-5 h-5"
                        />

                        <span class="font-medium">
                            {{ item.title }}
                        </span>

                    </Link>

                    <div
                        v-else-if="!item.adminOnly || canSeeAdmin"
                        class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 cursor-not-allowed"
                    >

                        <div class="flex items-center gap-4">

                            <component
                                :is="item.icon"
                                class="w-5 h-5"
                            />

                            <span>
                                {{ item.title }}
                            </span>

                        </div>

                        <span
                            class="text-[10px] bg-slate-700 px-2 py-1 rounded-full"
                        >
                            Yakında
                        </span>

                    </div>

                </template>

            </div>

        </div>

    </nav>

</aside>

</template> 
