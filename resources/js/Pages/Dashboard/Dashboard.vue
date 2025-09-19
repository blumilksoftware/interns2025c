<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import Header from '@/Components/Header.vue'
import MVPSection from './MVPSection.vue'
import PetGrid from './PetGrid.vue'
import Footer from '@/Components/Footer.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { usePreferencesStore } from '@/stores/preferences'
import { routes } from '@/routes'
import { scorePet } from '@/helpers/scoring'

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

onMounted(() => {
  if (prefs.isEmpty) {
    router.get(routes.preferences.index(), {}, { replace: true })
  }
})

const page = usePage()
const petImageFor = (_base, idx) => `https://placedog.net/500?id=${idx + 1}`

const normalizeEnum = (v) => (v && typeof v === 'object') ? (('value' in v) ? v.value : (('name' in v) ? v.name : String(v))) : v

const sourcePets = computed(() => Array.isArray(page.props.pets) ? page.props.pets : (page.props.pets?.data || []))
const pets = computed(() => (sourcePets.value || []).map((p, idx) => {
  const base = (p && typeof p === 'object' && 'pet' in p) ? p.pet : p
  const sexNormalized = normalizeEnum(base.sex)
  const sexValue = String(sexNormalized ?? '').toLowerCase()
  const rawStatus = normalizeEnum(base.adoption_status ?? base.status)
  let statusLabel = rawStatus
  if (String(rawStatus).toLowerCase() === 'available') {
    statusLabel = (sexValue === 'male' || sexValue === 'm')
      ? (t('dashboard.mvp.availablemale'))
      : (t('dashboard.mvp.availablefemale'))
  } else if (rawStatus) {
    const statusKey = String(rawStatus).toLowerCase().replaceAll(' ', '_')
    statusLabel = t(`dashboard.mvp.statuses.${statusKey}`)
  }

  return {
    ...base,
    species: String(normalizeEnum(base.species) ?? '').trim().toLowerCase(),
    sex: sexNormalized,
    gender: base.sex ?? base.gender,
    tags: Array.isArray(base.tags) ? base.tags.map(t => (typeof t === 'string' ? t : t?.name)).filter(Boolean) : [],
    imageUrl: petImageFor(p, idx),
    status: statusLabel,
  }
}))

const scorePetWrapper = (pet) => scorePet(pet, filters.value)

const sortedPets = computed(() => [...pets.value].sort((a, b) => scorePetWrapper(b) - scorePetWrapper(a)))
const featuredPet = computed(() => sortedPets.value[0] || null)
const bestMatchesRest = computed(() => sortedPets.value.slice(1, 13))

const usedIds = computed(() => {
  const ids = new Set()
  if (featuredPet.value?.id) ids.add(featuredPet.value.id)
  for (const p of bestMatchesRest.value) {
    if (p?.id) ids.add(p.id)
  }
  return ids
})

const dogs = computed(() => sortedPets.value
  .filter(p => String(p.species).toLowerCase() === 'dog')
  .slice(0, 12))

const cats = computed(() => sortedPets.value
  .filter(p => String(p.species).toLowerCase() === 'cat')
  .slice(0, 12))

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
