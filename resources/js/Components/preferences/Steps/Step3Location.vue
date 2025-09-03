<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FilterLocationPopover from '@/Components/filters/FilterLocationPopover.vue'
import MapPreview from '@/Components/preferences/MapPreview.vue'
import { usePreferencesStore } from '@/stores/preferences'

const props = defineProps({
  popularLocations: { type: Array, required: true },
  recentLocations: { type: Object, required: true },
  filteredLocations: { type: Object, required: true },
  locationOpen: { type: Boolean, required: true },
  radiusOptions: { type: Array, required: true },
  selectLocation: { type: Function, required: true },
  clearLocation: { type: Function, required: true },
  handleLocationChange: { type: Function, required: true },
  moveFilterById: { type: Function, required: true },
})

const emit = defineEmits(['update:locationOpen'])

const { t } = useI18n()

const locationOpenModel = computed({ get: () => props.locationOpen, set: (v) => emit('update:locationOpen', v) })

const prefs = usePreferencesStore()
const form = computed({ get: () => prefs.form, set: (v) => prefs.setForm(v || {}) })

const locationText = computed(() => {
  const loc = form.value?.location
  if (typeof loc === 'string') return loc
  if (loc && typeof loc === 'object') return loc.city || loc.name || loc.label || ''
  return ''
})

const effectiveRadius = computed(() => {
  const q = locationText.value?.trim()
  if (!q || q.toLowerCase() === 'cała polska') return null
  return form.value?.radiusKm ?? null
})
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <FilterLocationPopover
      v-model="form.location"
      v-model:open="locationOpenModel"
      filter-id="location"
      :label="t('preferences.labels.location')"
      :popular-locations="popularLocations"
      :recent-locations="recentLocations"
      :filtered-locations="filteredLocations"
      @changed="handleLocationChange"
      @use="selectLocation"
      @clear="clearLocation"
    />

    <div class="space-y-4">
      <div class="filter-item" data-filter-id="radius">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.radiusKm') }}</label>
        <select v-model="form.radiusKm" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" :disabled="!locationText || locationText.toLowerCase() === 'cała polska'" @change="moveFilterById('radius')">
          <option :value="null">{{ t('preferences.placeholders.any') }}</option>
          <option v-for="opt in radiusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
      </div>

      <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <div class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
          {{ locationText || t('preferences.placeholders.cityOrZip') }}
        </div>
        <MapPreview :query="locationText || 'Polska'" :radius-km="effectiveRadius" />
      </div>
    </div>
  </div>
</template>



