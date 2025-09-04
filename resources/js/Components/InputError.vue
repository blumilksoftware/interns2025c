<script setup>
defineProps({
  message: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div v-if="message">
    <p class="text-sm text-red-600 dark:text-red-400 font-semibold transition-all duration-150 ease-out">
      {{ message }}
    </p>
  </div>
</template>
