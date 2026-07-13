<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

defineProps({
    form: Object,

    warehouses: {
        type: Array,
        default: () => [],
    },

    definitions: {
        type: Object,
        default: () => ({
            category: [],
            brand: [],
            model: [],
            unit: [],
        }),
    },

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
                :error="form.errors.code"
            />

            <NxInput
                label="Barkod"
                v-model="form.barcode"
                :error="form.errors.barcode"
            />

            <NxInput
                label="Ürün Adı"
                v-model="form.name"
                :error="form.errors.name"
            />

            <NxSelect
                label="Kategori"
                v-model="form.category"
                :options="definitions.category ?? []"
                option-label="label"
                option-value="value"
                :error="form.errors.category"
            />

            <NxSelect
                label="Marka"
                v-model="form.brand"
                :options="definitions.brand ?? []"
                option-label="label"
                option-value="value"
                :error="form.errors.brand"
            />

            <NxSelect
                label="Model"
                v-model="form.model"
                :options="definitions.model ?? []"
                option-label="label"
                option-value="value"
                :error="form.errors.model"
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
                :error="form.errors.purchase_price"
            />

            <NxInput
                type="number"
                label="Satış Fiyatı"
                v-model="form.sale_price"
                :error="form.errors.sale_price"
            />

            <NxInput
                type="number"
                label="KDV (%)"
                v-model="form.vat"
                :error="form.errors.vat"
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
                :error="form.errors.stock"
            />

            <NxSelect
                label="Stok Deposu"
                v-model="form.warehouse_id"
                :options="warehouses"
                option-label="label"
                option-value="value"
                :error="form.errors.warehouse_id"
            />

            <NxInput
                type="number"
                label="Minimum Stok"
                v-model="form.min_stock"
                :error="form.errors.min_stock"
            />

            <NxSelect
                label="Birim"
                v-model="form.unit"
                :options="definitions.unit ?? []"
                option-label="label"
                option-value="value"
                :error="form.errors.unit"
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
