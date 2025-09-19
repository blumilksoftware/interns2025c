<script setup>
import { useI18n } from 'vue-i18n'
import AppLayout from '@/Layouts/AppLayout.vue'
import { IconHeart, IconAdjustments, IconPhone } from '@tabler/icons-vue'
const { t } = useI18n()
</script>

<template>
  <AppLayout :title="t('about.title')">
    <section class="mx-auto max-w-5xl px-6 sm:px-8 py-10 sm:py-16">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
        {{ t('about.title') }}
      </h1>
      <p class="text-base sm:text-lg text-gray-700 max-w-3xl">
        {{ t('about.lead') }}
      </p>
    </section>

    <section class="mx-auto max-w-5xl px-6 sm:px-8 pb-12 sm:pb-16">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-start gap-4">
          <div class="shrink-0 rounded-lg bg-amber-50 p-3 text-amber-600">
            <IconHeart class="size-6" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ t('about.features.indexing.title') }}</h3>
            <p class="mt-1 text-gray-600 text-sm">{{ t('about.features.indexing.desc') }}</p>
          </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-start gap-4">
          <div class="shrink-0 rounded-lg bg-blue-50 p-3 text-blue-600">
            <IconAdjustments class="size-6" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ t('about.features.matching.title') }}</h3>
            <p class="mt-1 text-gray-600 text-sm">{{ t('about.features.matching.desc') }}</p>
          </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-start gap-4">
          <div class="shrink-0 rounded-lg bg-emerald-50 p-3 text-emerald-600">
            <IconPhone class="size-6" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ t('about.features.contact.title') }}</h3>
            <p class="mt-1 text-gray-600 text-sm">{{ t('about.features.contact.desc') }}</p>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
