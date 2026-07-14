<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, AlertCircle } from 'lucide-vue-next';
import Sidebar from '@/Components/Navigation/Sidebar.vue';
import Topbar from '@/Components/Navigation/Topbar.vue';
import MobileBottomNav from '@/Components/Navigation/MobileBottomNav.vue';
import AssistantWidget from '@/Components/AI/AssistantWidget.vue';

const page = usePage();
const aiBriefing = computed(() => page.props.aiBriefing ?? null);
const flash = computed(() => page.props.flash ?? {});
</script>

<template>

<div class="flex min-h-screen bg-slate-100 transition-colors dark:bg-slate-950">

    <Sidebar />

    <div class="flex-1 flex flex-col">

        <Topbar />

        <div
            v-if="flash.success || flash.error"
            class="px-4 pt-4 sm:px-6 lg:px-8 lg:pt-6"
        >
            <div
                :class="[
                    'flex items-center gap-3 rounded-2xl border px-5 py-4 text-sm font-bold shadow-sm',
                    flash.success
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
                        : 'border-red-200 bg-red-50 text-red-800'
                ]"
            >
                <CheckCircle2
                    v-if="flash.success"
                    class="h-5 w-5"
                />

                <AlertCircle
                    v-else
                    class="h-5 w-5"
                />

                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <main class="flex-1 px-4 pb-28 pt-4 sm:px-6 sm:pt-6 lg:p-8">

            <slot />

        </main>

    </div>

    <AssistantWidget :briefing="aiBriefing" />
    <MobileBottomNav />

</div>

</template>
