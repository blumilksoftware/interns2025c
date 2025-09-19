<script setup>
import { useI18n } from 'vue-i18n'
import AppLayout from '@/Layouts/AppLayout.vue'
import { IconMail, IconPhone, IconBrandGithub } from '@tabler/icons-vue'
const { t } = useI18n()
</script>

<template>
  <AppLayout :title="t('contact.title')">
    <section class="mx-auto max-w-5xl px-6 sm:px-8 py-10 sm:py-16">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
        {{ t('contact.title') }}
      </h1>
      <p class="text-base sm:text-lg text-gray-700 max-w-3xl">
        {{ t('contact.lead') }}
      </p>
    </section>

    <section class="mx-auto max-w-5xl px-6 sm:px-8 pb-12 sm:pb-16">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col items-start gap-4">
          <div class="flex flex-row gap-3">
            <div class="shrink-0 rounded-lg bg-blue-50 p-3 text-blue-600">
              <IconMail class="size-6" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ t('contact.sections.email.title') }}</h3>
            </div>
          </div>
          
          <p class="mt-1 text-gray-600 text-sm">{{ t('contact.sections.email.desc') }}</p>
          <a
            href="mailto:contact@example.com"
            class="mt-2 font-mono text-sm text-gray-900 hover:text-blue-600 underline underline-offset-4"
          >
            contact@example.com
          </a>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col items-start gap-4">
          <div class="flex flex-row gap-3">
            <div class="shrink-0 rounded-lg bg-emerald-50 p-3 text-emerald-600">
              <IconPhone class="size-6" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ t('contact.sections.phone.title') }}</h3>
            </div>
          </div>
          
          <p class="mt-1 text-gray-600 text-sm">{{ t('contact.sections.phone.desc') }}</p>
          <a
            :href="`tel:${t('contact.sections.phone.value')}`"
            class="mt-2 font-mono text-sm text-gray-900 hover:text-emerald-600 underline underline-offset-4"
          >
            {{ t('contact.sections.phone.value') }}
          </a>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col items-start gap-4">
          <div class="flex flex-row gap-3">
            <div class="shrink-0 rounded-lg bg-gray-50 p-3 text-gray-700">
              <IconBrandGithub class="size-6" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ t('contact.sections.github.title') }}</h3>
            </div>
          </div>
          
          <p class="mt-1 text-gray-600 text-sm">{{ t('contact.sections.github.desc') }}</p>
          <a
            href="https://github.com/blumilksoftware/interns2025c/"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-gray-900 hover:text-gray-700 underline underline-offset-4"
            aria-label="GitHub"
          >
            {{ t('contact.sections.github.value') }}
          </a>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
