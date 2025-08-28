<script setup>
import { computed, ref } from 'vue'
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Head, Link } from '@inertiajs/vue3'
import { bestMatches, dogs, cats } from '@/data/petsData.js'
import { getGenderInfo } from '@/helpers/mappers'
import PetGallery from './Partials/PetGallery.vue'
import PetDetails from './Partials/PetDetails.vue'
import PetLocation from './Partials/PetLocation.vue'
import PetStrip from '@/Components/PetStrip.vue'
import { useI18n } from 'vue-i18n'
import { routes } from '@/routes'

const { t } = useI18n()

const props = defineProps({
  pet: {
    type: Object,
    default: null,
  },
})

const allPets = [...bestMatches, ...dogs, ...cats]

const routeId = computed(() => {
  const path = typeof window !== 'undefined' ? window.location.pathname : ''
  const match = path.match(/\/(?:pets)(?:\/static)?\/(\d+)/)
  const id = match ? Number(match[1]) : NaN
  return Number.isFinite(id) ? id : null
})

const staticPet = computed(() => allPets.find(pet => pet.id === routeId.value) || null)

const effectivePet = computed(() => {
  if (props.pet && staticPet.value) {
    return {
      ...props.pet,
      ...staticPet.value,
    }
  }
  return props.pet ?? staticPet.value
})

const similarPets = computed(() => {
  const current = effectivePet.value
  if (!current) return []
  const currentTags = new Set(Array.isArray(current.tags) ? current.tags : [])
  const candidates = allPets.filter(pet => pet.id !== current.id)
  const scored = candidates.map(pet => {
    const petTags = Array.isArray(pet.tags) ? pet.tags : []
    const overlap = petTags.filter(tag => currentTags.has(tag)).length
    return { pet: pet, score: overlap }
  })
  const filtered = scored.filter(s => s.score > 0)
  filtered.sort((a, b) => b.score - a.score)
  return filtered.slice(0, 10).map(s => s.pet)
})

const showSimilarOverlay = ref(false)
const similarListTitle = ref('')
const similarList = ref([])

const onShowSimilar = ({ title, pets }) => {
  similarListTitle.value = title || (t('dashboard.mvp.similarPets') || 'Podobne zwierzaki')
  similarList.value = Array.isArray(pets) ? pets : []
  showSimilarOverlay.value = true
}
const onHideSimilar = () => { showSimilarOverlay.value = false }
</script>

<template>
  <div class="min-h-screen bg-white">
    <Head :title="t('titles.petShow')" />     
    <Header />

    <PetGallery v-if="effectivePet" :pet="effectivePet" />

    <div class="mx-auto max-w-6xl px-6 lg:px-2 py-4">
      <PetDetails v-if="effectivePet" :pet="effectivePet" />
    </div>

    <div v-if="similarPets.length" class="mx-auto max-w-6xl p-6 lg:px-2">
      <PetStrip 
        :title="t('dashboard.mvp.similarPets') || 'Podobne zwierzaki'" 
        :pets="similarPets"
        @show-pet-list="onShowSimilar"
        @hide-pet-list="onHideSimilar"
      />
    </div>

    <PetLocation />

    <transition 
      enter-active-class="transition-opacity duration-500"
      leave-active-class="transition-opacity duration-500"
      enter-from-class="opacity-0"
      leave-to-class="opacity-0"
      enter-to-class="opacity-100"
      leave-from-class="opacity-100"
      mode="out-in"
    >
      <div v-if="showSimilarOverlay" class="fixed inset-0 bg-white z-50 overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 shadow-sm z-10">
          <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
              <button 
                class="flex items-center justify-center size-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-200"
                @click="onHideSimilar"
              >
                <svg class="size-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <h1 class="text-2xl font-bold text-gray-900">{{ similarListTitle }}</h1>
            </div>
          </div>
        </div>

        <div class="max-w-6xl mx-auto p-6 lg:px-8">
          <TransitionGroup name="list" tag="div" class="space-y-4">
            <div 
              v-for="(similarPet, index) in similarList" 
              :key="similarPet.id" 
              :style="{ animationDelay: `${index * 0.1}s` }"
              class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden hover:scale-[1.02]"
            >
              <div class="flex flex-col md:flex-row md:items-center">
                <div class="w-full md:w-[40vw] lg:w-80 shrink-0">
                  <Link :href="`/pets/${similarPet.id}`">
                    <img 
                      class="w-full h-auto object-cover" 
                      :src="similarPet.imageUrl" 
                      :alt="`${similarPet.name} - ${similarPet.breed}`" 
                      style="cursor: pointer;"
                    >
                  </Link>
                </div>
                <div class="flex-1 p-4">
                  <div class="flex items-start justify-between mb-2">
                    <div>
                      <h3 class="text-xl font-bold text-gray-900">{{ similarPet.name }}</h3>
                      <p class="text-base text-gray-600">{{ similarPet.breed }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-sm font-semibold text-blue-800">{{ similarPet.age }}</span>
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-sm font-semibold text-green-800">{{ similarPet.status }}</span>
                    <span :class="getGenderInfo(similarPet.gender).color + ' text-xl'">{{ getGenderInfo(similarPet.gender).symbol }}</span>
                  </div>
                </div>
                <div class="w-full md:w-80 p-4 border-t md:border-t-0 md:border-l border-gray-200 bg-gray-50">
                  <h4 class="text-base font-semibold text-gray-700 mb-2">{{ t('dashboard.aboutPet') }}</h4>
                  <p class="text-base text-gray-700 leading-relaxed">{{ similarPet.description }}</p>
                  <div class="mt-3">
                    <Link :href="routes.pets.show(similarPet.id)" class="text-indigo-600 hover:text-indigo-800 font-semibold">{{ t('dashboard.mvp.seeMore') }} →</Link>
                  </div>
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>
