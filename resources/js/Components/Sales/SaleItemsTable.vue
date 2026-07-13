<script setup>
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const onProductChange = (item) => {

const product = props.products.find(
p => p.value == item.product_id
)                                    ;

if (!product) {
return          ;
}

item.unit_price = product.price ;
item.vat = product.vat          ;

item.stock = product.stock ;
item.unit = product.unit   ;

item.brand = product.brand  ;
item.model = product.model  ;
}                           ;
const props = defineProps({
products: {
type: Array,
default: () => [],
},

warehouses: {
type: Array,
default: () => [],
},

items: {
type: Array,
default: () => [],
},

errors: {
type: Object,
default: () => ({}),
},
})                 ;

defineEmits([
'add-item',
'remove-item',
])             ;
</script>

<template>

<NxCard>

<div class="flex items-center justify-between mb-6">

<h2 class="text-xl font-bold">
📦 Ürünler
</h2>

<NxButton
type="button"
@click="$emit('add-item')"
>
+ Ürün Ekle
</NxButton>

</div>

<div class="space-y-5">

<div
v-for="(item, index) in items"
:key="index"
class="grid grid-cols-12 gap-4 border rounded-2xl p-5"
>

<!-- Ürün -->

<div class="col-span-12 md:col-span-3">

<NxSelect
label="Ürün"
v-model="item.product_id"
:options="products"
option-label="label"
option-value="value"
@update:modelValue="onProductChange(item)"
:error="errors[`items.${index}.product_id`]"
/>

</div>

<!-- Depo -->

<div class="col-span-12 md:col-span-2">

<NxSelect
label="Depo"
v-model="item.warehouse_id"
:options="warehouses"
option-label="label"
option-value="value"
:error="errors[`items.${index}.warehouse_id`]"
/>

</div>

<!-- Adet -->

<div class="col-span-6 md:col-span-1">

<NxInput
label="Adet"
type="number"
v-model="item.quantity"
:error="errors[`items.${index}.quantity`]"
/>

</div>

<!-- Fiyat -->

<div class="col-span-6 md:col-span-2">

<NxInput
label="Fiyat"
type="number"
v-model="item.unit_price"
:error="errors[`items.${index}.unit_price`]"
/>

</div>

<!-- İskonto -->

<div class="col-span-6 md:col-span-2">

<NxInput
label="İskonto"
type="number"
v-model="item.discount"
:error="errors[`items.${index}.discount`]"
/>

</div>

<!-- Sil -->

<div class="col-span-6 md:col-span-1 flex items-end">

<NxButton
type="button"
variant="danger"
class="w-full"
@click="$emit('remove-item', index)"
>
Sil
</NxButton>

</div>

</div>

<div
v-if="items.length === 0"
class="border-2 border-dashed rounded-2xl p-12 text-center"
>

<div class="text-5xl mb-4">
📦
</div>

<p class="font-semibold">
Henüz ürün eklenmedi.
</p>

<p class="text-slate-500 mt-2">
İlk ürünü eklemek için yukarıdaki butonu kullanın.
</p>

</div>

</div>

</NxCard>

</template>
