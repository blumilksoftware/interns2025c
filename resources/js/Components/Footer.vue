<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <footer class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-8 sm:py-12 md:flex md:items-center md:justify-between lg:px-8">
      <p class="mt-4 sm:mt-8 text-center text-xs sm:text-sm/6 text-gray-600 md:order-1 md:mt-0">&copy; 2025 LapGo, Inc. {{ t('landing.footer.copyright') }}.</p>
    </div>
  </footer>
</template>
  
  <style scoped>
  </style>
