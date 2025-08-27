<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { getPetTags } from '@/data/petTagsConfig.js'

const props = defineProps({
  pet: {
    type: Object,
    required: true,
  },
})

const { t } = useI18n()

const petTags = getPetTags()

const petTagObjects = computed(() => {
  const p = props.pet
  if (!p?.tags || !Array.isArray(p.tags)) return []
  return p.tags.map(tagId => petTags[tagId]).filter(Boolean)
})

const personalityTraits = computed(() => {
  const p = props.pet
  if (!p?.tags || !Array.isArray(p.tags)) return []
  return p.tags.map(tagId => petTags[tagId]?.name).filter(Boolean)
})

const characteristics = computed(() => {
  const p = props.pet || {}
  const items = []
  if (p.age) items.push({ key: 'age', label: t('dashboard.mvp.age'), value: p.age })
  if (p.breed) items.push({ key: 'breed', label: t('dashboard.mvp.breed'), value: p.breed })
  if (p.gender || p.sex) {
    const isMale = (p.gender === 'male') || (String(p.sex).toLowerCase() === 'male' || String(p.sex).toLowerCase() === 'm')
    items.push({ key: 'gender', label: t('dashboard.mvp.gender'), value: isMale ? t('dashboard.mvp.male') : t('dashboard.mvp.female') })
  }
  return items
})

const isPetAvailable = computed(() => {
  return props.pet?.status === t('dashboard.mvp.availablemale') || props.pet?.status === t('dashboard.mvp.availablefemale')
})
</script>

<template>
  <div class="max-w-5xl mx-auto">
    <div class="mb-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="heading-lg">{{ props.pet?.name }}</h1>
          <p v-if="props.pet?.breed" class="mt-1 text-lg text-gray-600">{{ props.pet.breed }}</p>
        </div>
        <div v-if="props.pet?.status" class="mt-4 mr-4">
          <div 
            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium border"
            :class="isPetAvailable 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-200 dark:border-emerald-800' 
              : 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900/20 dark:text-gray-200 dark:border-gray-700'"
          >
            <span 
              class="size-2 rounded-full"
              :class="isPetAvailable ? 'bg-emerald-500' : 'bg-gray-400'"
            />
            <span>{{ props.pet.status }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full my-4 space-y-2">
      <div class="mb-8"> 
        <h2 class="heading-xl">{{ t('dashboard.mvp.characteristics') }}</h2>
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-y-4 gap-x-6 text-gray-700">
          <li v-for="item in characteristics" :key="item.key + String(item.text)" class="flex gap-3 items-start">
            <svg v-if="item.key === 'age'" class="mt-1 size-4 text-amber-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z" />
            </svg>
            <svg v-else-if="item.key === 'breed'" class="mt-1 size-4 text-green-400 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20 3v10a8 8 0 1 1 -16 0v-10l3.432 3.432a7.963 7.963 0 0 1 4.568 -1.432c1.769 0 3.403 .574 4.728 1.546l3.272 -3.546z" />
              <path d="M2 16h5l-4 4" />
              <path d="M22 16h-5l4 4" />
              <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
              <path d="M9 11v.01" />
              <path d="M15 11v.01" />
            </svg>
            <svg v-else-if="item.key === 'gender' && ((props.pet?.gender || String(props.pet?.sex || '')).toLowerCase().startsWith('m'))" class="mt-1 size-4 text-sky-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="9" cy="15" r="5" />
              <path d="M14 10l6-6" />
              <path d="M15 4h5v5" />
            </svg>
            <svg v-else-if="item.key === 'gender' && !((props.pet?.gender || String(props.pet?.sex || '')).toLowerCase().startsWith('m'))" class="mt-1 size-4 text-pink-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="12" cy="8" r="5" />
              <path d="M12 13v8" />
              <path d="M9 18h6" />
            </svg>

            <svg v-else class="mt-1 size-4 text-blue-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 12l2 2 4-4" />
              <circle cx="12" cy="12" r="9" />
            </svg>
            <span class="text-base">
              <template v-if="item.label">
                {{ item.label }}: <span class="font-semibold">{{ item.value }}</span>
              </template>
              <template v-else>
                <span class="font-semibold">{{ item.value }}</span>
              </template>
            </span>
          </li>
        </ul>
      </div>

      <div v-if="personalityTraits.length" class="mb-8">
        <h2 class="heading-xl">{{ t('dashboard.mvp.personalityTraits') }}</h2>
        <div class="flex flex-wrap gap-2">
          <span 
            v-for="tag in petTagObjects" 
            :key="tag.name"
            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-sm font-medium justify-center truncate border"
            :class="tag.color"
          >
            <span class="text-sm shrink-0">{{ tag.emoji }}</span>
            <span class="truncate text-sm">{{ tag.name }}</span>
          </span>
        </div>
      </div>

      <div>
        <h2 class="heading-xl">{{ t('dashboard.aboutPet') }}</h2>
        <p class="text-base text-gray-700 leading-relaxed">{{ props.pet?.description }}</p>
        <div class="mt-8 space-y-6">
          <div v-if="props.pet?.health_status" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center gap-3 mb-4">
              <div class="shrink-0 size-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ t('dashboard.mvp.healthStatus.title') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.description') }}</p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div v-if="props.pet?.health_status" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="shrink-0">
                  <div v-if="props.pet.health_status === 'healthy'" class="size-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div v-else-if="props.pet.health_status === 'sick'" class="size-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                  </div>
                  <div v-else-if="props.pet.health_status === 'recovering'" class="size-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <div v-else-if="props.pet.health_status === 'critical'" class="size-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div v-else class="size-8 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ props.pet.health_status === 'healthy' ? t('dashboard.mvp.healthStatus.healthy') : 
                      props.pet.health_status === 'sick' ? t('dashboard.mvp.healthStatus.sick') :
                      props.pet.health_status === 'recovering' ? t('dashboard.mvp.healthStatus.recovering') :
                      props.pet.health_status === 'critical' ? t('dashboard.mvp.healthStatus.critical') :
                      props.pet.health_status === 'unknown' ? t('dashboard.mvp.healthStatus.unknown') : props.pet.health_status }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.healthState') }}</p>
                </div>
              </div>
            </div>

            <div v-if="props.pet?.current_treatment" class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
              <div class="flex items-start gap-3">
                <div class="shrink-0 size-6 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center mt-0.5">
                  <svg class="size-3 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-amber-900 dark:text-amber-100">{{ t('dashboard.mvp.healthStatus.currentTreatment') }}</p>
                  <p class="text-sm text-amber-800 dark:text-amber-200 mt-1">{{ props.pet.current_treatment }}</p>
                </div>
              </div>
            </div>

            <!-- Informacje medyczne -->
            <div v-if="props.pet?.vaccinated || props.pet?.dewormed || props.pet?.microchipped || props.pet?.sterilized" class="mt-6">
              <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">{{ t('dashboard.mvp.healthStatus.medicalInfo') }}</h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-if="props.pet?.vaccinated" class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <div class="size-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ t('dashboard.mvp.healthStatus.vaccinated') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.vaccinatedDesc') }}</p>
                  </div>
                </div>

                <div v-if="props.pet?.dewormed" class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <div class="size-8 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ t('dashboard.mvp.healthStatus.dewormed') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.dewormedDesc') }}</p>
                  </div>
                </div>

                <div v-if="props.pet?.microchipped" class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <div class="size-8 bg-cyan-100 dark:bg-cyan-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <rect x="4" y="4" width="16" height="16" rx="3" />
                      <path d="M8 8h8M8 12h8M8 16h5" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ t('dashboard.mvp.healthStatus.chipped') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.chippedDesc') }}</p>
                  </div>
                </div>

                <div v-if="props.pet?.sterilized" class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <div class="size-8 bg-fuchsia-100 dark:bg-fuchsia-900 rounded-full flex items-center justify-center">
                    <svg class="size-4 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M17 3v1c-.007 2.46 -.91 4.554 -2.705 6.281m-2.295 1.719c-3.328 1.99 -5 4.662 -5.008 8.014v1" />
                      <path d="M17 21.014v-1c0 -1.44 -.315 -2.755 -.932 -3.944m-4.068 -4.07c-1.903 -1.138 -3.263 -2.485 -4.082 -4.068" />
                      <path d="M8 4h9" />
                      <path d="M7 20h10" />
                      <path d="M12 8h4" />
                      <path d="M8 16h8" />
                      <path d="M3 3l18 18" />
                    </svg>
                  </div>
                  <div>
                      <p class="text-sm font-medium text-gray-900 dark:text-white">{{ t('dashboard.mvp.healthStatus.sterilized') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('dashboard.mvp.healthStatus.sterilizedDesc') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
