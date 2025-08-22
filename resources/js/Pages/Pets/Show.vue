<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
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

const petTagObjects = computed(() => {
  const p = effectivePet.value
  if (!p?.tags || !Array.isArray(p.tags)) return []
  return p.tags.map(tagId => petTags[tagId]).filter(Boolean)
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

const carouselTransform = computed(() => {
  return `translateX(calc(-${currentImageIndex.value} * 50vw + 25vw))`
})

const prevImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--
  }
}

const nextImage = () => {
  if (currentImageIndex.value < imageUrls.value.length - 1) {
    currentImageIndex.value++
  }
}

const isFullscreen = ref(false)

const openFullscreen = (index) => {
  currentImageIndex.value = index
  isFullscreen.value = true
}

const closeFullscreen = () => {
  isFullscreen.value = false
}

const handleKeydown = (e) => {
  if (!isFullscreen.value) return
  if (e.key === 'Escape') {
    isFullscreen.value = false
  } else if (e.key === 'ArrowRight') {
    nextImage()
  } else if (e.key === 'ArrowLeft') {
    prevImage()
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeydown)
})

</script>

<template>
  <div class="min-h-screen bg-white">
    <Head :title="pageTitle" />     
    <Header />
    <div class="relative w-full overflow-hidden bg-gray-300/60 px-20 py-4 rounded-lg dark:bg-neutral-900">
      <div class="relative flex h-96 justify-center">
        <div 
          class="flex transition-transform duration-700 h-96 ml-[50vw]" 
          :style="{ transform: carouselTransform }"
        >
          <div 
            v-for="(url, index) in imageUrls" 
            :key="index"
            class="w-[50vw] shrink-0 px-[5vw]"
          >
            <div class="flex justify-center h-full bg-gray-100 dark:bg-neutral-900">
              <img
                :src="url"
                :alt="`${effectivePet?.name || 'Pet'} - ${index + 1}`"
                class="size-full object-cover hover:scale-105 transition-transform duration-300 rounded-lg cursor-zoom-in"
                @click="openFullscreen(index)"
              >
            </div>
          </div>
        </div>
      </div>

      <button 
        type="button" 
        :disabled="currentImageIndex === 0"
        class="absolute inset-y-0 start-0 inline-flex justify-center items-center w-11.5 h-full text-gray-800 hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-s-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="prevImage"
      >
        <span class="text-2xl" aria-hidden="true">
          <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6" />
          </svg>
        </span>
        <span class="sr-only">Previous</span>
      </button>
      <button 
        type="button" 
        :disabled="currentImageIndex >= imageUrls.length - 1"
        class="absolute inset-y-0 end-0 inline-flex justify-center items-center w-11.5 h-full text-gray-800 hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-e-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="nextImage"
      >
        <span class="sr-only">Next</span>
        <span class="text-2xl" aria-hidden="true">
          <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6" />
          </svg>
        </span>
      </button>
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
          <div class="mt-4 flex flex-wrap gap-2">
            <span v-if="effectivePet?.age" class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-sm font-semibold text-blue-800">{{ effectivePet.age }}</span>
            <span v-if="effectivePet?.status" class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-sm font-semibold text-green-800">{{ effectivePet.status }}</span>
          </div>
          <div v-if="petTagObjects.length" class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-1">
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
      </div>
    </div>


    <Footer />

    <!-- Fullscreen Image Viewer -->
    <div
      v-if="isFullscreen"
      class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
      @click.self="closeFullscreen"
    >
      <button
        type="button"
        class="absolute top-4 right-4 text-white/80 hover:text-white"
        aria-label="Close"
        @click="closeFullscreen"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6 6 18" />
          <path d="m6 6 12 12" />
        </svg>
      </button>

      <button
        v-if="currentImageIndex > 0"
        type="button"
        class="absolute left-4 md:left-8 text-white/80 hover:text-white p-2"
        aria-label="Previous"
        @click.stop="prevImage"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m15 18-6-6 6-6" />
        </svg>
      </button>

      <img
        :src="imageUrls[currentImageIndex]"
        :alt="`${effectivePet?.name || 'Pet'} - ${currentImageIndex + 1}`"
        class="max-w-[90vw] max-h-[90vh] object-contain rounded"
      >

      <button
        v-if="currentImageIndex < imageUrls.length - 1"
        type="button"
        class="absolute right-4 md:right-8 text-white/80 hover:text-white p-2"
        aria-label="Next"
        @click.stop="nextImage"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6" />
        </svg>
      </button>
    </div>
    <!-- /Fullscreen Image Viewer -->
  </div>
</template>

<style scoped>
</style>


  
