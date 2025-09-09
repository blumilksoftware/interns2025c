<script setup>
import { Link } from '@inertiajs/vue3'
import { routes } from '@/routes'
import { ref, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'
import { HeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as HeartOutlineIcon } from '@heroicons/vue/24/outline'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import { getGenderInfo } from '@/helpers/mappers'

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

const toggleLike = (petId) => {
  if (likedPets.value.has(petId)) {
    likedPets.value.delete(petId)
  } else {
    likedPets.value.add(petId)
  }
}

const getPetTagsForPet = (pet) => Array.isArray(pet.tags) ? pet.tags.map(t => (typeof t === 'string' ? t : t?.name)).filter(Boolean) : []

const descriptionFor = (pet) => {
  const desc = typeof pet.description === 'string' ? pet.description.trim() : ''
  if (desc) return desc
  return t('dashboard.mvp.description', { breed: pet.breed || '', name: pet.name || '' })
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
  <div class="mb-8 sm:mb-12">
    <div class="flex items-center justify-between mb-4 sm:mb-6">
      <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ title }}</h2>
      <button 
        class="relative overflow-hidden text-xs sm:text-sm text-left text-purple-600 hover:text-purple-800 font-medium transition-transform duration-300 hover:translate-x-1 after:content-[''] after:absolute after:-bottom-0.5 after:left-0 after:w-0 after:h-0.5 after:bg-gradient-to-r after:from-purple-600 after:to-purple-500 hover:after:w-full after:transition-[width] after:duration-300"
        @click="showPetListHandler"
      >
        {{ t('dashboard.mvp.seeMore') }} →
      </button>
    </div>
    
    <div class="relative group">
      <button 
        v-show="canScrollLeft"
        class="absolute left-1 sm:left-2 top-1/2 -translate-y-1/2 z-10 size-8 sm:size-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
        @click="scrollLeft"
      >
        <ChevronLeftIcon class="size-4 sm:size-6 text-gray-700" />
      </button>
      
      <button 
        v-show="canScrollRight"
        class="absolute right-1 sm:right-2 top-1/2 -translate-y-1/2 z-10 size-8 sm:size-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110"
        @click="scrollRight"
      >
        <ChevronRightIcon class="size-4 sm:size-6 text-gray-700" />
      </button>
      
      <div 
        ref="scrollContainer"
        class="flex gap-3 sm:gap-4 overflow-x-auto pb-4 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
      >
        <div 
          v-for="pet in pets" 
          :key="pet.id" 
          class="shrink-0 w-64 sm:w-72 md:w-80 bg-white rounded-xl shadow-lg ring-2 m-4 ring-gray-100 hover:shadow-xl hover:ring-blue-200 focus-within:ring-2 focus-within:ring-indigo-500 transition-all duration-300 overflow-hidden relative"
        >
          <div class="relative aspect-square ">
            <Link :href="routes.pets.show(pet.id)" class="focus-visible:outline-none">
              <img class="size-full object-cover" :src="pet.imageUrl" :alt="`${pet.name} - ${pet.breed}`" @error="($event) => { $event.target.src = '/Images/cat-dog.png' }">
            </Link> 

            <button 
              class="absolute top-2 sm:top-3 right-2 sm:right-3 size-7 sm:size-8 flex items-center justify-center bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 z-10 hover:scale-110 active:scale-95" 
              @click.prevent.stop="toggleLike(pet.id)"
            >
              <HeartIcon v-if="likedPets.has(pet.id)" class="size-4 sm:size-5 text-purple-600 animate-heartbeat [transition:all_0.3s_cubic-bezier(0.68,_-0.55,_0.265,_1.55)]" />
              <HeartOutlineIcon v-else class="size-4 sm:size-5 text-purple-600 [transition:all_0.3s_cubic-bezier(0.68,_-0.55,_0.265,_1.55)] hover:scale-110" />
            </button>
            
            <div class="absolute bottom-2 sm:bottom-3 right-2 sm:right-3 size-7 sm:size-8 flex items-center justify-center text-white text-lg sm:text-2xl font-bold drop-shadow-lg bg-white/70 rounded-full pointer-events-none">
              <span :class="getGenderInfo(pet.sex).color">{{ getGenderInfo(pet.sex).symbol }}</span>
            </div>
          </div>
          
          <div class="flex flex-1 flex-col p-3 sm:p-4 text-center">
            <div class="flex flex-col items-center mb-2">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ pet.name }}</h3>
              <span class="text-sm sm:text-base text-gray-600">{{ pet.breed }}</span>
            </div>
            
            <div class="flex items-center justify-center gap-1 sm:gap-2 mb-2">
              <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs sm:text-sm font-semibold text-blue-800">{{ pet.age }}</span>
              <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs sm:text-sm font-semibold text-green-800">{{ pet.adoption_status }}</span>
            </div>
            
            <div class="border-t border-gray-200 my-2 sm:my-3" />
            
            <div class="flex flex-col gap-2 items-center">
              <p class="text-xs sm:text-sm text-gray-700 leading-relaxed text-center px-2">{{ descriptionFor(pet) }}</p>
              <div class="flex flex-wrap gap-1 sm:gap-2 justify-center">
                <span 
                  v-for="tag in getPetTagsForPet(pet)" 
                  :key="`${pet.id}-${tag}`"
                  class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs sm:text-sm font-medium justify-center truncate border max-w-full bg-gray-50 text-gray-700"
                >
                  <span class="truncate text-xs sm:text-sm">{{ tag }}</span>
                </span>
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
