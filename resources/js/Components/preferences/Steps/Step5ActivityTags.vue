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

function clearTags() {
  if (Array.isArray(form.value.tags) && form.value.tags.length) {
    form.value.tags = []
    props.moveFilterById('tags')
  }
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

const showAllTags = ref(false)
const baseTagLimit = computed(() => {
  try { return (typeof window !== 'undefined' && window.innerWidth < 640) ? 12 : 30 } catch { return 30 }
})
const limitedTags = computed(() => {
  const tags = Array.isArray(props.tagOptions) ? props.tagOptions : []
  if (showAllTags.value) return tags
  const limit = baseTagLimit.value
  if (tags.length <= limit) return tags
  return tags.slice(0, limit)
})
</script>

<template>
  <div class="space-y-6">
    <div class="filter-item" data-filter-id="activity">
      <div class="flex items-center justify-between mb-3">
        <span class="block text-sm font-medium text-gray-700">{{ t('preferences.labels.activityLevel') }}</span>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all cursor-pointer duration-150 ease-in-out" @click="form.activityLevel = null; props.moveFilterById('activity')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="props.selectorConfigs.activity.columns" :options="props.selectorConfigs.activity.options" :model-value="form.activityLevel" @update:model-value="val => { form.activityLevel = val; props.moveFilterById('activity') }">
        <template #label="{ option }">
          <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
        </template>
      </ChoiceTiles>
    </div>

    <div class="filter-item" data-filter-id="tags">
      <div class="flex items-center justify-between mb-2">
        <span class="block text-sm font-medium text-gray-700">{{ t('preferences.labels.tags') }}</span>
        <div class="flex items-center gap-2">
          <button
            v-if="Array.isArray(tagOptions) && tagOptions.length > baseTagLimit"
            type="button"
            class="text-xs px-2 py-1 cursor-pointer rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out"
            @click="showAllTags = !showAllTags"
          >
            {{ showAllTags ? t('preferences.actions.collapse') : t('preferences.actions.expand') }}
          </button>
          <button
            v-if="Array.isArray(form.tags) && form.tags.length"
            type="button"
            class="text-xs px-2 py-1 cursor-pointer rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out"
            @click="clearTags"
          >
            {{ t('preferences.actions.clear') }}
          </button>
        </div>
      </div>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in limitedTags"
          :key="tag.value"
          type="button"
          class="px-3 py-1 cursor-pointer rounded-full border text-sm transition-all duration-150 ease-in-out"
          :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 border-gray-300'"
          @click="toggleTag(tag.value, $event)"
        >
          {{ tag.label }}<span v-if="typeof tag.count === 'number'" class="ml-1 text-xs" :class="form.tags.includes(tag.value) ? 'text-white' : 'text-gray-500'">({{ tag.count }})</span>
        </button>
      </div>
    </div>

    <div class="filter-item" data-filter-id="summary">
      <span class="block text-sm font-medium text-gray-700 mb-2">{{ t('preferences.summary') }}</span>
      <div v-if="summary.length === 0" class="text-sm text-gray-500">{{ t('preferences.placeholders.noActiveFilters') }}</div>
      <div v-else class="flex flex-wrap gap-2">
        <template v-for="badge in limitedBadges" :key="badge.ellipsis ? 'ellipsis' : (badge.title + '-' + String(badge.value))">
          <button v-if="badge.ellipsis" type="button" class="badge-selected cursor-pointer" @click="showAllBadges = true">...</button>
          <span v-else class="badge-selected">
            {{ badge.title }}: {{ badge.label }}
            <button type="button" :aria-label="`Usuń ${badge.title}: ${badge.label}`" class="ml-1 inline-flex items-center justify-center size-4 rounded-full hover:bg-white/20" @click="handleClearItem(badge.key, badge.value)">
              <svg class="size-3 cursor-pointer" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
              </svg>
            </button>
          </span>
        </template>
        <button v-if="showAllBadges && flatBadges.length > baseLimit" type="button" class="badge-selected cursor-pointer" @click="showAllBadges = false">{{ t('preferences.actions.collapse') }}</button>
      </div>
    </div>
  </div>
</template>
