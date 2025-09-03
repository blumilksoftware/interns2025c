<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { usePreferencesStore } from '@/stores/preferences'

const props = defineProps({
  selectorConfigs: { type: Object, required: true },
  tagOptions: { type: Array, required: true },
  moveFilterById: { type: Function, required: true },
})

const { t } = useI18n()
const prefs = usePreferencesStore()
const form = computed({ get: () => prefs.form, set: (v) => prefs.setForm(v || {}) })

function toggleTag(tag) {
  const idx = form.value.tags.indexOf(tag)
  if (idx === -1) form.value.tags.push(tag)
  else form.value.tags.splice(idx, 1)
  props.moveFilterById('tags')
}
</script>

<template>
  <div class="space-y-6">
    <div class="filter-item" data-filter-id="activity">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.activityLevel') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.activityLevel = null; props.moveFilterById('activity')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="props.selectorConfigs.activity.columns" :options="props.selectorConfigs.activity.options" :model-value="form.activityLevel" @update:model-value="val => { form.activityLevel = val; props.moveFilterById('activity') }" />
    </div>

    <div class="filter-item" data-filter-id="tags">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.tags') }}</label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in tagOptions"
          :key="tag.value"
          type="button"
          class="px-3 py-1 rounded-full border text-sm transition-colors"
          :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'"
          @click="toggleTag(tag.value, $event)"
        >
          {{ tag.label }}
        </button>
      </div>
    </div>
  </div>
</template>



