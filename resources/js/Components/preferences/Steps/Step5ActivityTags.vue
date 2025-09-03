<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { usePreferencesStore } from '@/stores/preferences'
import { speciesOptions, sexOptions, colorOptions, healthOptions, adoptionOptions } from '@/helpers/preferencesConfig'

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

function mapByOptionsWithValues(values, options, tFn) {
  if (values === null || values === undefined || values === '') return []
  const arr = Array.isArray(values) ? values : [values]
  const labelMap = new Map(options.map(o => [o.value, o.labelKey ? tFn(o.labelKey) : (o.label || String(o.value))]))
  return arr
    .filter(v => v !== null && v !== undefined && String(v) !== '')
    .map(v => ({ value: v, label: labelMap.get(v) ?? String(v) }))
}

const summary = computed(() => {
  const f = form.value || {}
  const tFn = (k) => t(k)
  const sections = []

  const species = mapByOptionsWithValues(f.species, speciesOptions, tFn)
  if (species.length) sections.push({ key: 'species', title: t('preferences.labels.species'), items: species })

  const breed = Array.isArray(f.breed) ? f.breed.map(b => ({ value: b, label: b })) : []
  if (breed.length) sections.push({ key: 'breed', title: t('preferences.labels.breed'), items: breed })

  const sex = mapByOptionsWithValues(f.sex, sexOptions, tFn)
  if (sex.length) sections.push({ key: 'sex', title: t('preferences.labels.sex'), items: sex })

  const colors = mapByOptionsWithValues(f.color, colorOptions, tFn)
  if (colors.length) sections.push({ key: 'color', title: t('preferences.labels.color'), items: colors })

  const age = mapByOptionsWithValues(f.ageIndex, props.selectorConfigs.age.options, tFn)
  if (age.length) sections.push({ key: 'ageIndex', title: t('preferences.labels.age'), items: age })

  const size = mapByOptionsWithValues(f.sizeIndex, props.selectorConfigs.size.options, tFn)
  if (size.length) sections.push({ key: 'sizeIndex', title: t('preferences.labels.size'), items: size })

  const weight = mapByOptionsWithValues(f.weightState, props.selectorConfigs.weight.options, tFn)
  if (weight.length) sections.push({ key: 'weightState', title: t('preferences.labels.weightKg'), items: weight })

  if (f.location) {
    const radius = f.radiusKm ? `±${f.radiusKm} km` : ''
    sections.push({ key: 'location', title: t('preferences.labels.location'), items: [{ value: 'location', label: radius ? `${f.location} (${radius})` : f.location }] })
  }

  const health = mapByOptionsWithValues(f.healthStatus, healthOptions, tFn)
  if (health.length) sections.push({ key: 'healthStatus', title: t('preferences.labels.healthStatus'), items: health })

  const adoption = mapByOptionsWithValues(f.adoptionStatus, adoptionOptions, tFn)
  if (adoption.length) sections.push({ key: 'adoptionStatus', title: t('preferences.labels.adoptionStatus'), items: adoption })

  const checks = []
  if (f.vaccinated) checks.push({ value: 'vaccinated', label: t('preferences.checks.vaccinated') })
  if (f.sterilized) checks.push({ value: 'sterilized', label: t('preferences.checks.sterilized') })
  if (f.microchipped) checks.push({ value: 'microchipped', label: t('preferences.checks.microchipped') })
  if (f.dewormed) checks.push({ value: 'dewormed', label: t('preferences.checks.dewormed') })
  if (f.defleaTreated) checks.push({ value: 'defleaTreated', label: t('preferences.checks.defleaTreated') })
  if (checks.length) sections.push({ key: 'healthChecks', title: t('preferences.labels.healthChecks'), items: checks })

  const attDog = mapByOptionsWithValues(f.attitudeToDogs, props.selectorConfigs.attitudes.options, tFn)
  if (attDog.length) sections.push({ key: 'attitudeToDogs', title: t('preferences.labels.attitudeToDogs'), items: attDog })
  const attCat = mapByOptionsWithValues(f.attitudeToCats, props.selectorConfigs.attitudes.options, tFn)
  if (attCat.length) sections.push({ key: 'attitudeToCats', title: t('preferences.labels.attitudeToCats'), items: attCat })
  const attChild = mapByOptionsWithValues(f.attitudeToChildren, props.selectorConfigs.attitudes.options, tFn)
  if (attChild.length) sections.push({ key: 'attitudeToChildren', title: t('preferences.labels.attitudeToChildren'), items: attChild })
  const attAdult = mapByOptionsWithValues(f.attitudeToAdults, props.selectorConfigs.attitudes.options, tFn)
  if (attAdult.length) sections.push({ key: 'attitudeToAdults', title: t('preferences.labels.attitudeToAdults'), items: attAdult })

  const activity = mapByOptionsWithValues(f.activityLevel, props.selectorConfigs.activity.options, tFn)
  if (activity.length) sections.push({ key: 'activityLevel', title: t('preferences.labels.activityLevel'), items: activity })

  if (Array.isArray(f.tags) && f.tags.length) {
    const tagMap = new Map((Array.isArray(props.tagOptions) ? props.tagOptions : []).map(o => [o.value, o.label]))
    const tagItems = f.tags.map(v => ({ value: v, label: tagMap.get(v) || String(v) }))
    sections.push({ key: 'tags', title: t('preferences.labels.tags'), items: tagItems })
  }

  return sections
})

function clearItem(sectionKey, value) {
  const f = form.value
  switch (sectionKey) {
  case 'species': f.species = (f.species || []).filter(v => v !== value); props.moveFilterById('species'); break
  case 'breed': f.breed = (f.breed || []).filter(v => v !== value); props.moveFilterById('breed'); break
  case 'sex': if (f.sex === value) f.sex = ''; props.moveFilterById('sex'); break
  case 'color': f.color = (f.color || []).filter(v => v !== value); props.moveFilterById('color'); break
  case 'ageIndex': f.ageIndex = (f.ageIndex || []).filter(v => v !== value); props.moveFilterById('age'); break
  case 'sizeIndex': f.sizeIndex = (f.sizeIndex || []).filter(v => v !== value); props.moveFilterById('size'); break
  case 'weightState': f.weightState = (f.weightState || []).filter(v => v !== value); props.moveFilterById('weight'); break
  case 'location': f.location = ''; props.moveFilterById('location'); break
  case 'healthStatus': f.healthStatus = (f.healthStatus || []).filter(v => v !== value); props.moveFilterById('health'); break
  case 'adoptionStatus': f.adoptionStatus = (f.adoptionStatus || []).filter(v => v !== value); props.moveFilterById('adoption'); break
  case 'healthChecks': if (value) f[value] = false; props.moveFilterById('health-checks'); break
  case 'attitudeToDogs': f.attitudeToDogs = null; props.moveFilterById('attitude-dogs'); break
  case 'attitudeToCats': f.attitudeToCats = null; props.moveFilterById('attitude-cats'); break
  case 'attitudeToChildren': f.attitudeToChildren = null; props.moveFilterById('attitude-children'); break
  case 'attitudeToAdults': f.attitudeToAdults = null; props.moveFilterById('attitude-adults'); break
  case 'activityLevel': f.activityLevel = null; props.moveFilterById('activity'); break
  case 'tags': f.tags = (f.tags || []).filter(v => v !== value); props.moveFilterById('tags'); break
  default: break
  }
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

    <div class="filter-item" data-filter-id="summary">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Podsumowanie filtrów</label>
      <div v-if="summary.length === 0" class="text-sm text-gray-500 dark:text-gray-400">Brak aktywnych filtrów.</div>
      <div v-else class="flex flex-wrap gap-2">
        <template v-for="b in limitedBadges" :key="b.ellipsis ? 'ellipsis' : (b.title + '-' + String(b.value))">
          <button v-if="b.ellipsis" type="button" class="badge-selected" @click="showAllBadges = true">...</button>
          <span v-else class="badge-selected">
            {{ b.title }}: {{ b.label }}
            <button type="button" class="ml-1 inline-flex items-center justify-center size-4 rounded-full hover:bg-white/20" @click="clearItem(b.key, b.value)">
              <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
              </svg>
            </button>
          </span>
        </template>
        <button v-if="showAllBadges && flatBadges.length > baseLimit" type="button" class="badge-selected" @click="showAllBadges = false">zwiń</button>
      </div>
    </div>
  </div>
</template>



