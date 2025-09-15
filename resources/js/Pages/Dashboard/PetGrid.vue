<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import PetStrip from '@/Components/PetStrip.vue'
import { getGenderInfo } from '@/helpers/mappers'
import { formatAge } from '@/helpers/formatters/age.ts'
import { Link } from '@inertiajs/vue3'
import { routes } from '@/routes'

const props = defineProps({
  showPetList: {
    type: Boolean,
    default: false,
  },
  currentPetList: {
    type: Object,
    default: null,
  },
  bestMatchesRest: {
    type: Array,
    default: () => [],
  },
  dogs: {
    type: Array,
    default: () => [],
  },
  cats: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['showPetList', 'hidePetList'])

const { t } = useI18n()

const getPetTagsForPet = (pet) => Array.isArray(pet.tags)
  ? pet.tags.map((t) => (typeof t === 'string' ? t : t?.name)).filter(Boolean)
  : []

const descriptionFor = (pet) => {
  const desc = typeof pet.description === 'string' ? pet.description.trim() : ''
  if (desc) return desc
  return t('dashboard.mvp.description', { breed: pet.breed || '', name: pet.name || '' })
}

const handleShowPetList = (data) => {
  emit('showPetList', data)
}

const handleHidePetList = () => {
  emit('hidePetList')
}
</script>

<template>
  <div class="mx-auto max-w-6xl px-6 lg:px-8">
    <div v-if="props.showPetList && props.currentPetList" class="fixed inset-0 bg-white z-50 overflow-y-auto">
      <div class="sticky top-0 bg-white border-b border-gray-200 shadow-sm z-10">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4">
          <div class="flex items-center gap-4">
            <button 
              class="back-button flex items-center justify-center size-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-200"
              @click="handleHidePetList"
            >
              <svg class="size-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <h1 class="title-animation text-2xl font-bold text-gray-900">{{ props.currentPetList.title }}</h1>
          </div>
        </div>
      </div>
      
      <div class="max-w-6xl mx-auto p-6 lg:px-8">
        <TransitionGroup name="list" tag="div" class="space-y-4">
          <div 
            v-for="(pet, index) in props.currentPetList.pets" 
            :key="pet.id" 
            :style="{ animationDelay: `${index * 0.1}s` }"
            class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden hover:scale-[1.02]"
          >
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="w-full md:w-[40vw] lg:w-80 shrink-0">
                <img 
                  class="w-full h-auto object-cover cursor-pointer" 
                  :src="pet.imageUrl" 
                  :alt="`${pet.name} - ${pet.breed}`" 
                  @click="handleShowPetList(pet)"
                >
              </div>
              
              <div class="flex-1 p-4">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ pet.name }}</h3>
                    <p class="text-base text-gray-600">{{ pet.breed }}</p>
                  </div>
                </div>
                
                <div class="flex items-center gap-2 mb-3">
                  <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-sm font-semibold text-blue-800">{{ formatAge(pet.age) }}</span>
                  <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-sm font-semibold text-green-800">{{ pet.status }}</span>
                  <span :class="getGenderInfo(pet.gender).color + ' text-xl'">{{ getGenderInfo(pet.gender).symbol }}</span>
                </div>
                
                <div class="flex flex-wrap gap-1 mb-3">
                  <span 
                    v-for="tag in getPetTagsForPet(pet)" 
                    :key="`${pet.id}-${tag}`"
                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-sm font-medium border"
                  >
                    <span class="text-sm">{{ tag }}</span>
                  </span>
                </div>
              </div>
              
              <div class="w-full md:w-80 p-4 border-t md:border-t-0 md:border-l border-gray-200 bg-gray-50">
                <h4 class="text-base font-semibold text-gray-700 mb-2">{{ t('dashboard.aboutPet') }}</h4>
                <p class="text-base text-gray-700 leading-relaxed">{{ descriptionFor(pet) }}</p>
                <div class="mt-3">
                  <Link :href="routes.pets.show(pet.id)" class="text-indigo-600 hover:text-indigo-800 font-semibold">{{ t('dashboard.mvp.seeMore') }} →</Link>
                </div>
              </div>
            </div>
          </div>
        </TransitionGroup>
      </div>
    </div>

    <div v-else>
      <PetStrip 
        :title="t('dashboard.restBestMatches')" 
        :pets="bestMatchesRest" 
        @show-pet-list="handleShowPetList"
        @hide-pet-list="handleHidePetList"
      />
      <PetStrip 
        :title="t('dashboard.dogs')" 
        :pets="dogs" 
        @show-pet-list="handleShowPetList"
        @hide-pet-list="handleHidePetList"
      />
      <PetStrip 
        :title="t('dashboard.cats')" 
        :pets="cats" 
        @show-pet-list="handleShowPetList"
        @hide-pet-list="handleHidePetList"
      />
    </div>
  </div>
</template>
