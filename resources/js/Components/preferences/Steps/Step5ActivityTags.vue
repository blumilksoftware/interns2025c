<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { usePreferencesStore } from '@/stores/preferences'
import { usePreferencesSummary } from '@/composables/usePreferencesSummary'

const props = defineProps({
  selectorConfigs: { type: Object, required: true },
  tagOptions: { type: Array, required: true },
  moveFilterById: { type: Function, required: true },
})

const { t } = useI18n()
const prefs = usePreferencesStore()
const form = computed({ get: () => prefs.form, set: (value) => prefs.setForm(value || {}) })

const { summary, clearItem } = usePreferencesSummary(form, props.selectorConfigs, props.tagOptions)

function toggleTag(tag) {
  const idx = form.value.tags.indexOf(tag)
  if (idx === -1) form.value.tags.push(tag)
  else form.value.tags.splice(idx, 1)
  props.moveFilterById('tags')
}

function handleClearItem(sectionKey, value) {
  clearItem(sectionKey, value, props.moveFilterById)
}

const flatBadges = computed(() => {
  const items = []
  for (const sec of summary.value) {
    for (const it of sec.items) {
      items.push({ key: sec.key, title: sec.title, value: it.value, label: it.label })
    }
  }
  return items
})

const showAllBadges = ref(false)
const baseLimit = computed(() => {
  try { return (typeof window !== 'undefined' && window.innerWidth < 640) ? 6 : 12 } catch { return 12 }
})
const limitedBadges = computed(() => {
  const arr = flatBadges.value
  if (showAllBadges.value) return arr
  const limit = baseLimit.value
  if (arr.length <= limit) return arr
  return [...arr.slice(0, limit), { ellipsis: true }]
})
</script>

<template>
  <div class="space-y-6">
    <div class="filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="activity">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.activityLevel') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-150 ease-in-out hover:-translate-y-0.5" @click="form.activityLevel = null; props.moveFilterById('activity')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="props.selectorConfigs.activity.columns" :options="props.selectorConfigs.activity.options" :model-value="form.activityLevel" @update:model-value="val => { form.activityLevel = val; props.moveFilterById('activity') }">
        <template #label="{ option }">
          <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
        </template>
      </ChoiceTiles>
    </div>

    <div class="filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="tags">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.tags') }}</label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in tagOptions"
          :key="tag.value"
          type="button"
          class="px-3 py-1 rounded-full border text-sm transition-all duration-150 ease-in-out hover:-translate-y-0.5"
          :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'"
          @click="toggleTag(tag.value, $event)"
        >
          {{ tag.label }}
        </button>
      </div>
    </div>

    <div class="filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" data-filter-id="summary">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.summary') }}</label>
      <div v-if="summary.length === 0" class="text-sm text-gray-500 dark:text-gray-400">{{ t('preferences.placeholders.noActiveFilters') }}</div>
      <div v-else class="flex flex-wrap gap-2">
        <template v-for="badge in limitedBadges" :key="badge.ellipsis ? 'ellipsis' : (badge.title + '-' + String(badge.value))">
          <button v-if="badge.ellipsis" type="button" class="badge-selected" @click="showAllBadges = true">...</button>
          <span v-else class="badge-selected">
            {{ badge.title }}: {{ badge.label }}
            <button type="button" class="ml-1 inline-flex items-center justify-center size-4 rounded-full hover:bg-white/20" @click="handleClearItem(badge.key, badge.value)">
              <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
              </svg>
            </button>
          </span>
        </template>
        <button v-if="showAllBadges && flatBadges.length > baseLimit" type="button" class="badge-selected" @click="showAllBadges = false">{{ t('preferences.actions.collapse') }}</button>
      </div>
    </div>
  </div>
</template>
