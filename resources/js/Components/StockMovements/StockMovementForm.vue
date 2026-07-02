<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxNumberInput from '@/Components/UI/NxNumberInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxTextarea from '@/Components/UI/NxTextarea.vue';
import NxButton from '@/Components/UI/NxButton.vue';

defineProps({
    form: Object,

    products: {
        type: Array,
        default: () => [],
    },

    warehouses: {
        type: Array,
        default: () => [],
    },

    types: {
        type: Array,
        default: () => [],
    },

    buttonText: {
        type: String,
        default: 'Kaydet',
    },
});

const emit = defineEmits([
    'submit',
]);
</script>

<template>

<NxCard>

<form
    @submit.prevent="emit('submit')"
    class="space-y-6"
>

    <NxSelect
        label="Ürün"
        v-model="form.product_id"
        :options="products"
        option-label="label"
        option-value="value"
        :error="form.errors.product_id"
    />

    <NxSelect
        label="Depo"
        v-model="form.warehouse_id"
        :options="warehouses"
        option-label="label"
        option-value="value"
        :error="form.errors.warehouse_id"
    />

    <NxSelect
        label="Hareket Tipi"
        v-model="form.type"
        :options="types.map(type => ({
            value: type,
            label: type,
        }))"
        option-label="label"
        option-value="value"
        :error="form.errors.type"
    />

    <NxNumberInput
        label="Miktar"
        v-model="form.quantity"
        :error="form.errors.quantity"
    />

    <NxNumberInput
        label="Birim Maliyet"
        v-model="form.unit_cost"
        :error="form.errors.unit_cost"
    />

    <NxTextarea
        label="Açıklama"
        v-model="form.description"
        :error="form.errors.description"
    />

    <div class="flex justify-end">

        <NxButton
            type="submit"
            :disabled="form.processing"
        >

            {{ form.processing ? 'Kaydediliyor...' : buttonText }}

        </NxButton>

    </div>

</form>

</NxCard>

</template>