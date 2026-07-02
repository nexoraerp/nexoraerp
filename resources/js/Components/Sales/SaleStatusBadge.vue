<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        default: 'Draft',
    },
});

const badge = computed(() => {

    switch (props.status) {

        case 'Completed':
            return {
                text: 'Tamamlandı',
                class: 'bg-emerald-100 text-emerald-700 border-emerald-200',
                icon: '🟢',
            };

        case 'Cancelled':
            return {
                text: 'İptal Edildi',
                class: 'bg-red-100 text-red-700 border-red-200',
                icon: '🔴',
            };

        default:
            return {
                text: 'Taslak',
                class: 'bg-amber-100 text-amber-700 border-amber-200',
                icon: '🟡',
            };

    }

});
</script>

<template>

<div
    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border font-semibold text-sm"
    :class="badge.class"
>
    <span>{{ badge.icon }}</span>

    <span>{{ badge.text }}</span>
</div>

</template>