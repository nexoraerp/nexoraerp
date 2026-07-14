<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Banknote, LayoutDashboard, Package, ReceiptText, Users } from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});
const isSubUser = computed(() => Boolean(user.value.parent_user_id));

const canAccess = (permission) => {
    if (!permission || !isSubUser.value) {
        return true;
    }

    return (user.value.permissions ?? []).includes(permission);
};

const items = computed(() => [
    {
        title: 'Panel',
        icon: LayoutDashboard,
        route: 'dashboard',
        prefix: '/dashboard',
    },
    {
        title: 'Cariler',
        icon: Users,
        route: 'customers.index',
        prefix: '/customers',
        permission: 'customers',
    },
    {
        title: 'Satış',
        icon: ReceiptText,
        route: 'sales.index',
        prefix: '/sales',
        permission: 'sales',
    },
    {
        title: 'Ürün',
        icon: Package,
        route: 'products.index',
        prefix: '/products',
        permission: 'products',
    },
    {
        title: 'Tahsilat',
        icon: Banknote,
        route: 'payments.index',
        prefix: '/payments',
        permission: 'payments',
    },
].filter(item => canAccess(item.permission)));

const isActive = (item) => {
    const current = page.url.split('#')[0];

    return current === item.prefix || current.startsWith(`${item.prefix}/`);
};
</script>

<template>
    <nav
        class="fixed inset-x-3 bottom-3 z-40 rounded-2xl border border-slate-200 bg-white/95 px-2 py-2 shadow-2xl shadow-slate-950/20 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/95 lg:hidden"
        aria-label="Mobil alt menü"
    >
        <div class="grid grid-cols-5 gap-1">
            <Link
                v-for="item in items"
                :key="item.title"
                :href="route(item.route)"
                :class="[
                    'flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl px-1 py-2 text-[11px] font-black transition',
                    isActive(item)
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-slate-100'
                ]"
            >
                <component
                    :is="item.icon"
                    class="h-5 w-5"
                />
                <span class="truncate">
                    {{ item.title }}
                </span>
            </Link>
        </div>
    </nav>
</template>
