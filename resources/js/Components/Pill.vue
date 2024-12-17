<script setup>

import {Link} from "@inertiajs/vue3";

defineProps({
  href: {
    required: true,
  },

  filled: {
    default: false,
  },

});
</script>

<template>
  <Link :href="href"
        class="whitespace-nowrap mb-2 rounded-full font-bold py-0.5 px-2 border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white"
        :class="{ '!bg-green-500 !text-white': filled }"
  >
    <slot/>
  </Link>
</template>

<style scoped>

</style>
