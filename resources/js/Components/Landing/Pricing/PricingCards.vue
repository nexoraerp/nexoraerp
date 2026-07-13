<script setup>
import PricingCard from "./PricingCard.vue";
import { plans } from "./pricingData";
</script>

<template>

<div
    class="mt-20 grid gap-8 lg:grid-cols-3">

    <PricingCard
        v-for="plan in plans"
        :key="plan.name"
        :plan="plan"/>

</div>

</template>