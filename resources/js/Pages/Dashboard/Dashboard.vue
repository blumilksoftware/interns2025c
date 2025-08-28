<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import Header from '@/Components/Header.vue'
import MVPSection from './MVPSection.vue'
import PetGrid from './PetGrid.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'

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
      <MVPSection />
      <PetGrid :show-pet-list="showPetList" :current-pet-list="currentPetList" @show-pet-list="handleShowPetList" @hide-pet-list="handleHidePetList" />
      <Footer />
    </div>
  </transition>
</template>

<style scoped>
</style>
 
