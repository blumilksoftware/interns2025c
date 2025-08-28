<script setup>
import { ref, computed, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { getPetTags } from '@/helpers/mappers'
import { dogs, cats } from '@/data/petsData.js'
import CommonIcons from '@/Components/Icons/CommonIcons.vue'

const { t } = useI18n()

const speciesOptions = [
  { value: 'dog', label: t('preferences.species.dog') },
  { value: 'cat', label: t('preferences.species.cat') },
  { value: 'other', label: t('preferences.species.other') },
]

const sexOptions = [
  { value: 'male', label: t('preferences.sex.male') },
  { value: 'female', label: t('preferences.sex.female') },
]

const ageOptions = [
  { value: 'young', label: t('preferences.age.young') },
  { value: 'adult', label: t('preferences.age.adult') },
  { value: 'senior', label: t('preferences.age.senior') },
]

const allTags = getPetTags()
const tagOptions = computed(() =>
  Object.entries(allTags).map(([key, meta]) => ({ value: key, label: `${meta.emoji} ${meta.name}` }))
)

const form = ref({
  species: '',
  breed: '',
  sex: '',
  ageIndex: 1,
  sizeIndex: 1,
  weightState: 1,
  color: '',
  healthStatus: '',
  vaccinated: false,
  sterilized: false,
  microchipped: false,
  dewormed: false,
  defleaTreated: false,
  attitudeToDogs: 2,
  attitudeToCats: 2,
  attitudeToChildren: 2,
  attitudeToAdults: 2,
  activityLevel: 2,
  adoptionStatus: '',
  tags: [],
  location: '',
  radiusKm: 25,
})

function toggleTag(tag) {
  const idx = form.value.tags.indexOf(tag)
  if (idx === -1) form.value.tags.push(tag)
  else form.value.tags.splice(idx, 1)
}

function resetForm() {
  form.value = {
    species: '',
    breed: '',
    sex: '',
    ageIndex: 1,
    sizeIndex: 1,
    weightState: 1,
    color: '',
    healthStatus: '',
    vaccinated: false,
    sterilized: false,
    microchipped: false,
    dewormed: false,
    defleaTreated: false,
    attitudeToDogs: 2,
    attitudeToCats: 2,
    attitudeToChildren: 2,
    attitudeToAdults: 2,
    activityLevel: 2,
    adoptionStatus: '',
    tags: [],
    location: '',
    radiusKm: 25,
  }
}

function applyPreferences() {
  console.log('Applied preferences:', { ...form.value })
}

const dogBreeds = Array.from(new Set(dogs.map(d => d.breed))).sort()
const catBreeds = Array.from(new Set(cats.map(c => c.breed))).sort()
const breedOptions = computed(() => {
  if (form.value.species === 'dog') return dogBreeds
  if (form.value.species === 'cat') return catBreeds
  return []
})

watch(() => form.value.species, () => { form.value.breed = '' })

const sizeLabels = [t('preferences.size.small'), t('preferences.size.medium'), t('preferences.size.large')]
const levelLabels = [
  t('preferences.level.veryLow'),
  t('preferences.level.low'),
  t('preferences.level.medium'),
  t('preferences.level.high'),
  t('preferences.level.veryHigh'),
]

const colorOptions = [
  { value: 'black', label: t('preferences.colors.black') },
  { value: 'white', label: t('preferences.colors.white') },
  { value: 'brown', label: t('preferences.colors.brown') },
  { value: 'grey', label: t('preferences.colors.grey') },
  { value: 'ginger', label: t('preferences.colors.ginger') },
  { value: 'mixed', label: t('preferences.colors.mixed') },
]

const healthOptions = [
  { value: 'healthy', label: t('preferences.health.healthy') },
  { value: 'sick', label: t('preferences.health.sick') },
  { value: 'recovering', label: t('preferences.health.recovering') },
  { value: 'critical', label: t('preferences.health.critical') },
  { value: 'unknown', label: t('preferences.health.unknown') },
]

const adoptionOptions = [
  { value: 'adopted', label: t('preferences.adoption.adopted') },
  { value: 'waiting', label: t('preferences.adoption.waiting') },
  { value: 'quarantined', label: t('preferences.adoption.quarantined') },
  { value: 'temporary_home', label: t('preferences.adoption.temporaryHome') },
]

</script>

<template>
    <Head :title="t('titles.preferences')" />
  <div class="mx-auto max-w-5xl px-6 lg:px-8 py-10">
    <div class="mb-6">
      <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ t('preferences.title') }}</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-300">{{ t('preferences.subtitle') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.species') }}</label>
          <select v-model="form.species" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in speciesOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.breed') }}</label>
          <select v-model="form.breed" :disabled="!form.species || breedOptions.length === 0" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 disabled:opacity-50 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="b in breedOptions" :key="b" :value="b">{{ b }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.sex') }}</label>
          <select v-model="form.sex" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in sexOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.age') }}</label>
          <input
            v-model.number="form.ageIndex"
            type="range"
            min="0"
            max="2"
            step="1"
            class="w-full range-custom"
            :style="{ '--range-progress': (form.ageIndex / 2 * 100) + '%' }"
          />
          <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.ageIndex === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-kid" />
              {{ ageOptions[0].label }}
            </span>
            <span class="inline-flex items-center gap-1" :class="form.ageIndex === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="man" />
              {{ ageOptions[1].label }}
            </span>
            <span class="inline-flex items-center gap-1" :class="form.ageIndex === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="old" />
              {{ ageOptions[2].label }}
            </span>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.location') }}</label>
          <input v-model="form.location" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" :placeholder="t('preferences.placeholders.cityOrZip')">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.radiusKm') }}</label>
          <input v-model.number="form.radiusKm" type="number" min="1" max="500" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
        </input>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.size') }}</label>
          <input v-model.number="form.sizeIndex" type="range" min="0" max="2" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.sizeIndex / 2 * 100) + '%' }">
          <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.sizeIndex === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="horse" class="w-4 h-4"/>
              {{ sizeLabels[0] }}
            </span>
            <span class="inline-flex items-center gap-1" :class="form.sizeIndex === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="horse" class="w-6 h-6"/>
              {{ sizeLabels[1] }}
            </span>
            <span class="inline-flex items-center gap-1" :class="form.sizeIndex === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="horse" class="w-8 h-8"/>
              {{ sizeLabels[2] }}
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.weightKg') }}</label>
          <input v-model.number="form.weightState" type="range" min="0" max="2" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.weightState / 2 * 100) + '%' }">
          <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-2" :class="form.weightState === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="weight" class="w-5 h-5" :style="{ strokeWidth: form.weightState === 0 ? 2 : 1 }"/>
              chudy
            </span>
            <span class="inline-flex items-center gap-2" :class="form.weightState === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="weight" class="w-6 h-6" :style="{ strokeWidth: form.weightState === 1 ? 2.5 : 2 }"/>
              średni
            </span>
            <span class="inline-flex items-center gap-2" :class="form.weightState === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="weight" class="w-7 h-7" :style="{ strokeWidth: form.weightState === 2 ? 3 : 3 }"/>
              gruby
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.color') }}</label>
          <select v-model="form.color" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in colorOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.healthStatus') }}</label>
          <select v-model="form.healthStatus" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in healthOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.adoptionStatus') }}</label>
          <select v-model="form.adoptionStatus" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in adoptionOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>


      </div>

      <details class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <summary class="cursor-pointer font-medium text-gray-800 dark:text-gray-100">{{ t('preferences.labels.healthChecks') }}</summary>
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.vaccinated" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.vaccinated') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.sterilized" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.sterilized') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.microchipped" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.microchipped') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.dewormed" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.dewormed') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.defleaTreated" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.defleaTreated') }}
          </label>
        </div>
      </details>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.attitudeToDogs') }}</label>
          <input v-model.number="form.attitudeToDogs" type="range" min="0" max="4" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.attitudeToDogs / 4 * 100) + '%' }">
          <div class="grid grid-cols-5 place-items-center text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.attitudeToDogs === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-sad" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToDogs === 0 ? 2.25 : 1.5 }"/>
              wrogi
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToDogs === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="warning" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToDogs === 1 ? 2.25 : 1.5 }"/>
              ostrożny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToDogs === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-empty" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToDogs === 2 ? 2.25 : 1.5 }"/>
              obojętny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToDogs === 3 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-smile" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToDogs === 3 ? 2.25 : 1.5 }"/>
              przyjazny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToDogs === 4 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="heart" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToDogs === 4 ? 2.25 : 1.5 }"/>
              bardzo przyjazny
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.attitudeToCats') }}</label>
          <input v-model.number="form.attitudeToCats" type="range" min="0" max="4" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.attitudeToCats / 4 * 100) + '%' }">
          <div class="grid grid-cols-5 place-items-center text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.attitudeToCats === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-sad" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToCats === 0 ? 2.25 : 1.5 }"/>
              wrogi
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToCats === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="warning" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToCats === 1 ? 2.25 : 1.5 }"/>
              ostrożny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToCats === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-empty" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToCats === 2 ? 2.25 : 1.5 }"/>
              obojętny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToCats === 3 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-smile" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToCats === 3 ? 2.25 : 1.5 }"/>
              przyjazny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToCats === 4 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="heart" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToCats === 4 ? 2.25 : 1.5 }"/>
              bardzo przyjazny
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.attitudeToChildren') }}</label>
          <input v-model.number="form.attitudeToChildren" type="range" min="0" max="4" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.attitudeToChildren / 4 * 100) + '%' }">
          <div class="grid grid-cols-5 place-items-center text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.attitudeToChildren === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-sad" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToChildren === 0 ? 2.25 : 1.5 }"/>
              wrogi
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToChildren === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="warning" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToChildren === 1 ? 2.25 : 1.5 }"/>
              ostrożny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToChildren === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-empty" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToChildren === 2 ? 2.25 : 1.5 }"/>
              obojętny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToChildren === 3 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-smile" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToChildren === 3 ? 2.25 : 1.5 }"/>
              przyjazny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToChildren === 4 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="heart" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToChildren === 4 ? 2.25 : 1.5 }"/>
              bardzo przyjazny
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.attitudeToAdults') }}</label>
          <input v-model.number="form.attitudeToAdults" type="range" min="0" max="4" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.attitudeToAdults / 4 * 100) + '%' }">
          <div class="grid grid-cols-5 place-items-center text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span class="inline-flex items-center gap-1" :class="form.attitudeToAdults === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-sad" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToAdults === 0 ? 2.25 : 1.5 }"/>
              wrogi
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToAdults === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="warning" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToAdults === 1 ? 2.25 : 1.5 }"/>
              ostrożny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToAdults === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-empty" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToAdults === 2 ? 2.25 : 1.5 }"/>
              obojętny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToAdults === 3 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="mood-smile" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToAdults === 3 ? 2.25 : 1.5 }"/>
              przyjazny
            </span>
            <span class="inline-flex items-center gap-1" :class="form.attitudeToAdults === 4 ? 'font-semibold text-gray-900 dark:text-white' : ''">
              <CommonIcons name="heart" class="w-4 h-4" :style="{ strokeWidth: form.attitudeToAdults === 4 ? 2.25 : 1.5 }"/>
              bardzo przyjazny
            </span>
          </div>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.activityLevel') }}</label>
        <input v-model.number="form.activityLevel" type="range" min="0" max="4" step="1" class="w-full range-custom" :style="{ '--range-progress': (form.activityLevel / 4 * 100) + '%' }">
        <div class="grid grid-cols-5 place-items-center text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">
          <span class="inline-flex items-center gap-1" :class="form.activityLevel === 0 ? 'font-semibold text-gray-900 dark:text-white' : ''">
            <CommonIcons name="zzz" class="w-4 h-4" :style="{ strokeWidth: form.activityLevel === 0 ? 2.25 : 1.5 }"/>
            bardzo niski
          </span>
          <span class="inline-flex items-center gap-1" :class="form.activityLevel === 1 ? 'font-semibold text-gray-900 dark:text-white' : ''">
            <CommonIcons name="walk" class="w-4 h-4" :style="{ strokeWidth: form.activityLevel === 1 ? 2.25 : 1.5 }"/>
            niski
          </span>
          <span class="inline-flex items-center gap-1" :class="form.activityLevel === 2 ? 'font-semibold text-gray-900 dark:text-white' : ''">
            <CommonIcons name="run" class="w-4 h-4" :style="{ strokeWidth: form.activityLevel === 2 ? 2.25 : 1.5 }"/>
            średni
          </span>
          <span class="inline-flex items-center gap-1" :class="form.activityLevel === 3 ? 'font-semibold text-gray-900 dark:text-white' : ''">
            <CommonIcons name="bolt" class="w-4 h-4" :style="{ strokeWidth: form.activityLevel === 3 ? 2.25 : 1.5 }"/>
            wysoki
          </span>
          <span class="inline-flex items-center gap-1" :class="form.activityLevel === 4 ? 'font-semibold text-gray-900 dark:text-white' : ''">
            <CommonIcons name="flame" class="w-4 h-4" :style="{ strokeWidth: form.activityLevel === 4 ? 2.25 : 1.5 }"/>
            bardzo wysoki
          </span>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.tags') }}</label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="tag in tagOptions"
            :key="tag.value"
            type="button"
            @click="toggleTag(tag.value)"
            class="px-3 py-1 rounded-full border text-sm transition-colors"
            :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'"
          >
            {{ tag.label }}
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" @click="applyPreferences" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          {{ t('preferences.actions.apply') }}
        </button>
        <button type="button" @click="resetForm" class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
          {{ t('preferences.actions.reset') }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.range-custom {
  --range-progress: 0%;
  appearance: none;
  height: 0.375rem; /* 6px */
  border-radius: 9999px;
  background:
    linear-gradient(90deg, #6366f1 0%, #ec4899 100%) 0 0/var(--range-progress) 100% no-repeat,
    #d1d5db; /* base track */
}
.range-custom:focus { outline: none; }

.range-custom::-webkit-slider-thumb {
  appearance: none;
  width: 1rem;
  height: 1rem;
  border-radius: 9999px;
  background: radial-gradient(circle at center, #ffffff 55%, transparent 55%),
              conic-gradient(from 0deg, #6366f1, #22d3ee, #a78bfa, #6366f1);
  box-shadow: 0 0 0 3px #ffffff inset;
  border: 0;
}
.range-custom:active::-webkit-slider-thumb {
  filter: brightness(1.05);
}

.range-custom::-moz-range-track {
  height: 0.375rem;
  border-radius: 9999px;
  background: transparent;
}
.range-custom::-moz-range-thumb {
  width: 1rem;
  height: 1rem;
  border-radius: 9999px;
  background: radial-gradient(circle at center, #ffffff 55%, transparent 55%),
              conic-gradient(from 0deg, #6366f1, #22d3ee, #a78bfa, #6366f1);
  border: 0;
}

@media (prefers-color-scheme: dark) {
  .range-custom {
    background:
      linear-gradient(90deg, #22d3ee 0%, #a78bfa 100%) 0 0/var(--range-progress) 100% no-repeat,
      #374151; /* gray-700 base */
  }
  .range-custom::-moz-range-track { background: transparent; }
}
</style>