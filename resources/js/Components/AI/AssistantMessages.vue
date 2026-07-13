<script setup>
import { nextTick, ref, watch } from 'vue'

const props = defineProps({

    messages: Array,

    loading: Boolean

})

const list = ref(null)

const scrollToBottom = async () => {
    await nextTick()

    if (list.value) {
        list.value.scrollTop = list.value.scrollHeight
    }
}

watch(
    () => [props.messages?.length, props.loading],
    scrollToBottom,
    { immediate: true }
)
</script>

<template>

<div
    ref="list"
    class="flex-1 overflow-y-auto p-5"
>

    <div
        v-for="(item,index) in messages"
        :key="index"
        class="mb-4"
    >

        <div
            v-if="item.role==='assistant'"
            class="whitespace-pre-line rounded-xl bg-slate-100 p-4"
        >

            {{ item.content }}

        </div>

        <div
            v-else
            class="ml-16 whitespace-pre-line rounded-xl bg-blue-600 p-4 text-white"
        >

            {{ item.content }}

        </div>

    </div>

    <div
        v-if="loading"
        class="text-sm text-gray-500"
    >

        🤖 Nexora düşünüyor...

    </div>

</div>

</template>
