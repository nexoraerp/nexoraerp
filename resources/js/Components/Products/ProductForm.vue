<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

defineProps({
    form: Object,

    buttonText: {
        type: String,
        default: 'Kaydet',
    },
});

defineEmits(['submit']);
</script>

<template>

<form @submit.prevent="$emit('submit')">

    <!-- Ürün Bilgileri -->

    <NxCard class="mb-6">

        <h2 class="text-xl font-semibold mb-6">
            Ürün Bilgileri
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <NxInput
                label="Ürün Kodu"
                v-model="form.code"
            />

            <NxInput
                label="Barkod"
                v-model="form.barcode"
            />

            <NxInput
                label="Ürün Adı"
                v-model="form.name"
            />

            <NxSelect
                label="Kategori"
                v-model="form.category"
                :options="[
                    'Panel',
                    'İnverter',
                    'Akü',
                    'Kablo',
                    'Aksesuar',
                    'Montaj'
                ]"
            />

            <NxSelect
                label="Marka"
                v-model="form.brand"
                :options="[
                    'Lexron',
                    'Must',
                    'Deye',
                    'Huawei',
                    'Victron',
                    'Growatt'
                ]"
            />

            <NxInput
                label="Model"
                v-model="form.model"
            />

        </div>

    </NxCard>

    <!-- Fiyat Bilgileri -->

    <NxCard class="mb-6">

        <h2 class="text-xl font-semibold mb-6">
            Fiyat Bilgileri
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <NxInput
                type="number"
                label="Alış Fiyatı"
                v-model="form.purchase_price"
            />

            <NxInput
                type="number"
                label="Satış Fiyatı"
                v-model="form.sale_price"
            />

            <NxInput
                type="number"
                label="KDV (%)"
                v-model="form.vat"
            />

        </div>

    </NxCard>

    <!-- Stok Bilgileri -->

    <NxCard>

        <h2 class="text-xl font-semibold mb-6">
            Stok Bilgileri
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <NxInput
                type="number"
                label="Mevcut Stok"
                v-model="form.stock"
            />

            <NxInput
                type="number"
                label="Minimum Stok"
                v-model="form.min_stock"
            />

            <NxSelect
                label="Birim"
                v-model="form.unit"
                :options="[
                    'Adet',
                    'Metre',
                    'Takım',
                    'Kutu',
                    'Paket'
                ]"
            />

        </div>

        <div class="flex justify-end mt-8">

            <NxButton type="submit">
                {{ buttonText }}
            </NxButton>

        </div>

    </NxCard>

</form>

</template>