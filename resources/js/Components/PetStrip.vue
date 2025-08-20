<script setup>
import { defineProps, ref, nextTick, defineEmits } from 'vue'
import { useI18n } from 'vue-i18n'
import { HeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as HeartOutlineIcon } from '@heroicons/vue/24/outline'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import { getPetTags } from '../data/petTagsConfig.js'

const { t } = useI18n()

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  pets: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['showPetList', 'hidePetList'])

const likedPets = ref(new Set())
const scrollContainer = ref(null)
const canScrollLeft = ref(false)
const canScrollRight = ref(true)

const petTags = getPetTags()

const toggleLike = (petId) => {
  if (likedPets.value.has(petId)) {
    likedPets.value.delete(petId)
  } else {
    likedPets.value.add(petId)
  }
}

const getPetTagsForPet = (pet) => {
  if (!pet.tags || !Array.isArray(pet.tags)) return []
  return pet.tags.map(tagId => petTags[tagId]).filter(Boolean)
}

const checkScrollPosition = () => {
  if (!scrollContainer.value) return
  
  const { scrollLeft, scrollWidth, clientWidth } = scrollContainer.value
  canScrollLeft.value = scrollLeft > 0
  canScrollRight.value = scrollLeft < scrollWidth - clientWidth - 1
}

const scrollLeft = () => {
  if (!scrollContainer.value) return
  scrollContainer.value.scrollBy({ left: -300, behavior: 'smooth' })
}

const scrollRight = () => {
  if (!scrollContainer.value) return
  scrollContainer.value.scrollBy({ left: 300, behavior: 'smooth' })
}

const showPetListHandler = () => {
  emit('showPetList', { title: props.title, pets: props.pets })
}

nextTick(() => {
  checkScrollPosition()
  if (scrollContainer.value) {
    scrollContainer.value.addEventListener('scroll', checkScrollPosition)
  }
})
</script>

<template>
  <div class="mb-12">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">{{ title }}</h2>
      <button 
        class="relative overflow-hidden text-sm text-purple-600 hover:text-purple-800 font-medium transition-transform duration-300 hover:translate-x-1 after:content-[''] after:absolute after:-bottom-0.5 after:left-0 after:w-0 after:h-0.5 after:bg-gradient-to-r after:from-purple-600 after:to-purple-500 hover:after:w-full after:transition-[width] after:duration-300"
        @click="showPetListHandler"
      >
        {{ t('landing.mvp.seeMore') }} →
      </button>
    </div>
    
    <div class="relative group">
      <button 
        v-show="canScrollLeft"
        class="absolute left-2 top-1/2 -translate-y-1/2 z-10 size-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
        @click="scrollLeft"
      >
        <ChevronLeftIcon class="size-6 text-gray-700" />
      </button>
      
      <button 
        v-show="canScrollRight"
        class="absolute right-2 top-1/2 -translate-y-1/2 z-10 size-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
        @click="scrollRight"
      >
        <ChevronRightIcon class="size-6 text-gray-700" />
      </button>
      
      <div 
        ref="scrollContainer"
        class="flex gap-4 overflow-x-auto pb-4 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
      >
        <div 
          v-for="pet in pets" 
          :key="pet.id" 
          class="shrink-0 w-72 sm:w-80 bg-white rounded-xl shadow-lg ring-2 ring-gray-100 hover:shadow-xl hover:ring-blue-200 transition-all duration-300 overflow-hidden relative"
        >
          <div class="relative aspect-square">
            <img class="size-full object-cover" :src="pet.imageUrl" :alt="`${pet.name} - ${pet.breed}`">

            <button 
              class="absolute top-3 right-3 size-8 flex items-center justify-center bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 z-10 hover:scale-110 active:scale-95" 
              @click="toggleLike(pet.id)"
            >
              <HeartIcon v-if="likedPets.has(pet.id)" class="size-5 text-purple-600 animate-heartbeat [transition:all_0.3s_cubic-bezier(0.68,_-0.55,_0.265,_1.55)]" />
              <HeartOutlineIcon v-else class="size-5 text-purple-600 [transition:all_0.3s_cubic-bezier(0.68,_-0.55,_0.265,_1.55)] hover:scale-110" />
            </button>
            
            <div class="absolute bottom-3 right-3 size-8 flex items-center justify-center text-white text-2xl font-bold drop-shadow-lg bg-white/70 rounded-full pointer-events-none">
              <span v-if="pet.gender === 'male'" class="text-blue-400">♂</span>
              <span v-else class="text-pink-400">♀</span>
            </div>
          </div>
          
          <div class="flex flex-1 flex-col p-4 text-center">
            <div class="flex flex-col items-center mb-2">
              <h3 class="text-lg font-bold text-gray-900">{{ pet.name }}</h3>
              <span class="text-sm text-gray-500">{{ pet.breed }}</span>
            </div>
            
            <div class="flex items-center justify-center gap-2 mb-2">
              <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">{{ pet.age }}</span>
              <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">{{ pet.status }}</span>
            </div>
            
            <div class="border-t border-gray-200 my-3" />
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 mb-2">
              <span 
                v-for="tag in getPetTagsForPet(pet)" 
                :key="tag.name"
                class="inline-flex items-center gap-1 rounded-full px-1.5 py-0.5 text-xs font-medium justify-center truncate border"
                :class="tag.color"
              >
                <span class="text-xs shrink-0">{{ tag.emoji }}</span>
                <span class="truncate text-xs">{{ tag.name }}</span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style> 
