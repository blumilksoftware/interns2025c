<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { usePreferencesStore } from '@/stores/preferences'

const props = defineProps({
  selectorConfigs: { type: Object, required: true },
  moveFilterById: { type: Function, required: true },
})

const { t } = useI18n()
const prefs = usePreferencesStore()
const form = computed({ get: () => prefs.form, set: (value) => prefs.setForm(value || {}) })
</script>

<template>
  <div class="bg-white/70 backdrop-blur-md border border-gray-200/60 rounded-xl p-4 filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="attitudes">
    <div class="flex items-center justify-between mb-4">
      <label class="block text-base font-medium text-gray-800">{{ t('preferences.attitudes') || 'Stosunki' }}</label>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 md:auto-rows-fr gap-6">
      <div class="h-full border border-gray-200/70 rounded-lg p-3 bg-white/60 filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="attitude-dogs">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600">{{ t('preferences.labels.attitudeToDogs') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out hover:-translate-y-0.5" @click="form.attitudeToDogs = null; props.moveFilterById('attitude-dogs')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="props.selectorConfigs.attitudes.columns" :options="props.selectorConfigs.attitudes.options" :model-value="form.attitudeToDogs" @update:model-value="val => { form.attitudeToDogs = val; props.moveFilterById('attitude-dogs') }">
          <template #label="{ option }">
            <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
          </template>
        </ChoiceTiles>
      </div>

      <div class="h-full border border-gray-200/70 rounded-lg p-3 bg-white/60 filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="attitude-cats">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600">{{ t('preferences.labels.attitudeToCats') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out hover:-translate-y-0.5" @click="form.attitudeToCats = null; props.moveFilterById('attitude-cats')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="props.selectorConfigs.attitudes.columns" :options="props.selectorConfigs.attitudes.options" :model-value="form.attitudeToCats" @update:model-value="val => { form.attitudeToCats = val; props.moveFilterById('attitude-cats') }">
          <template #label="{ option }">
            <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
          </template>
        </ChoiceTiles>
      </div>

      <div class="h-full border border-gray-200/70 rounded-lg p-3 bg-white/60 filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="attitude-children">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600">{{ t('preferences.labels.attitudeToChildren') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out hover:-translate-y-0.5" @click="form.attitudeToChildren = null; props.moveFilterById('attitude-children')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="props.selectorConfigs.attitudes.columns" :options="props.selectorConfigs.attitudes.options" :model-value="form.attitudeToChildren" @update:model-value="val => { form.attitudeToChildren = val; props.moveFilterById('attitude-children') }">
          <template #label="{ option }">
            <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
          </template>
        </ChoiceTiles>
      </div>

      <div class="h-full border border-gray-200/70 rounded-lg p-3 bg-white/60 filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="attitude-adults">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-gray-600">{{ t('preferences.labels.attitudeToAdults') }}</span>
          <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out hover:-translate-y-0.5" @click="form.attitudeToAdults = null; props.moveFilterById('attitude-adults')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="props.selectorConfigs.attitudes.columns" :options="props.selectorConfigs.attitudes.options" :model-value="form.attitudeToAdults" @update:model-value="val => { form.attitudeToAdults = val; props.moveFilterById('attitude-adults') }">
          <template #label="{ option }">
            <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
          </template>
        </ChoiceTiles>
      </div>
    </div>
  </div>
</template>
