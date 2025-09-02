<script setup>
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'

const props = defineProps({
  form: { type: Object, required: true },
  selectorConfigs: { type: Object, required: true },
  tagOptions: { type: Array, required: true },
  moveFilterById: { type: Function, required: true },
  toggleTag: { type: Function, required: true }
})

const { t } = useI18n()
</script>

<template>
  <div class="space-y-6">
    <div class="filter-item" data-filter-id="activity">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.activityLevel') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.activityLevel = null; moveFilterById('activity')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="selectorConfigs.activity.columns" :options="selectorConfigs.activity.options" :model-value="form.activityLevel" @update:modelValue="val => { form.activityLevel = val; moveFilterById('activity') }" />
    </div>

    <div class="filter-item" data-filter-id="tags">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.tags') }}</label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in tagOptions"
          :key="tag.value"
          type="button"
          @click="toggleTag(tag.value, $event)"
          class="px-3 py-1 rounded-full border text-sm transition-colors"
          :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'"
        >
          {{ tag.label }}
        </button>
      </div>
    </div>
  </div>
</template>



