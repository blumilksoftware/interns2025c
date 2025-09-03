<script setup>
defineProps({
  value: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <label class="block text-gray-900 text-sm transition-all duration-150 ease-out font-semibold">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>
