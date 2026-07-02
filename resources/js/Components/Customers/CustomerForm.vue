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

const emit = defineEmits(['submit']);
</script>

<template>

    <NxCard>

        <form @submit.prevent="$emit('submit')">

            <h2 class="text-2xl font-semibold mb-8">
                Cari Bilgileri
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <NxInput
                    label="Cari Kodu"
                    v-model="form.code"
                />

                <NxInput
                    label="Cari Adı"
                    v-model="form.name"
                />

                <NxInput
                    label="Firma"
                    v-model="form.company"
                />

                <NxInput
                    label="Telefon"
                    v-model="form.phone"
                />

                <NxInput
                    label="E-Posta"
                    type="email"
                    v-model="form.email"
                />

                <NxInput
                    label="Vergi No"
                    v-model="form.tax_number"
                />

                <NxInput
                    label="Vergi Dairesi"
                    v-model="form.tax_office"
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