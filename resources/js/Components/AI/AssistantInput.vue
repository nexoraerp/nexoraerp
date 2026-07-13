<script setup>
import { computed, onBeforeUnmount, ref } from 'vue'
import { Mic, MicOff, SendHorizontal } from 'lucide-vue-next'

const emit = defineEmits(['send'])

const message = ref('')
const listening = ref(false)
const speechError = ref('')

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
const supportsSpeech = Boolean(SpeechRecognition)
let recognition = null

if (supportsSpeech) {
    recognition = new SpeechRecognition()
    recognition.lang = 'tr-TR'
    recognition.continuous = false
    recognition.interimResults = true

    recognition.onresult = (event) => {
        const transcript = Array.from(event.results)
            .map(result => result[0]?.transcript ?? '')
            .join('')

        message.value = transcript
    }

    recognition.onerror = () => {
        speechError.value = 'Mikrofon dinlenemedi.'
        listening.value = false
    }

    recognition.onend = () => {
        listening.value = false
    }
}

const canSubmit = computed(() => message.value.trim().length > 0)

function submit() {

    if (!canSubmit.value) return

    emit('send', message.value)

    message.value = ''
}

function toggleListening() {
    speechError.value = ''

    if (!supportsSpeech || !recognition) {
        speechError.value = 'Tarayıcı mikrofonla yazmayı desteklemiyor.'
        return
    }

    if (listening.value) {
        recognition.stop()
        listening.value = false
        return
    }

    listening.value = true
    recognition.start()
}

onBeforeUnmount(() => {
    if (recognition && listening.value) {
        recognition.stop()
    }
})
</script>

<template>

<div class="border-t border-slate-200 p-4">

    <div class="flex items-center gap-2">

        <button
            type="button"
            @click="toggleListening"
            :class="[
                'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border transition',
                listening
                    ? 'border-red-200 bg-red-50 text-red-700'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300 hover:text-blue-700'
            ]"
            :title="listening ? 'Dinlemeyi durdur' : 'Sesle yaz'"
        >
            <MicOff
                v-if="listening"
                class="h-5 w-5"
            />

            <Mic
                v-else
                class="h-5 w-5"
            />
        </button>

        <input
            v-model="message"
            @keyup.enter="submit"
            type="text"
            placeholder="Nexora AI'ya yaz veya mikrofonla konuş..."
            class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >

        <button
            type="button"
            @click="submit"
            :disabled="!canSubmit"
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300"
            title="Gönder"
        >
            <SendHorizontal class="h-5 w-5" />
        </button>

    </div>

    <p
        v-if="speechError"
        class="mt-2 text-xs font-semibold text-red-600"
    >
        {{ speechError }}
    </p>

</div>

</template>
