<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';

defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Gider Kategorileri" />
    <NxLayout>
        <NxPageHeader
            title="Gider Kategorileri"
            subtitle="Varsayılan ve kullanıcıya özel gider sınıfları."
        >
            <Link :href="route('expenses.index')">
                <NxButton variant="secondary">Giderlere Dön</NxButton>
            </Link>
        </NxPageHeader>

        <NxCard>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="rounded-2xl border border-slate-200 p-4"
                >
                    <div class="text-xs font-black text-slate-400">{{ category.code }}</div>
                    <div class="mt-1 font-black text-slate-950">{{ category.name }}</div>
                    <div class="mt-3 text-sm text-slate-500">
                        {{ category.is_active ? 'Aktif' : 'Pasif' }}
                    </div>
                </div>
            </div>
        </NxCard>
    </NxLayout>
</template>
