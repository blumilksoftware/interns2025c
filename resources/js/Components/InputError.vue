<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  message: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-red-600 dark:text-red-400">
      {{ t(message) }}
    </p>
  </div>
</template>
