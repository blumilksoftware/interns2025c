<script setup>
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'

const props = defineProps({
  form: { type: Object, required: true },
  selectorConfigs: { type: Object, required: true },
  moveFilterById: { type: Function, required: true }
})

const { t } = useI18n()
</script>

<template>
  <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/60 dark:border-gray-700/60 rounded-xl p-4 filter-item" data-filter-id="attitudes">
    <div class="flex items-center justify-between mb-4">
      <label class="block text-base font-medium text-gray-800 dark:text-gray-100">Stosunki</label>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 md:auto-rows-fr gap-6">
      <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-dogs">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToDogs') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToDogs = null; moveFilterById('attitude-dogs')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToDogs" @update:modelValue="val => { form.attitudeToDogs = val; moveFilterById('attitude-dogs') }" />
      </div>

      <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-cats">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToCats') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToCats = null; moveFilterById('attitude-cats')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToCats" @update:modelValue="val => { form.attitudeToCats = val; moveFilterById('attitude-cats') }" />
      </div>

      <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-children">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToChildren') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToChildren = null; moveFilterById('attitude-children')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToChildren" @update:modelValue="val => { form.attitudeToChildren = val; moveFilterById('attitude-children') }" />
      </div>

      <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-adults">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToAdults') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToAdults = null; moveFilterById('attitude-adults')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToAdults" @update:modelValue="val => { form.attitudeToAdults = val; moveFilterById('attitude-adults') }" />
      </div>
    </div>
  </div>
</template>



