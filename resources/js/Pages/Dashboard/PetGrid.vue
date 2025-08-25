<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import PetStrip from '../../Components/PetStrip.vue'
import { bestMatches, dogs, cats } from '../../data/petsData.js'
import { getPetTags } from '../../data/petTagsConfig.js'

const props = defineProps({
  showPetList: {
    type: Boolean,
    default: false,
  },
  currentPetList: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['showPetList', 'hidePetList'])

const petTags = getPetTags()

const { t } = useI18n()

const getPetTagsForPet = (pet) => {
  if (!pet.tags || !Array.isArray(pet.tags)) return []
  return pet.tags.map(tagId => petTags[tagId]).filter(Boolean)
}

const bestMatchesRest = computed(() => bestMatches.slice(1))

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
                  class="w-full h-auto object-cover" 
                  :src="pet.imageUrl" 
                  :alt="`${pet.name} - ${pet.breed}`" 
                  style="cursor: pointer;"
                  @click="$inertia.visit(`/pets/static/${pet.id}`)"
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
                  <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-sm font-semibold text-blue-800">{{ pet.age }}</span>
                  <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-sm font-semibold text-green-800">{{ pet.status }}</span>
                  <span v-if="pet.gender === 'male'" class="text-blue-400 text-xl">♂</span>
                  <span v-else class="text-pink-400 text-xl">♀</span>
                </div>
                
                <div class="flex flex-wrap gap-1 mb-3">
                  <span 
                    v-for="tag in getPetTagsForPet(pet)" 
                    :key="tag.name"
                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-sm font-medium border"
                    :class="tag.color"
                  >
                    <span class="text-sm">{{ tag.emoji }}</span>
                    <span class="text-sm">{{ tag.name }}</span>
                  </span>
                </div>
              </div>
              
              <div class="w-full md:w-80 p-4 border-t md:border-t-0 md:border-l border-gray-200 bg-gray-50">
                <h4 class="text-base font-semibold text-gray-700 mb-2">{{ t('dashboard.aboutPet') }}</h4>
                <p class="text-base text-gray-700 leading-relaxed">{{ pet.description }}</p>
                <div class="mt-3">
                  <a :href="`/pets/static/${pet.id}`" class="text-indigo-600 hover:text-indigo-800 font-semibold">{{ t('dashboard.mvp.seeMore') }} →</a>
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

<style scoped>
</style>
