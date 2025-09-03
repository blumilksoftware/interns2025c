<script setup>
import { ref, computed, watch, nextTick, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { Step1Basic, Step2Health, Step3Location, Step4Attitudes, Step5ActivityTags } from './Steps'
import TopFilters from './TopFilters.vue'
import ScrollToTop from './ScrollToTop.vue'
import PawPrints from '@/Components/PawPrints.vue'
import { usePreferencesStore } from '@/stores/preferences'
import { useLocation } from '@/composables/useLocation'
import { useBreeds } from '@/composables/useBreeds'
import { useTopFilters } from '@/composables/useTopFilters'
import { selectorConfigs } from '@/helpers/selectors'
import { getPetTags } from '@/helpers/mappers'
import { speciesOptions as speciesCfg, sexOptions as sexCfg, colorOptions as colorCfg, healthOptions as healthCfg, adoptionOptions as adoptionCfg } from '@/helpers/preferencesConfig'

const { t } = useI18n()

const currentStep = ref(1)

const prefs = usePreferencesStore()
const form = computed({
  get: () => prefs.form,
  set: (v) => prefs.setForm(v || {}),
})

const { locationOpen, popularLocations, recentLocations, filteredLocations, selectLocation, clearLocation, handleLocationChange, loadRecentLocations, radiusOptions } = useLocation(form)
const { dogBreedsAvailable, catBreedsAvailable } = useBreeds(form)
const { moveFilterById } = useTopFilters()

const speciesOptions = speciesCfg
const sexOptions = sexCfg
const colorOptions = colorCfg
const healthOptions = healthCfg
const adoptionOptions = adoptionCfg

const speciesOpen = ref(false)
const colorOpen = ref(false)
const healthOpen = ref(false)
const adoptionOpen = ref(false)
const healthChecksOpen = ref(false)
const breedOpen = ref(false)
const sexOpen = ref(false)

const allTags = getPetTags()
const tagOptions = computed(() =>
  Object.entries(allTags).map(([key, meta]) => ({ value: key, label: `${meta.emoji} ${meta.name}` })),
)

function toggleTag(tag) {
  const idx = form.value.tags.indexOf(tag)
  if (idx === -1) form.value.tags.push(tag)
  else form.value.tags.splice(idx, 1)
  moveFilterById('tags')
}

watch(() => form.value.species, async () => {
  form.value.breed = []
  await nextTick()
  moveFilterById('species')
}, { deep: true })

onMounted(() => {
  loadRecentLocations()
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-200/40 via-pink-100/40 to-blue-200/40 dark:from-purple-900 dark:via-blue-900 dark:to-indigo-900 relative">
    <PawPrints />

    <ScrollToTop />

    <div class="mx-auto max-w-5xl px-6 lg:px-8 py-10">
      <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ t('preferences.title') }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-300">{{ t('preferences.subtitle') }}</p>
      </div>

      <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-xl p-6 space-y-6 shadow-lg preferences-form-container">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="flex items-center gap-1 sm:gap-2 max-w-full min-w-0 justify-between sm:justify-start">
            <template v-for="s in 5" :key="s">
              <button type="button" class="size-8 rounded-full flex items-center justify-center text-sm shrink-0"
                      :class="s === currentStep ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200'"
                      @click="currentStep = s"
              >
                {{ s }}
              </button>
              <span v-if="s < 5" class="hidden sm:block w-6 h-0.5 bg-gray-300 dark:bg-gray-600 shrink-0" />
            </template>
          </div>
        </div>

        <TopFilters />

        <div v-if="currentStep === 1">
          <Step1Basic
            v-model:species-open="speciesOpen"
            v-model:breed-open="breedOpen"
            v-model:sex-open="sexOpen"
            v-model:color-open="colorOpen"
            :form="form"
            :selector-configs="selectorConfigs"
            :species-options="speciesOptions"
            :sex-options="sexOptions"
            :color-options="colorOptions"
            :dog-breeds-available="dogBreedsAvailable"
            :cat-breeds-available="catBreedsAvailable"
            :move-filter-by-id="moveFilterById"
          />
        </div>

        <div v-else-if="currentStep === 2">
          <Step2Health
            v-model:health-open="healthOpen"
            v-model:adoption-open="adoptionOpen"
            v-model:health-checks-open="healthChecksOpen"
            :form="form"
            :health-options="healthOptions"
            :adoption-options="adoptionOptions"
            :move-filter-by-id="moveFilterById"
          />
        </div>

        <div v-else-if="currentStep === 3">
          <Step3Location
            v-model:location-open="locationOpen"
            :form="form"
            :popular-locations="popularLocations"
            :recent-locations="recentLocations"
            :filtered-locations="filteredLocations"
            :radius-options="radiusOptions"
            :select-location="selectLocation"
            :clear-location="clearLocation"
            :handle-location-change="handleLocationChange"
            :move-filter-by-id="moveFilterById"
          />
        </div>

        <div v-else-if="currentStep === 4">
          <Step4Attitudes
            :form="form"
            :selector-configs="selectorConfigs"
            :move-filter-by-id="moveFilterById"
          />
        </div>

        <div v-else-if="currentStep === 5">
          <Step5ActivityTags
            :form="form"
            :selector-configs="selectorConfigs"
            :tag-options="tagOptions"
            :move-filter-by-id="moveFilterById"
            :toggle-tag="toggleTag"
          />
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2">
          <div class="w-full sm:w-auto">
            <button type="button" class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-1 text-xs sm:text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" @click="prefs.reset()">
              {{ t('preferences.actions.reset') }}
            </button>
          </div>
          <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <button type="button" class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-xs sm:text-sm" :disabled="currentStep===1" @click="currentStep--">{{ t('common.prev') || 'Wstecz' }}</button>
            <button v-if="currentStep < 5" type="button" class="px-3 py-1 rounded-md bg-indigo-600 text-white text-xs sm:text-sm" :disabled="currentStep===5" @click="currentStep++">{{ t('common.next') || 'Dalej' }}</button>
            <button v-else type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1 text-xs sm:text-sm text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="prefs.apply()">
              {{ t('preferences.actions.apply') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.filter-item { transition: all 0.2s ease; }
.filter-item:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
button { transition: all 0.15s ease; }
button:hover { transform: translateY(-1px); }
input, select { transition: all 0.15s ease; }
input:focus, select:focus { transform: scale(1.01); box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1); }
input[type="checkbox"] { transition: all 0.15s ease; }
input[type="checkbox"]:checked { transform: scale(1.05); }
button[type="button"] { transition: all 0.15s ease; }
button[type="button"]:hover { transform: translateY(-1px); }
</style>


