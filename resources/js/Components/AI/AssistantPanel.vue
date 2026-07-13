<script setup>

import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

import AssistantHeader from './AssistantHeader.vue'
import AssistantMessages from './AssistantMessages.vue'
import AssistantInput from './AssistantInput.vue'

const props = defineProps({
    briefing: {
        type: Object,
        default: () => null,
    },
})

const formatBriefing = () => {
    if (!props.briefing) {
        return 'Merhaba. Ben Nexora AI Assistant.';
    }

    const levelIcon = {
        danger: '🔴',
        warning: '🟠',
        success: '🟢',
    };

    const highlights = (props.briefing.highlights ?? [])
        .map(item => `${levelIcon[item.level] ?? '•'} ${item.text}`)
        .join('\n\n');

    return [
        'Nexora AI',
        '',
        `${props.briefing.greeting}.`,
        '',
        props.briefing.headline,
        '',
        highlights,
        '',
        'Detayları Gör ile risk analizine geçebilirsiniz.',
    ].join('\n');
}

const messages = ref([
    {
        role: 'assistant',
        content: formatBriefing()
    }
])

const loading = ref(false)

async function sendMessage(message) {

    messages.value.push({
        role: 'user',
        content: message
    })

    loading.value = true

    try {

        const { data } = await axios.post('/ai/chat', {
            message
        })

        messages.value.push({
            role: 'assistant',
            content: data.message ?? data.content ?? 'Yanıt alınamadı.'
        })

    } catch (error) {

    console.error(error)

    messages.value.push({
        role: 'assistant',
        content:
            error.response?.data?.message ??
            error.message ??
            'Bilinmeyen hata'
    })

} finally {

        loading.value = false

    }

}

</script>

<template>

<div class="flex h-full flex-col">

    <AssistantHeader />

    <AssistantMessages
        :messages="messages"
        :loading="loading"
    />

    <div
        v-if="briefing?.detail_route"
        class="border-t border-slate-100 px-5 py-3"
    >
        <Link
            :href="briefing.detail_route"
            class="flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-3 text-sm font-bold text-white hover:bg-slate-800"
        >
            Detayları Gör
        </Link>
    </div>

    <AssistantInput
        @send="sendMessage"
    />

</div>

</template>
