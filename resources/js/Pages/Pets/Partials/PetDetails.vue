<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { getAvailableMedicalInfo, getHealthStatusInfo, getPetCharacteristics, getStatusInfo } from '@/helpers/mappers'
import PetIcons from '@/Components/Icons/PetIcons.vue'
import { formatAge } from '@/helpers/formatters/age.ts'

const props = defineProps({
  pet: {
    type: Object,
    required: true,
  },
})

const { t } = useI18n()

const personalityTraits = computed(() => {
  const pet = props.pet
  if (!pet?.tags || !Array.isArray(pet.tags)) return []
  return pet.tags.map(tag => (typeof tag === 'string' ? tag : String(tag))).filter(Boolean)
})

const petTagObjects = computed(() => {
  return personalityTraits.value.map(tag => ({
    name: tag,
    color: 'rounded-full bg-yellow-100 text-yellow-800',
  }))
})

const characteristics = computed(() => {
  return getPetCharacteristics({
    ...props.pet,
    age: Number.isFinite(Number(props.pet?.age)) ? formatAge(props.pet?.age) : props.pet?.age,
  }).map(item => ({
    ...item,
    label: t(item.label),
    value: item.value.startsWith('dashboard.mvp.') ? t(item.value) : item.value,
  }))
})

const medicalInfo = computed(() => {
  return getAvailableMedicalInfo(props.pet)
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
            :class="getStatusInfo(props.pet.status, [t('dashboard.mvp.availablemale'), t('dashboard.mvp.availablefemale')]).bgColor"
          >
            <span 
              class="size-2 rounded-full"
              :class="getStatusInfo(props.pet.status, [t('dashboard.mvp.availablemale'), t('dashboard.mvp.availablefemale')]).dotColor"
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
          <li v-for="item in characteristics" :key="item.key + String(item.value)" class="flex gap-3 items-start">
            <PetIcons :name="item.icon" />
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
            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-sm font-medium justify-center truncate shadow-lg border"
            :class="tag.color"
          >
            <span class="truncate text-sm">{{ tag.name }}</span>
          </span>
        </div>
      </div>

      <div>
        <h2 class="heading-xl">{{ t('dashboard.aboutPet') }}</h2>
        <p class="text-base text-gray-700 leading-relaxed">{{ props.pet?.description }}</p>
        <div class="mt-8 space-y-6">
          <div v-if="props.pet?.health_status" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <div class="flex items-center gap-3 mb-4">
              <div class="shrink-0 size-10 bg-blue-100 rounded-full flex items-center justify-center">
                <PetIcons name="heart" />
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ t('dashboard.mvp.healthStatus.title') }}</h3>
                <p class="text-sm text-gray-600">{{ t('dashboard.mvp.healthStatus.description') }}</p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div v-if="props.pet?.health_status" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200">
                <div class="shrink-0">
                  <div :class="`size-8 ${getHealthStatusInfo(props.pet.health_status).bgColor} rounded-full flex items-center justify-center`">
                    <PetIcons :name="getHealthStatusInfo(props.pet.health_status).icon" />
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ t(getHealthStatusInfo(props.pet.health_status).label) }}
                  </p>
                  <p class="text-xs text-gray-500">{{ t(getHealthStatusInfo(props.pet.health_status).description) }}</p>
                </div>
              </div>
            </div>

            <div v-if="props.pet?.current_treatment" class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
              <div class="flex items-start gap-3">
                <div class="shrink-0 size-6 bg-amber-100 rounded-full flex items-center justify-center mt-0.5">
                  <PetIcons name="treatment" />
                </div>
                <div>
                  <p class="text-sm font-medium text-amber-900">{{ t('dashboard.mvp.healthStatus.currentTreatment') }}</p>
                  <p class="text-sm text-amber-800 mt-1">{{ props.pet.current_treatment }}</p>
                </div>
              </div>
            </div>

            <div v-if="medicalInfo.length" class="mt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-3">{{ t('dashboard.mvp.healthStatus.medicalInfo') }}</h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div 
                  v-for="info in medicalInfo" 
                  :key="info.type"
                  class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200"
                >
                  <div :class="`size-8 ${info.bgColor} rounded-full flex items-center justify-center`">
                    <PetIcons :name="info.icon" />
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ t(info.label) }}</p>
                    <p class="text-xs text-gray-500">{{ t(info.description) }}</p>
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
