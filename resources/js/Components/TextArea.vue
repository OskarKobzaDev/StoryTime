<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel();

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        ref="input"
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
        ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-green-600 sm:text-sm sm:leading-6"
        v-model="model"
    />
</template>
