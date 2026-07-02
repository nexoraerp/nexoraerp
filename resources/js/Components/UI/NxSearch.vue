<script setup>
defineProps({
    modelValue: String,
    placeholder: String,
});

defineEmits(['update:modelValue']);
</script>

<template>

<div class="relative">

    <input
        :value="modelValue"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
        class="w-full rounded-xl border border-slate-300
               py-3 pl-12 pr-4
               focus:ring-2 focus:ring-blue-200
               focus:border-blue-500 outline-none"
    />

    <div
        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">

        🔍

    </div>

</div>

</template>