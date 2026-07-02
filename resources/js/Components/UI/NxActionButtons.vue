<script setup>
import { Link } from '@inertiajs/vue3';
import { Pencil, Eye, Trash2 } from 'lucide-vue-next';

defineProps({
    editUrl: String,
    showUrl: String,
});

const emit = defineEmits(['delete']);
</script>

<template>

<div class="flex justify-end gap-2">

    <Link
        :href="editUrl"
        class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200"
    >
        <Pencil class="w-4 h-4" />
    </Link>

    <Link
        :href="showUrl"
        class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"
    >
        <Eye class="w-4 h-4" />
    </Link>

    <button
        @click="$emit('delete')"
        class="p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200"
    >
        <Trash2 class="w-4 h-4" />
    </button>

</div>

</template>