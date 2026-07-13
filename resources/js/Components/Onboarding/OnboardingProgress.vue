<script setup>
import { computed, ref } from 'vue';
import { CheckCircle2, ChevronDown, Circle } from 'lucide-vue-next';

const props = defineProps({
    onboarding: {
        type: Object,
        default: () => null,
    },
});

const open = ref(false);
const progress = computed(() => props.onboarding?.progress ?? 0);
const tasks = computed(() => props.onboarding?.tasks ?? []);
const completed = computed(() => tasks.value.filter(task => task.completed).length);
</script>

<template>
    <div
        v-if="onboarding && !onboarding.completed"
        class="relative"
    >
        <button
            type="button"
            @click="open = !open"
            class="flex min-w-[230px] items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left shadow-sm transition hover:border-blue-200 hover:bg-blue-50/40"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-sm font-black text-blue-700">
                %{{ progress }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-3">
                    <p class="truncate text-sm font-black text-slate-900">Kurulum İlerlemesi</p>
                    <ChevronDown class="h-4 w-4 text-slate-400" />
                </div>

                <div class="mt-2 h-2 rounded-full bg-slate-100">
                    <div
                        class="h-2 rounded-full bg-blue-600 transition-all"
                        :style="{ width: `${progress}%` }"
                    />
                </div>

                <p class="mt-1 text-xs font-semibold text-slate-500">
                    {{ completed }} / {{ tasks.length }} görev tamamlandı
                </p>
            </div>
        </button>

        <div
            v-if="open"
            class="absolute right-0 z-40 mt-3 w-[360px] rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl"
        >
            <div class="mb-4">
                <h3 class="font-black text-slate-950">Başlangıç görevleri</h3>
                <p class="mt-1 text-sm text-slate-500">Her görev gerçek kayıt oluşturulduğunda otomatik tamamlanır.</p>
            </div>

            <div class="space-y-3">
                <div
                    v-for="task in tasks"
                    :key="task.key"
                    class="flex items-center gap-3 rounded-xl border border-slate-100 p-3"
                >
                    <CheckCircle2
                        v-if="task.completed"
                        class="h-5 w-5 text-emerald-600"
                    />

                    <Circle
                        v-else
                        class="h-5 w-5 text-slate-300"
                    />

                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ task.label }}</p>
                        <p class="text-xs text-slate-500">
                            {{ task.completed ? 'Tamamlandı' : `%${task.weight} ilerleme sağlar` }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        v-else-if="onboarding"
        class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-black text-emerald-700"
    >
        <CheckCircle2 class="h-5 w-5" />
        Kurulum tamamlandı
    </div>
</template>
