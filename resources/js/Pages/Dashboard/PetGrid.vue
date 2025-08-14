<script setup>
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import PetStrip from '../../Components/PetStrip.vue'
import { bestMatches, dogs, cats } from '../../data/petsData.js'

const { t } = useI18n()

const showPetList = ref(false)
const currentPetList = ref(null)

// Funkcja pomocnicza do tagów
const petTags = {
  friendly: { name: 'Przyjazny', emoji: '😊', color: 'bg-white text-green-600 border-green-300' },
  gentle: { name: 'Łagodny', emoji: '🥰', color: 'bg-white text-pink-600 border-pink-300' },
  energetic: { name: 'Energetyczny', emoji: '⚡', color: 'bg-white text-yellow-600 border-yellow-300' },
  playful: { name: 'Zabawowy', emoji: '🎾', color: 'bg-white text-blue-600 border-blue-300' },
  calm: { name: 'Spokojny', emoji: '😌', color: 'bg-white text-indigo-600 border-indigo-300' },
  loyal: { name: 'Lojalny', emoji: '❤️', color: 'bg-white text-red-600 border-red-300' },
  smart: { name: 'Inteligentny', emoji: '🧠', color: 'bg-white text-purple-600 border-purple-300' },
  protective: { name: 'Opiekuńczy', emoji: '🛡️', color: 'bg-white text-gray-600 border-gray-300' },
  social: { name: 'Towarzyski', emoji: '👥', color: 'bg-white text-teal-600 border-teal-300' },
  active: { name: 'Aktywny', emoji: '🏃', color: 'bg-white text-orange-600 border-orange-300' }
}

const getPetTags = (pet) => {
  if (!pet.tags || !Array.isArray(pet.tags)) return []
  return pet.tags.map(tagId => petTags[tagId]).filter(Boolean)
}

const handleShowPetList = (data) => {
  showPetList.value = true
  currentPetList.value = data
}

const handleHidePetList = () => {
  showPetList.value = false
  currentPetList.value = null
}
</script>

<template>
  <div class="mx-auto max-w-6xl px-6 lg:px-8">
    <!-- Widok listy zwierzaków -->
    <div v-if="showPetList && currentPetList" class="fixed inset-0 bg-white z-50 overflow-y-auto">
      <div class="sticky top-0 bg-white border-b border-gray-200 shadow-sm z-10">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4">
          <div class="flex items-center gap-4">
            <button 
              @click="handleHidePetList"
              class="back-button flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-200"
            >
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <h1 class="title-animation text-2xl font-bold text-gray-900">{{ currentPetList.title }}</h1>
          </div>
        </div>
      </div>
      
      <div class="max-w-6xl mx-auto px-6 lg:px-8 py-6">
        <TransitionGroup name="list" tag="div" class="space-y-4">
          <div 
            v-for="(pet, index) in currentPetList.pets" 
            :key="pet.id" 
            :style="{ animationDelay: `${index * 0.1}s` }"
            class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden transform hover:scale-[1.02]"
          >
            <div class="flex">
              <!-- Zdjęcie zwierzaka -->
              <div class="w-32 h-32 flex-shrink-0">
                <img 
                  class="w-full h-full object-cover" 
                  :src="pet.imageUrl" 
                  :alt="`${pet.name} - ${pet.breed}`" 
                />
              </div>
              
              <!-- Informacje o zwierzaku -->
              <div class="flex-1 p-4">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ pet.name }}</h3>
                    <p class="text-sm text-gray-500">{{ pet.breed }}</p>
                  </div>
                </div>
                
                <!-- Status i wiek -->
                <div class="flex items-center gap-2 mb-3">
                  <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">{{ pet.age }}</span>
                  <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">{{ pet.status }}</span>
                  <span v-if="pet.gender === 'male'" class="text-blue-400 text-lg">♂</span>
                  <span v-else class="text-pink-400 text-lg">♀</span>
                </div>
                
                <!-- Tagi -->
                <div class="flex flex-wrap gap-1 mb-3">
                  <span 
                    v-for="tag in getPetTags(pet)" 
                    :key="tag.name"
                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium border"
                    :class="tag.color"
                  >
                    <span class="text-xs">{{ tag.emoji }}</span>
                    <span class="text-xs">{{ tag.name }}</span>
                  </span>
                </div>
              </div>
              
              <!-- Opis zwierzaka -->
              <div class="description-section w-80 p-4 border-l border-gray-200 bg-gray-50">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">O zwierzaku</h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ pet.description }}</p>
              </div>
            </div>
          </div>
        </TransitionGroup>
      </div>
    </div>

    <!-- Oryginalny widok PetGrid -->
    <div v-else>
      <PetStrip 
        title="Reszta najlepszych dopasowań" 
        :pets="bestMatches" 
        @showPetList="handleShowPetList"
        @hidePetList="handleHidePetList"
      />
      <PetStrip 
        title="Psy" 
        :pets="dogs" 
        @showPetList="handleShowPetList"
        @hidePetList="handleHidePetList"
      />
      <PetStrip 
        title="Koty" 
        :pets="cats" 
        @showPetList="handleShowPetList"
        @hidePetList="handleHidePetList"
      />
    </div>
  </div>
</template>

<style scoped>
/* Animacja dla listy zwierzaków */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

.list-move {
  transition: transform 0.5s ease;
}

/* Animacja dla przycisku powrotu */
.back-button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.back-button:hover {
  transform: translateX(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Animacja pojawiania się elementów listy */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.list-enter-active {
  animation: fadeInUp 0.6s ease forwards;
}

/* Animacja dla tytułu */
.title-animation {
  animation: slideInFromLeft 0.8s ease-out;
}

@keyframes slideInFromLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Animacja dla sekcji opisu */
.description-section {
  animation: fadeInRight 0.8s ease-out 0.3s both;
  transition: all 0.3s ease;
}

.description-section:hover {
  background-color: #f8fafc;
  border-left-color: #cbd5e1;
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>