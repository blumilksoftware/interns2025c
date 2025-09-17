<script setup>
import { useI18n } from 'vue-i18n'
import { IconBrandGithub } from '@tabler/icons-vue'
import Logo from '@/Components/Logo.vue'
const { t } = useI18n()
</script>

<template>
  <footer class="bg-white">
    <div class="mx-auto max-w-7xl py-6 sm:py-6 mt-12 md:flex md:items-center md:justify-between border-t-2 border-gray-100">
      <p class="text-center md:text-left text-sm text-gray-500 inline-flex items-center gap-2">
        <Logo class="size-12" />
        &copy; 2025 ŁapGo, Inc. {{ t('landing.footer.copyright') }}.
      </p>
      <div class="mt-4 md:mt-0 flex justify-center md:justify-end">
        <a
          href="https://github.com/blumilksoftware/interns2025c/"
          target="_blank"
          rel="noopener noreferrer"
          aria-label="GitHub"
          class="text-gray-500 hover:text-gray-700 transition-colors"
        >
          <IconBrandGithub class="size-6" />
        </a>
      </div>
    </div>
  </footer>
</template>
  
  <style scoped>
  </style>
