<script setup>
import { defineProps, ref, nextTick, defineEmits } from 'vue'
import { useI18n } from 'vue-i18n'
import { HeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as HeartOutlineIcon } from '@heroicons/vue/24/outline'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'

const { t } = useI18n()

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  pets: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['showPetList', 'hidePetList'])

const likedPets = ref(new Set())
const scrollContainer = ref(null)
const canScrollLeft = ref(false)
const canScrollRight = ref(true)

const petTags = {
  friendly: { name: t('landing.petTags.friendly'), emoji: '😊', color: 'bg-white text-green-600 border-green-300' },
  gentle: { name: t('landing.petTags.gentle'), emoji: '🥰', color: 'bg-white text-pink-600 border-pink-300' },
  energetic: { name: t('landing.petTags.energetic'), emoji: '⚡', color: 'bg-white text-yellow-600 border-yellow-300' },
  playful: { name: t('landing.petTags.playful'), emoji: '🎾', color: 'bg-white text-blue-600 border-blue-300' },
  calm: { name: t('landing.petTags.calm'), emoji: '😌', color: 'bg-white text-indigo-600 border-indigo-300' },
  loyal: { name: t('landing.petTags.loyal'), emoji: '❤️', color: 'bg-white text-red-600 border-red-300' },
  smart: { name: t('landing.petTags.smart'), emoji: '🧠', color: 'bg-white text-purple-600 border-purple-300' },
  protective: { name: t('landing.petTags.protective'), emoji: '🛡️', color: 'bg-white text-gray-600 border-gray-300' },
  social: { name: t('landing.petTags.social'), emoji: '👥', color: 'bg-white text-teal-600 border-teal-300' },
  active: { name: t('landing.petTags.active'), emoji: '🏃', color: 'bg-white text-orange-600 border-orange-300' }
}

const toggleLike = (petId) => {
  if (likedPets.value.has(petId)) {
    likedPets.value.delete(petId)
  } else {
    likedPets.value.add(petId)
  }
}

const getPetTags = (pet) => {
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

// Sprawdź pozycję scroll po załadowaniu komponentu
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
        @click="showPetListHandler"
        class="see-more-button text-sm text-purple-600 hover:text-purple-800 font-medium transition-all duration-300 hover:translate-x-1"
      >
        {{ t('landing.mvp.seeMore') }} →
      </button>
    </div>
    
    <div class="relative group">
      <!-- Lewa strzałka -->
      <button 
        v-show="canScrollLeft"
        @click="scrollLeft"
        class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
      >
        <ChevronLeftIcon class="w-6 h-6 text-gray-700" />
      </button>
      
      <!-- Prawa strzałka -->
      <button 
        v-show="canScrollRight"
        @click="scrollRight"
        class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
      >
        <ChevronRightIcon class="w-6 h-6 text-gray-700" />
      </button>
      
      <!-- Kontener z przewijaniem -->
      <div 
        ref="scrollContainer"
        class="flex gap-4 overflow-x-auto scrollbar-hide pb-4"
        style="scrollbar-width: none; -ms-overflow-style: none;"
      >
        <div 
          v-for="pet in pets" 
          :key="pet.id" 
          class="flex-shrink-0 w-72 sm:w-80 bg-white rounded-xl shadow-lg ring-2 ring-gray-100 hover:shadow-xl hover:ring-blue-200 transition-all duration-300 overflow-hidden relative"
        >
          <div class="relative aspect-square">
            <img class="w-full h-full object-cover" :src="pet.imageUrl" :alt="`${pet.name} - ${pet.breed}`" />

            <!-- Przycisk like -->
            <button 
              @click="toggleLike(pet.id)" 
              class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 z-10 hover:scale-110 active:scale-95"
            >
              <HeartIcon v-if="likedPets.has(pet.id)" class="w-5 h-5 text-purple-600 heart-icon heart-animation" />
              <HeartOutlineIcon v-else class="w-5 h-5 text-purple-600 heart-icon" />
            </button>
            
            <!-- Ikona płci -->
            <div class="absolute bottom-3 right-3 w-8 h-8 flex items-center justify-center text-white text-2xl font-bold drop-shadow-lg bg-white/70 rounded-full pointer-events-none">
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
            
            <!-- Linia oddzielająca -->
            <div class="border-t border-gray-200 my-3"></div>
            
            <!-- Tagi z emotkami w 2 kolumnach -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 mb-2">
              <span 
                v-for="tag in getPetTags(pet)" 
                :key="tag.name"
                class="inline-flex items-center gap-1 rounded-full px-1.5 py-0.5 text-xs font-medium justify-center truncate border"
                :class="tag.color"
              >
                <span class="text-xs flex-shrink-0">{{ tag.emoji }}</span>
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
@keyframes heartBeat {
  0% {
    transform: scale(1);
  }
  25% {
    transform: scale(1.15);
  }
  50% {
    transform: scale(1);
  }
  75% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.heart-animation {
  animation: heartBeat 0.8s ease-out;
}

.heart-icon {
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.heart-icon:hover {
  transform: scale(1.1);
}

/* Ukryj scrollbar */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Animacja dla przycisku "Zobacz więcej" */
.see-more-button {
  position: relative;
  overflow: hidden;
}

.see-more-button::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #9333ea, #a855f7);
  transition: width 0.3s ease;
}

.see-more-button:hover::after {
  width: 100%;
}

.see-more-button:hover {
  transform: translateX(4px);
}
</style> 