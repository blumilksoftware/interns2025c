<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import Header from '../../Components/Header.vue'
import Footer from '../../Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import { bestMatches, dogs, cats } from '../../data/petsData.js'
import PetGallery from './Partials/PetGallery.vue'
import PetDetails from './Partials/PetDetails.vue'
import PetLocation from './Partials/PetLocation.vue'

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

const pageTitle = computed(() => effectivePet.value?.name ? `${t('dashboard.mvp.meetPet')} ${effectivePet.value.name}` : 'Pet')
</script>

<template>
  <div class="min-h-screen bg-white">
    <Head :title="pageTitle" />     
    <Header />

    <PetGallery v-if="effectivePet" :pet="effectivePet" />

    <div class="mx-auto max-w-6xl px-6 lg:px-2 py-4">
      <PetDetails v-if="effectivePet" :pet="effectivePet" />
    </div>

    <PetLocation />

    <Footer />
  </div>
</template>

<style scoped>
</style>


  
