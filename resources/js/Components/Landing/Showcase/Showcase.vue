<script setup>
import ShowcaseHeader from "./ShowcaseHeader.vue";
import ShowcaseTabs from "./ShowcaseTabs.vue";
import ShowcaseViewer from "./ShowcaseViewer.vue";
</script>

<template>

<section
    id="modules"
    class="relative overflow-hidden bg-slate-50 py-32">

    <div class="mx-auto max-w-[1600px] px-6 lg:px-10">

        <ShowcaseHeader />

        <div class="mt-20">

            <ShowcaseTabs />

        </div>

        <div class="mt-14">

            <ShowcaseViewer />

        </div>

    </div>

</section>

</template>