<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import ExpenseForm from './Partials/ExpenseForm.vue';

defineProps({
    expense: Object,
    categories: Array,
    cashAccounts: Array,
});
</script>

<template>
    <Head title="Gider Düzenle" />
    <NxLayout>
        <NxPageHeader
            title="Gider Düzenle"
            :subtitle="expense.expense_no"
        >
            <Link :href="route('expenses.show', expense.id)">
                <NxButton variant="secondary">Detaya Dön</NxButton>
            </Link>
        </NxPageHeader>
        <ExpenseForm
            :expense="expense"
            :categories="categories"
            :cash-accounts="cashAccounts"
        />
    </NxLayout>
</template>
