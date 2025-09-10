<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import Header from '@/Components/Header.vue'
import MVPSection from './MVPSection.vue'
import PetGrid from './PetGrid.vue'
import Footer from '@/Components/Footer.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { usePreferencesStore } from '@/stores/preferences'
import { samplePetImages } from '@/data/petsData.js'

const { t } = useI18n()

const showPetList = ref(false)
const currentPetList = ref(null)

const handleShowPetList = (data) => {
  currentPetList.value = data
  showPetList.value = true
}

const handleHidePetList = () => {
  showPetList.value = false
}

const prefs = usePreferencesStore()
const filters = computed(() => prefs.form || {})

  const page = usePage()
const petImageFor = (_p, idx) => `https://placedog.net/500?id=${idx + 1}`

const normalizeEnum = (v) => (v && typeof v === 'object') ? (('value' in v) ? v.value : (('name' in v) ? v.name : String(v))) : v

const pets = computed(() => (page.props.pets?.data || []).map((p, idx) => {
  const sexNormalized = normalizeEnum(p.sex)
  const sexValue = String(sexNormalized ?? '').toLowerCase()
  const rawStatus = normalizeEnum(p.adoption_status ?? p.status)
  let statusLabel = rawStatus
  if (String(rawStatus).toLowerCase() === 'available') {
    statusLabel = (sexValue === 'male' || sexValue === 'm')
      ? (t('dashboard.mvp.availablemale') || 'Dostępny')
      : (t('dashboard.mvp.availablefemale') || 'Dostępna')
  }

  return {
    ...p,
    species: normalizeEnum(p.species),
    sex: sexNormalized,
    gender: p.sex ?? p.gender,
    tags: Array.isArray(p.tags) ? p.tags.map(t => (typeof t === 'string' ? t : t?.name)).filter(Boolean) : [],
    imageUrl: petImageFor(p, idx),
    status: statusLabel,
  }
}))

const weightConfig = { species: 3, breed: 3, sex: 2, color: 1, tags: 2 }
const scorePet = (pet) => {
  const f = filters.value
  let score = 0
  if (Array.isArray(f.species) && f.species.includes(String(pet.species))) score += weightConfig.species
  if (Array.isArray(f.breed) && f.breed.includes(pet.breed)) score += weightConfig.breed
  if (f.sex && String(f.sex) === String(pet.sex)) score += weightConfig.sex
  if (Array.isArray(f.color) && f.color.includes(pet.color)) score += weightConfig.color
  if (Array.isArray(f.tags) && f.tags.length) {
    const petTags = Array.isArray(pet.tags) ? pet.tags : []
    const overlap = f.tags.filter((t) => petTags.includes(t))
    if (overlap.length) score += weightConfig.tags * Math.min(1, overlap.length / 3)
  }
  return score
}

const sortedPets = computed(() => [...pets.value].sort((a, b) => scorePet(b) - scorePet(a)))
const featuredPet = computed(() => sortedPets.value[0] || null)
const bestMatchesRest = computed(() => sortedPets.value.slice(1, 13))
const dogs = computed(() => sortedPets.value.filter(p => String(p.species) === 'dog').slice(0, 12))
const cats = computed(() => sortedPets.value.filter(p => String(p.species) === 'cat').slice(0, 12))


</script>

<template>
  <Head :title="t('titles.dashboard')" />
  
  <transition 
    enter-active-class="transition-opacity duration-500"
    leave-active-class="transition-opacity duration-500"
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
    enter-to-class="opacity-100"
    leave-from-class="opacity-100"
    mode="out-in"
  >
    <div v-if="showPetList" class="fixed inset-0 bg-white z-50 overflow-y-auto">
      <PetGrid :show-pet-list="showPetList" :current-pet-list="currentPetList" @show-pet-list="handleShowPetList" @hide-pet-list="handleHidePetList" />
    </div>
      
    <div v-else class="min-h-screen">
      <Header />
      <div class="mx-auto max-w-6xl px-6 lg:px-8 my-6">
        <div v-if="Object.keys(filters || {}).length" class="mb-6 rounded-lg border border-gray-200 bg-white p-4">
          <h3 class="font-semibold mb-2">Zastosowane preferencje</h3>
          <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ JSON.stringify(filters, null, 2) }}</pre>
        </div>
      </div>
      <MVPSection v-if="featuredPet" :pet="featuredPet" />
      <PetGrid 
        :show-pet-list="showPetList" 
        :current-pet-list="currentPetList"
        :best-matches-rest="bestMatchesRest"
        :dogs="dogs"
        :cats="cats"
        @show-pet-list="handleShowPetList"
        @hide-pet-list="handleHidePetList"
      />
      <Footer />
    </div>
  </transition>
</template>

<style scoped>
</style>
 
