<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxTextarea from '@/Components/UI/NxTextarea.vue';

defineProps({
    form: Object,
});
</script>

<template>

<NxCard class="mt-6">

    <div class="flex items-center gap-3 mb-5">

        <div
            class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"
        >

            📝

        </div>

        <div>

            <h2 class="font-bold text-lg">

                Satış Notları

            </h2>

            <p class="text-slate-500 text-sm">

                Bu not satış belgesinde görüntülenebilir.

            </p>

        </div>

    </div>

    <NxTextarea
        v-model="form.notes"
        rows="5"
        placeholder="Teslimat bilgisi, müşteri notu, özel açıklamalar..."
    />

</NxCard>

</template>