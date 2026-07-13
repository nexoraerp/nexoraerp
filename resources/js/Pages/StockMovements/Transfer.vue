<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftRight } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxNumberInput from '@/Components/UI/NxNumberInput.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';
import NxTextarea from '@/Components/UI/NxTextarea.vue';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
    warehouses: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    product_id: '',
    from_warehouse_id: '',
    to_warehouse_id: '',
    quantity: '',
    unit_cost: '',
    description: '',
});

const selectedProduct = computed(() => props.products.find(product => String(product.value) === String(form.product_id)));

const submit = () => {
    form.post(route('stock-movements.transfer.store'));
};
</script>

<template>
    <Head title="Depolar Arası Transfer" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                title="Depolar Arası Transfer"
                subtitle="Ürünü bir depodan diğer depoya kontrollü stok hareketiyle aktarın."
            >
                <Link :href="route('stock-movements.index')">
                    <NxButton variant="secondary">
                        Stok Hareketlerine Dön
                    </NxButton>
                </Link>
            </NxPageHeader>

            <NxCard>
                <form
                    class="space-y-6"
                    @submit.prevent="submit"
                >
                    <div class="grid gap-5 xl:grid-cols-2">
                        <NxSelect
                            v-model="form.product_id"
                            label="Ürün"
                            :options="products"
                            option-label="label"
                            option-value="value"
                            :error="form.errors.product_id"
                        />

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                            <div class="text-sm font-bold text-slate-500">Seçili Ürün Stok Bilgisi</div>
                            <div class="mt-2 text-2xl font-black text-slate-950 dark:text-slate-100">
                                {{ selectedProduct ? `${selectedProduct.stock} ${selectedProduct.unit ?? ''}` : '-' }}
                            </div>
                        </div>

                        <NxSelect
                            v-model="form.from_warehouse_id"
                            label="Kaynak Depo"
                            :options="warehouses"
                            option-label="label"
                            option-value="value"
                            :error="form.errors.from_warehouse_id"
                        />

                        <NxSelect
                            v-model="form.to_warehouse_id"
                            label="Hedef Depo"
                            :options="warehouses"
                            option-label="label"
                            option-value="value"
                            :error="form.errors.to_warehouse_id"
                        />

                        <NxNumberInput
                            v-model="form.quantity"
                            label="Transfer Miktarı"
                            :error="form.errors.quantity"
                        />

                        <NxNumberInput
                            v-model="form.unit_cost"
                            label="Birim Maliyet"
                            :error="form.errors.unit_cost"
                        />
                    </div>

                    <NxTextarea
                        v-model="form.description"
                        label="Açıklama"
                        placeholder="Transfer sebebi veya sevk notu"
                        :error="form.errors.description"
                    />

                    <div class="flex justify-end">
                        <NxButton
                            type="submit"
                            :disabled="form.processing"
                        >
                            <ArrowLeftRight class="mr-2 h-4 w-4" />
                            {{ form.processing ? 'Transfer ediliyor...' : 'Transferi Tamamla' }}
                        </NxButton>
                    </div>
                </form>
            </NxCard>
        </div>
    </NxLayout>
</template>
