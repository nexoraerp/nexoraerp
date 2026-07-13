<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import ExpenseForm from './Partials/ExpenseForm.vue';

defineProps({
    categories: Array,
    cashAccounts: Array,
});
</script>

<template>
    <Head title="Yeni Gider" />
    <NxLayout>
        <NxPageHeader
            title="Yeni Gider"
            subtitle="Tahakkuk eden gideri kaydedin, ödeme varsa kasa çıkışını otomatik oluşturun."
        >
            <Link :href="route('expenses.index')">
                <NxButton variant="secondary">Giderlere Dön</NxButton>
            </Link>
        </NxPageHeader>
        <ExpenseForm
            :categories="categories"
            :cash-accounts="cashAccounts"
        />
    </NxLayout>
</template>
