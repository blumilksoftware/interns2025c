<script setup>
import { ref, computed, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { getPetTags } from '@/helpers/mappers'
import { dogs, cats } from '@/data/petsData.js'
import PawPrints from '@/Components/PawPrints.vue'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { selectorConfigs } from '@/helpers/selectors'
import { speciesOptions as speciesCfg, sexOptions as sexCfg, ageTextOptions as ageTextCfg, colorOptions as colorCfg, healthOptions as healthCfg, adoptionOptions as adoptionCfg } from '@/helpers/preferencesConfig'

const { t } = useI18n()

const speciesOptions = speciesCfg

const sexOptions = sexCfg

const allTags = getPetTags()
const tagOptions = computed(() =>
  Object.entries(allTags).map(([key, meta]) => ({ value: key, label: `${meta.emoji} ${meta.name}` }))
)

const form = ref({
  species: '',
  breed: '',
  sex: '',
  ageIndex: null,
  sizeIndex: null,
  weightState: null,
  color: '',
  healthStatus: '',
  vaccinated: false,
  sterilized: false,
  microchipped: false,
  dewormed: false,
  defleaTreated: false,
  attitudeToDogs: null,
  attitudeToCats: null,
  attitudeToChildren: null,
  attitudeToAdults: null,
  activityLevel: null,
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
    ageIndex: null,
    sizeIndex: null,
    weightState: null,
    color: '',
    healthStatus: '',
    vaccinated: false,
    sterilized: false,
    microchipped: false,
    dewormed: false,
    defleaTreated: false,
    attitudeToDogs: null,
    attitudeToCats: null,
    attitudeToChildren: null,
    attitudeToAdults: null,
    activityLevel: null,
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

const colorOptions = colorCfg

const healthOptions = healthCfg

const adoptionOptions = adoptionCfg

</script>

<template>
    <Head :title="t('titles.preferences')" />
  <div class="min-h-screen bg-gradient-to-br from-orange-200/40 via-pink-100/40 to-blue-200/40 dark:from-purple-900 dark:via-blue-900 dark:to-indigo-900 relative">
    <PawPrints mode="scatter" />
    <div class="mx-auto max-w-5xl px-6 lg:px-8 py-10">
    <div class="mb-6">
      <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ t('preferences.title') }}</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-300">{{ t('preferences.subtitle') }}</p>
    </div>

    <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-xl p-6 space-y-6 shadow-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.species') }}</label>
          <select v-model="form.species" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in speciesOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
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
            <option v-for="opt in sexOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>

        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.age') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.ageIndex = null">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.age.columns" :options="selectorConfigs.age.options" :model-value="form.ageIndex" @update:modelValue="val => form.ageIndex = val">
            <template #label="{ option }">
              <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
            </template>
          </ChoiceTiles>
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
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.size') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.sizeIndex = null">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.size.columns" :options="selectorConfigs.size.options" :model-value="form.sizeIndex" @update:modelValue="val => form.sizeIndex = val">
            <template #label="{ option }">
              <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
            </template>
          </ChoiceTiles>
        </div>

        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.weightKg') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.weightState = null">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.weight.columns" :options="selectorConfigs.weight.options" :model-value="form.weightState" @update:modelValue="val => form.weightState = val" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.color') }}</label>
          <select v-model="form.color" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in colorOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.healthStatus') }}</label>
          <select v-model="form.healthStatus" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in healthOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.adoptionStatus') }}</label>
          <select v-model="form.adoptionStatus" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in adoptionOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>


      </div>

      <details class="disclosure rounded-lg border border-gray-200 dark:border-gray-700 p-0">
        <summary class="disclosure-summary cursor-pointer">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.75H6.5a.75.75 0 000 1.5h2.75v2.75a.75.75 0 001.5 0V11h2.75a.75.75 0 000-1.5H10.75V6.75z" clip-rule="evenodd" />
            </svg>
            <span class="disclosure-title">{{ t('preferences.labels.healthChecks') }}</span>
          </div>
          <div class="flex items-center gap-3">
            <span class="disclosure-hint hidden sm:inline text-xs text-gray-500 dark:text-gray-400">kliknij, aby rozwinąć</span>
            <span v-if="(form.vaccinated?1:0)+(form.sterilized?1:0)+(form.microchipped?1:0)+(form.dewormed?1:0)+(form.defleaTreated?1:0) > 0" class="badge-selected">{{ (form.vaccinated?1:0)+(form.sterilized?1:0)+(form.microchipped?1:0)+(form.dewormed?1:0)+(form.defleaTreated?1:0) }}</span>
            <svg class="chevron w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
          </div>
        </summary>
        <div class="px-4 pb-4 pt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
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

      <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/60 dark:border-gray-700/60 rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
          <label class="block text-base font-medium text-gray-800 dark:text-gray-100">Stosunki</label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:auto-rows-fr gap-6">
          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToDogs') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToDogs = null">{{ t('preferences.placeholders.any') }}</button>
            </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToDogs" @update:modelValue="val => form.attitudeToDogs = val" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToCats') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToCats = null">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToCats" @update:modelValue="val => form.attitudeToCats = val" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToChildren') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToChildren = null">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToChildren" @update:modelValue="val => form.attitudeToChildren = val" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToAdults') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToAdults = null">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToAdults" @update:modelValue="val => form.attitudeToAdults = val" />
          </div>
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-3">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.activityLevel') }}</label>
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.activityLevel = null">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.activity.columns" :options="selectorConfigs.activity.options" :model-value="form.activityLevel" @update:modelValue="val => form.activityLevel = val" />
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
  </div>
</template>
