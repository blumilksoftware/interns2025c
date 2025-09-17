<script setup>
import { ref, computed, watch, nextTick, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePage } from '@inertiajs/vue3'
import { Step1Basic, Step2Health, Step3Location, Step4Attitudes, Step5ActivityTags } from './Steps'
import TopFilters from './TopFilters.vue'
import PawPrints from '@/Components/PawPrints.vue'
import { usePreferencesStore } from '@/stores/preferences'
import { useLocation } from '@/composables/useLocation'
import { useBreeds } from '@/composables/useBreeds'
import { useTopFilters } from '@/composables/useTopFilters'
import { selectorConfigs } from '@/helpers/selectors'
import { speciesOptions as speciesCfg, sexOptions as sexCfg, healthOptions as healthCfg, adoptionOptions as adoptionCfg } from '@/helpers/preferencesConfig'

const { t } = useI18n()

const currentStep = ref(1)

const stepTitles = computed(() => [
  t('preferences.steps.basic'),
  t('preferences.steps.health'),
  t('preferences.steps.location'),
  t('preferences.steps.attitudes'),
  t('preferences.steps.activity'),
])

const prefs = usePreferencesStore()
const form = computed({
  get: () => prefs.form,
  set: (newValue) => prefs.setForm(newValue || {}),
})

const { locationOpen, recentLocations, filteredLocations, selectLocation, clearLocation, loadRecentLocations, radiusOptions } = useLocation(form)
const { dogBreedsAvailable, catBreedsAvailable } = useBreeds(form)
const { moveFilterById } = useTopFilters()

const speciesOptions = speciesCfg
const sexOptions = sexCfg
const page = usePage()
const colorOptions = computed(() => {
  const colors = page?.props?.colors || []
  return Array.isArray(colors) ? colors.map((c) => ({ value: c, label: c })) : []
})
const healthOptions = healthCfg
const adoptionOptions = adoptionCfg

const speciesOpen = ref(false)
const colorOpen = ref(false)
const healthOpen = ref(false)
const adoptionOpen = ref(false)
const healthChecksOpen = ref(false)
const breedOpen = ref(false)
const sexOpen = ref(false)

const tagOptions = computed(() => {
  const tags = page?.props?.tags
  return Array.isArray(tags) ? tags : []
})

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
  <div class="min-h-screen bg-gradient-to-br from-orange-200/40 via-pink-100/40 to-blue-200/40 relative">
    <PawPrints />

    <div class="mx-auto max-w-5xl px-6 lg:px-8 py-10">
      <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-900">{{ t('preferences.title') }}</h1>
        <p class="mt-2 text-gray-800">{{ t('preferences.subtitle') }}</p>
      </div>

      <div class="bg-white/70 backdrop-blur-md border border-gray-200/50 rounded-xl p-6 space-y-6 shadow-lg">
        <div class="flex flex-col gap-2">
          <div class="grid grid-cols-5 gap-2">
            <div v-for="stepNumber in 5" :key="`dot-` + stepNumber" class="flex items-center justify-center">
              <button :aria-label="`Przejdź do kroku ${stepNumber}: ${stepTitles[stepNumber - 1]}`" :title="`${stepNumber}. ${stepTitles[stepNumber - 1]}`" type="button" class="size-8 rounded-full flex items-center justify-center text-sm shrink-0 transition-all duration-150 ease-in-out"
                      :class="stepNumber === currentStep ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                      @click="currentStep = stepNumber"
              >
                {{ stepNumber }}
              </button>
            </div>
          </div>
          <div class="grid grid-cols-5 gap-2">
            <span v-for="(title, idx) in stepTitles" :key="idx" class="text-center text-xs sm:text-sm text-gray-600 truncate"
                  :class="(idx + 1) === currentStep ? 'text-indigo-700 font-medium' : ''"
            >
              {{ title }}
            </span>
          </div>
        </div>

        <TopFilters />

        <div v-if="currentStep === 1">
          <Step1Basic
            v-model:species-open="speciesOpen"
            v-model:breed-open="breedOpen"
            v-model:sex-open="sexOpen"
            v-model:color-open="colorOpen"
            aria-
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
            :recent-locations="recentLocations"
            :filtered-locations="filteredLocations"
            :radius-options="radiusOptions"
            :select-location="selectLocation"
            :clear-location="clearLocation"
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
            <button type="button" class="inline-flex items-center rounded-md bg-gray-100 px-6 py-3 text-sm font-medium text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-150 ease-in-out" @click="prefs.reset()">
              {{ t('preferences.actions.reset') }}
            </button>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <button type="button" class="px-6 py-3 rounded-md border border-gray-300 text-gray-700 text-sm font-medium transition-all duration-150 ease-in-out hover:bg-gray-50" :disabled="currentStep===1" @click="currentStep--">{{ t('common.prev') || 'Wstecz' }}</button>
            <button v-if="currentStep < 5" type="button" class="px-6 py-3 rounded-md bg-indigo-600 text-white text-sm font-medium transition-all duration-150 ease-in-out hover:bg-indigo-700" :disabled="currentStep===5" @click="currentStep++">{{ t('common.next') || 'Dalej' }}</button>
            <button v-else type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-150 ease-in-out" @click="prefs.apply()">
              {{ t('preferences.actions.apply') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
