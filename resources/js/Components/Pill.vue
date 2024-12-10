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
        class="whitespace-nowrap mb-2 rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 hover:bg-indigo-400 hover:text-white"
        :class="{ '!bg-indigo-400 !text-white': filled }"
  >
    <slot/>
  </Link>
</template>

<style scoped>

</style>
