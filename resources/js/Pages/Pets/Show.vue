<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import Header from '../../Components/Header.vue'
import Footer from '../../Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import { bestMatches, dogs, cats } from '../../data/petsData.js'
import { getPetTags } from '../../data/petTagsConfig.js'

const props = defineProps({
  pet: {
    type: Object,
    default: null,
  },
})

const { t } = useI18n()

const allStaticPets = [...bestMatches, ...dogs, ...cats]

const routeId = computed(() => {
  const segments = (typeof window !== 'undefined' ? window.location.pathname : '')
    .split('/')
    .filter(Boolean)
  const last = segments[segments.length - 1]
  const parsed = Number.parseInt(last, 10)
  return Number.isNaN(parsed) ? null : parsed
})

const staticPet = computed(() => allStaticPets.find(p => p.id === routeId.value) || null)

const effectivePet = computed(() => props.pet ?? staticPet.value)

const petTags = getPetTags()
const personalityTraits = computed(() => {
  const p = effectivePet.value
  if (!p?.tags || !Array.isArray(p.tags)) return []
  return p.tags.map(tagId => petTags[tagId]?.name).filter(Boolean)
})

const imageUrls = computed(() => {
  const p = effectivePet.value
  if (!p) return []
  if (Array.isArray(p.photos) && p.photos.length) return p.photos
  if (p.imageUrl) return [p.imageUrl]
  return []
})

const characteristics = computed(() => {
  const p = effectivePet.value || {}
  const items = []
  if (p.age) items.push(`${t('dashboard.mvp.age')}: ${p.age}`)
  if (p.breed) items.push(`${t('dashboard.mvp.breed')}: ${p.breed}`)
  if (p.status) items.push(`${t('dashboard.mvp.status')}: ${p.status}`)
  if (p.gender || p.sex) {
    const isMale = (p.gender === 'male') || (String(p.sex).toLowerCase() === 'male' || String(p.sex).toLowerCase() === 'm')
    items.push(`${t('dashboard.mvp.gender')}: ${isMale ? t('dashboard.mvp.male') : t('dashboard.mvp.female')}`)
  }
  if (p.vaccinated || p.vaccinated === false) items.push(`${t('dashboard.mvp.health')}: ${p.vaccinated ? t('dashboard.mvp.vaccinated') : t('dashboard.mvp.notVaccinated')}`)
  return items
})

const pageTitle = computed(() => effectivePet.value?.name ? `${t('dashboard.mvp.meetPet')} ${effectivePet.value.name}` : 'Pet')

const currentImageIndex = ref(0)
const autoPlayInterval = ref(null)
const isTransitioning = ref(false)
const isAutoPlayActive = ref(true)

const startAutoPlay = () => {
  if (
    imageUrls.value.length > 1 &&
    isAutoPlayActive.value &&
    currentImageIndex.value < imageUrls.value.length - 1
  ) {
    autoPlayInterval.value = setInterval(() => {
      nextImage()
    }, 4000)
  }
}

const stopAutoPlay = () => {
  if (autoPlayInterval.value) {
    clearInterval(autoPlayInterval.value)
    autoPlayInterval.value = null
  }
}

const toggleAutoPlay = () => {
  isAutoPlayActive.value = !isAutoPlayActive.value
  if (isAutoPlayActive.value) {
    startAutoPlay()
  } else {
    stopAutoPlay()
  }
}

const resetAutoPlay = () => {
  stopAutoPlay()
  // Only restart if auto-play is enabled and we are not at the last image
  if (isAutoPlayActive.value && currentImageIndex.value < imageUrls.value.length - 1) {
    setTimeout(() => {
      startAutoPlay()
    }, 5000)
  }
}

const nextImage = () => {
  if (isTransitioning.value || imageUrls.value.length <= 1) return
  // prevent looping beyond last image
  if (currentImageIndex.value >= imageUrls.value.length - 1) return
  isTransitioning.value = true
  currentImageIndex.value = currentImageIndex.value + 1
  setTimeout(() => {
    isTransitioning.value = false
  }, 600) // Increased to match CSS transition
  resetAutoPlay() // Reset auto-play after manual navigation
}

const prevImage = () => {
  if (isTransitioning.value || imageUrls.value.length <= 1) return
  // prevent looping before first image
  if (currentImageIndex.value <= 0) return
  isTransitioning.value = true
  currentImageIndex.value = currentImageIndex.value - 1
  setTimeout(() => {
    isTransitioning.value = false
  }, 600) // Increased to match CSS transition
  resetAutoPlay() // Reset auto-play after manual navigation
}

const goToImage = (index) => {
  if (isTransitioning.value || index === currentImageIndex.value) return
  isTransitioning.value = true
  currentImageIndex.value = index
  setTimeout(() => {
    isTransitioning.value = false
  }, 600) // Increased to match CSS transition
  resetAutoPlay() // Reset auto-play after manual navigation
}

let touchStartX = 0
let touchEndX = 0

const handleTouchStart = (e) => {
  touchStartX = e.touches[0].clientX
}

const handleTouchEnd = (e) => {
  touchEndX = e.changedTouches[0].clientX
  handleSwipe()
}

const handleSwipe = () => {
  const swipeThreshold = 100 // Increased from 50 to reduce accidental swipes
  const swipeDistance = touchStartX - touchEndX

  if (Math.abs(swipeDistance) > swipeThreshold) {
    if (swipeDistance > 0) {
      nextImage()
    } else {
      prevImage()
    }
  }
}

onMounted(() => {
  startAutoPlay()
})

onUnmounted(() => {
  stopAutoPlay()
})
</script>

<template>
  <div class="min-h-screen bg-white">
    <Head :title="pageTitle" />
    <Header />

    <div class="w-full ">
      <div v-if="imageUrls.length > 1" class="flex justify-between items-center p-4">
        <div class="text-sm text-gray-600 flex items-center gap-2">
          {{ currentImageIndex + 1 }} z {{ imageUrls.length }} zdjęć
          <div v-if="isAutoPlayActive" class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Automatyczne przesuwanie aktywne"></div>
          <div v-else class="w-2 h-2 bg-gray-400 rounded-full" title="Automatyczne przesuwanie wstrzymane"></div>
        </div>
        <div class="flex items-center gap-4">
          <!-- Auto-play Toggle Switch -->
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Auto</span>
            <button
              @click="toggleAutoPlay"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              :class="isAutoPlayActive ? 'bg-blue-600' : 'bg-gray-200'"
            >
              <span
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                :class="isAutoPlayActive ? 'translate-x-6' : 'translate-x-1'"
              />
            </button>
          </div>
 
      </div>
    </div>

    <div
  class="relative overflow-hidden"
  style="height: min(70vh, 600px)"
  @mouseenter="isAutoPlayActive && stopAutoPlay()"
  @mouseleave="isAutoPlayActive && startAutoPlay()"
  @touchstart="handleTouchStart"
  @touchend="handleTouchEnd"
>
  <div
    class="flex transition-transform duration-500 ease-in-out items-center"
    :style="{ transform: `translateX(-${currentImageIndex * 80}%)` }"
  >
    <div
      v-for="(src, idx) in imageUrls"
      :key="idx"
      class="flex-shrink-0 w-[80%] sm:w-[60%] h-full px-2 relative transition-all duration-500"
      :class="idx === currentImageIndex ? 'scale-100 opacity-100 z-10' : 'scale-90 opacity-60 z-0'"
    >
      <img
        :src="src"
        :alt="`${effectivePet?.name || 'Pet'} - ${idx + 1}`"
        class="w-full h-full object-contain rounded-xl shadow-lg"
      />
    </div>
  </div>

  <!-- Navigation Arrows -->
  <button
    v-if="imageUrls.length > 1"
    @click="prevImage"
    :disabled="isTransitioning || currentImageIndex === 0"
    class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-4 rounded-full shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-40 disabled:cursor-not-allowed"
  >
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
  </button>

  <button
    v-if="imageUrls.length > 1"
    @click="nextImage"
    :disabled="isTransitioning || currentImageIndex === imageUrls.length - 1"
    class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-4 rounded-full shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-40 disabled:cursor-not-allowed"
  >
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button>
</div>

      <div v-if="imageUrls.length > 1" class="bg-gray-400/20 mx-auto my-4 max-w-2xl w-fit rounded-lg">
        <div class="overflow-x-auto scrollbar-hide">
          <div class="flex space-x-2 p-4 min-w-max mx-auto justify-center">
            <div
              v-for="(src, idx) in imageUrls"
              :key="idx"
              @click="goToImage(idx)"
              class="flex-shrink-0 w-20 h-20 cursor-pointe mx-4 rounded-lg overflow-hidden transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="[
                idx === currentImageIndex
                  ? 'ring-2 ring-blue-500 shadow-lg scale-105'
                  : 'hover:shadow-md'
              ]"
            >
              <img
                :src="src"
                :alt="`${effectivePet?.name || 'Pet'} miniature ${idx + 1}`"
                class="w-full h-full object-cover"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mx-auto max-w-6xl px-6 lg:px-8 py-8">
      <div class="mb-6">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">{{ effectivePet?.name }}</h1>
        <p v-if="effectivePet?.breed" class="mt-1 text-lg text-gray-600">{{ effectivePet.breed }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ t('dashboard.mvp.characteristics') }}</h2>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 text-gray-700">
              <li v-for="c in characteristics" :key="c" class="flex gap-3 items-start">
                <span class="mt-2 size-2 rounded-full bg-blue-500 flex-none" />
                <span class="text-base">{{ c }}</span>
              </li>
            </ul>
          </div>

          <div v-if="personalityTraits.length">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ t('dashboard.mvp.personalityTraits') }}</h2>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="trait in personalityTraits"
                :key="trait"
                class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium border border-gray-200 bg-gray-50 text-gray-800"
              >
                {{ trait }}
              </span>
            </div>
          </div>
        </div>

        <div class="lg:col-span-1">
          <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ t('dashboard.aboutPet') }}</h2>
          <p class="text-base text-gray-700 leading-relaxed">{{ effectivePet?.description }}</p>
        </div>
      </div>
    </div>

    <Footer />
  </div>
  
</template>

<style scoped>
::-webkit-scrollbar { display: none; }
* { -ms-overflow-style: none; scrollbar-width: none; }

.gallery-container {
  touch-action: pan-y;
}

.image-container {
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.image-transition {
  transition: opacity 0.3s ease-in-out;
}

.image-transition.fade-enter-active,
.image-transition.fade-leave-active {
  transition: opacity 0.3s ease-in-out;
}

.image-transition.fade-enter-from,
.image-transition.fade-leave-to {
  opacity: 0;
}

.nav-button {
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

.nav-button:focus-visible {
  outline: 2px solid theme('colors.blue.500');
  outline-offset: 2px;
}

.indicator-button:focus-visible {
  outline: 2px solid theme('colors.blue.500');
  outline-offset: 2px;
}

/* Animation for auto-play indicator */
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.auto-play-indicator {
  animation: pulse 2s infinite;
}

@media (max-width: 640px) {
  .gallery-container {
    height: 50vh;
  }
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>


