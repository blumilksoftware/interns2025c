<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FilterPopoverMulti from '@/Components/filters/FilterPopoverMulti.vue'
import { usePreferencesStore } from '@/stores/preferences'

const props = defineProps({
  healthOptions: { type: Array, required: true },
  adoptionOptions: { type: Array, required: true },
  healthOpen: { type: Boolean, required: true },
  adoptionOpen: { type: Boolean, required: true },
  healthChecksOpen: { type: Boolean, required: true },
  moveFilterById: { type: Function, required: true },
})

const emit = defineEmits(['update:healthOpen','update:adoptionOpen','update:healthChecksOpen'])

const { t } = useI18n()

const healthOpenModel = computed({ get: () => props.healthOpen, set: (v) => emit('update:healthOpen', v) })
const adoptionOpenModel = computed({ get: () => props.adoptionOpen, set: (v) => emit('update:adoptionOpen', v) })
const healthChecksOpenModel = computed({ get: () => props.healthChecksOpen, set: (v) => emit('update:healthChecksOpen', v) })

const prefs = usePreferencesStore()
const form = computed({ get: () => prefs.form, set: (v) => prefs.setForm(v || {}) })

const healthChecksCount = computed(() =>
  (form.value.vaccinated ? 1 : 0) +
  (form.value.sterilized ? 1 : 0) +
  (form.value.microchipped ? 1 : 0) +
  (form.value.dewormed ? 1 : 0) +
  (form.value.defleaTreated ? 1 : 0),
)

const healthChecksSummary = computed(() => {
  const selected = []
  if (form.value.vaccinated) selected.push(t('preferences.checks.vaccinated'))
  if (form.value.sterilized) selected.push(t('preferences.checks.sterilized'))
  if (form.value.microchipped) selected.push(t('preferences.checks.microchipped'))
  if (form.value.dewormed) selected.push(t('preferences.checks.dewormed'))
  if (form.value.defleaTreated) selected.push(t('preferences.checks.defleaTreated'))
  if (selected.length === 0) return t('preferences.placeholders.any')
  return selected.join(', ')
})
</script>

<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <FilterPopoverMulti
        v-model="form.healthStatus"
        v-model:open="healthOpenModel"
        filter-id="health"
        :label="t('preferences.labels.healthStatus')"
        :options="healthOptions"
        @changed="() => moveFilterById('health')"
      />

      <FilterPopoverMulti
        v-model="form.adoptionStatus"
        v-model:open="adoptionOpenModel"
        filter-id="adoption"
        :label="t('preferences.labels.adoptionStatus')"
        :options="adoptionOptions"
        @changed="() => moveFilterById('adoption')"
      />
    </div>

    <div class="filter-item" data-filter-id="health-checks" :style="{ zIndex: healthChecksOpenModel ? 1000 : 'auto' }">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.healthChecks') }}</label>
      <div class="relative z-30">
        <button type="button" class="w-full text-left text-black dark:text-gray-100 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="healthChecksOpenModel = !healthChecksOpenModel">
          <span>{{ healthChecksSummary }}</span>
          <div class="flex items-center gap-2">
            <span v-if="healthChecksCount > 0" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">{{ healthChecksCount }}</span>
            <svg class="size-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
          </div>
        </button>
        <div v-if="healthChecksOpenModel" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input v-model="form.vaccinated" type="checkbox" class="rounded border-gray-300 dark:border-gray-700" @change="moveFilterById('health-checks')"> {{ t('preferences.checks.vaccinated') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input v-model="form.sterilized" type="checkbox" class="rounded border-gray-300 dark:border-gray-700" @change="moveFilterById('health-checks')"> {{ t('preferences.checks.sterilized') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input v-model="form.microchipped" type="checkbox" class="rounded border-gray-300 dark:border-gray-700" @change="moveFilterById('health-checks')"> {{ t('preferences.checks.microchipped') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input v-model="form.dewormed" type="checkbox" class="rounded border-gray-300 dark:border-gray-700" @change="moveFilterById('health-checks')"> {{ t('preferences.checks.dewormed') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input v-model="form.defleaTreated" type="checkbox" class="rounded border-gray-300 dark:border-gray-700" @change="moveFilterById('health-checks')"> {{ t('preferences.checks.defleaTreated') }}
            </label>
          </div>
          <div class="mt-3 flex justify-end gap-2">
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.vaccinated=false;form.sterilized=false;form.microchipped=false;form.dewormed=false;form.defleaTreated=false; moveFilterById('health-checks')">{{ t('preferences.placeholders.any') }}</button>
            <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="healthChecksOpenModel = false">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



