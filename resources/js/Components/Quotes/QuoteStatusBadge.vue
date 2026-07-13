<script setup>
defineProps({
    status: {
        type: String,
        required: true,
    },
});

const classes = {
    Draft: 'bg-slate-100 text-slate-700',
    Sent: 'bg-blue-100 text-blue-700',
    Accepted: 'bg-emerald-100 text-emerald-700',
    Rejected: 'bg-red-100 text-red-700',
    Expired: 'bg-amber-100 text-amber-700',
    Converted: 'bg-violet-100 text-violet-700',
    Cancelled: 'bg-slate-200 text-slate-600',
};

const labels = {
    Draft: 'Taslak',
    Sent: 'Gönderildi',
    Accepted: 'Kabul Edildi',
    Rejected: 'Reddedildi',
    Expired: 'Süresi Doldu',
    Converted: 'Satışa Dönüştü',
    Cancelled: 'İptal',
};
</script>

<template>
    <span
        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
        :class="classes[status] ?? classes.Draft"
    >
        {{ labels[status] ?? status }}
    </span>
</template>
