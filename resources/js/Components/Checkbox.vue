<script setup>

const model = defineModel('checked');

const props = defineProps({
    value: {
        type: String,
        default: null,
    },
});

</script>

<template>
    <input
        v-model="model"
        type="checkbox"
        :value="value"
        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
    >
</template>
