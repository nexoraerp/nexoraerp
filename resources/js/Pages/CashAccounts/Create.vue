<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const form = useForm({

    code: '',

    name: '',

    currency: 'TRY',

    notes: '',

});

const save = () => {

    form.post(route('cash-accounts.store'));

};
</script>

<template>

<Head title="Yeni Kasa" />

<NxLayout>

    <NxPageHeader
        title="Yeni Kasa"
        subtitle="Yeni kasa hesabı oluşturun."
    />

    <form @submit.prevent="save">

        <NxCard>

            <div class="grid grid-cols-2 gap-6">

                <NxInput
                    label="Kasa Kodu"
                    v-model="form.code"
                />

                <NxInput
                    label="Kasa Adı"
                    v-model="form.name"
                />

                <NxSelect
                    label="Para Birimi"
                    v-model="form.currency"
                    :options="[
                        { value:'TRY', label:'TRY' },
                        { value:'USD', label:'USD' },
                        { value:'EUR', label:'EUR' }
                    ]"
                />

                <NxInput
                    label="Açıklama"
                    v-model="form.notes"
                />

            </div>

        </NxCard>

        <div class="flex justify-end mt-6">

            <NxButton
                type="submit"
            >

                Kaydet

            </NxButton>

        </div>

    </form>

</NxLayout>

</template>