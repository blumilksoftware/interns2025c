<script setup>
import { useI18n } from 'vue-i18n'
import { IconBrandGithub } from '@tabler/icons-vue'
import Logo from '@/Components/Logo.vue'
const { t } = useI18n()
</script>

<template>
  <footer class="bg-white w-full">
    <div class="mx-auto max-w-7xl py-6 sm:py-6 mt-12 border-t-2 border-gray-100 px-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="flex flex-col md:flex-row items-center gap-2 sm:gap-4 w-full md:w-auto">
        <Logo class="size-10 sm:size-12 mb-2 sm:mb-0 hidden md:block" />
        <p class="text-center sm:text-left text-sm text-gray-500">
          &copy; 2025 ŁapGo, Inc. {{ t('landing.footer.copyright') }}.
        </p>
      </div>
      <div class="flex justify-center md:justify-end w-full md:w-auto">
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
