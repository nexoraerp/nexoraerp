<script setup>
import { Head } from '@inertiajs/vue3';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxCard from '@/Components/UI/NxCard.vue';

const props = defineProps({

    cashAccount: Object,

    summary: Object,

    movements: {
        type: Array,
        default: () => [],
    },

});
</script>

<template>

<Head title="Kasa Detayı" />

<NxLayout>

    <NxPageHeader
        title="Kasa Detayı"
        subtitle="Kasa hareketlerini görüntüleyin."
    />

    <!-- Özet Kartları -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <NxCard>

            <p class="text-sm text-slate-500">

                Güncel Bakiye

            </p>

            <h2 class="mt-3 text-3xl font-bold text-blue-600">

                ₺ {{ Number(summary.balance).toLocaleString('tr-TR',{
                    minimumFractionDigits:2
                }) }}

            </h2>

        </NxCard>

        <NxCard>

            <p class="text-sm text-slate-500">

                Toplam Giriş

            </p>

            <h2 class="mt-3 text-3xl font-bold text-green-600">

                ₺ {{ Number(summary.total_in).toLocaleString('tr-TR',{
                    minimumFractionDigits:2
                }) }}

            </h2>

        </NxCard>

        <NxCard>

            <p class="text-sm text-slate-500">

                Toplam Çıkış

            </p>

            <h2 class="mt-3 text-3xl font-bold text-red-600">

                ₺ {{ Number(summary.total_out).toLocaleString('tr-TR',{
                    minimumFractionDigits:2
                }) }}

            </h2>

        </NxCard>

        <NxCard>

            <p class="text-sm text-slate-500">

                Hareket Sayısı

            </p>

            <h2 class="mt-3 text-3xl font-bold">

                {{ summary.movement_count }}

            </h2>

        </NxCard>

    </div>

    <!-- Kasa Bilgileri -->

    <NxCard class="mt-6">

        <div class="grid grid-cols-2 gap-8">

            <div>

                <p class="text-sm text-slate-500">

                    Kasa Kodu

                </p>

                <h3 class="text-xl font-semibold mt-2">

                    {{ cashAccount.code }}

                </h3>

            </div>

            <div>

                <p class="text-sm text-slate-500">

                    Kasa Adı

                </p>

                <h3 class="text-xl font-semibold mt-2">

                    {{ cashAccount.name }}

                </h3>

            </div>

            <div>

                <p class="text-sm text-slate-500">

                    Para Birimi

                </p>

                <h3 class="text-xl font-semibold mt-2">

                    {{ cashAccount.currency }}

                </h3>

            </div>

            <div>

                <p class="text-sm text-slate-500">

                    Durum

                </p>

                <h3 class="text-xl font-semibold mt-2">

                    {{ cashAccount.is_active ? 'Aktif' : 'Pasif' }}

                </h3>

            </div>

        </div>

    </NxCard>

    <!-- Hareketler -->

    <NxCard class="mt-6">

        <div class="mb-6">

            <h2 class="text-2xl font-bold">

                Kasa Hareketleri

            </h2>

        </div>

        <table class="w-full">

            <thead class="border-b">

                <tr>

                    <th class="text-left py-4">

                        Tarih

                    </th>

                    <th class="text-left py-4">

                        Tür

                    </th>

                    <th class="text-left py-4">

                        Açıklama

                    </th>

                    <th class="text-right py-4">

                        Giriş

                    </th>

                    <th class="text-right py-4">

                        Çıkış

                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="movement in movements"
                    :key="movement.id"
                    class="border-b"
                >

                    <td class="py-4">

                        {{ movement.movement_date }}

                    </td>

                    <td>

                        {{ movement.type }}

                    </td>

                    <td>

                        {{ movement.description }}

                    </td>

                    <td class="text-right text-green-600 font-semibold">

                        {{ Number(movement.debit).toLocaleString('tr-TR',{
                            minimumFractionDigits:2
                        }) }}

                    </td>

                    <td class="text-right text-red-600 font-semibold">

                        {{ Number(movement.credit).toLocaleString('tr-TR',{
                            minimumFractionDigits:2
                        }) }}

                    </td>

                </tr>

                <tr v-if="movements.length===0">

                    <td
                        colspan="5"
                        class="py-10 text-center text-slate-500"
                    >

                        Henüz hareket bulunmuyor.

                    </td>

                </tr>

            </tbody>

        </table>

    </NxCard>

</NxLayout>

</template>