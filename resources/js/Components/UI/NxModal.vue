<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    title: {
        type: String,
        default: 'Onay',
    },
});

const emit = defineEmits([
    'close',
    'confirm',
]);
</script>

<template>

<Teleport to="body">

    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center"
    >

        <!-- Arka plan -->

        <div
            class="absolute inset-0 bg-black/50"
            @click="$emit('close')"
        />

        <!-- Modal -->

        <div
            class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
        >

            <h2 class="text-xl font-bold mb-4">
                {{ title }}
            </h2>

            <div class="text-slate-600">

                <slot />

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <button
                    class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300"
                    @click="$emit('close')"
                >
                    İptal
                </button>

                <button
                    class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700"
                    @click="$emit('confirm')"
                >
                    Sil
                </button>

            </div>

        </div>

    </div>

</Teleport>

</template>