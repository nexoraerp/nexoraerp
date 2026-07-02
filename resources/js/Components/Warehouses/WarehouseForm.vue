<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxTextarea from '@/Components/UI/NxTextarea.vue';
import NxButton from '@/Components/UI/NxButton.vue';

defineProps({
    form: {
        type: Object,
        required: true,
    },

    buttonText: {
        type: String,
        default: 'Kaydet',
    },
});

defineEmits(['submit']);
</script>

<template>

    <NxCard>

        <form @submit.prevent="$emit('submit')">

            <h2 class="text-2xl font-semibold mb-8">
                Depo Bilgileri
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <NxInput
                    label="Depo Kodu"
                    v-model="form.code"
                />

                <NxInput
                    label="Depo Adı"
                    v-model="form.name"
                />

                <NxInput
                    label="Sorumlu"
                    v-model="form.manager"
                />

                <NxInput
                    label="Telefon"
                    v-model="form.phone"
                />

                <div class="md:col-span-2">

                    <NxTextarea
                        label="Adres"
                        v-model="form.address"
                    />

                </div>

            </div>

            <div class="mt-8 flex justify-end">

                <NxButton type="submit">
                    {{ buttonText }}
                </NxButton>

            </div>

        </form>

    </NxCard>

</template>