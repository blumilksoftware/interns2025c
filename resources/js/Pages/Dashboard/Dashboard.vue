<script setup>
import { ref } from 'vue'
import Header from '../../Components/Header.vue'
import MVPSection from './MVPSection.vue'
import PetGrid from './PetGrid.vue';
import Footer from '../../Components/Footer.vue';
import { Head } from '@inertiajs/vue3'

defineProps({
  title: {
    type: String,
    default: 'Dashboard - interns2025c'
  }
})

const showPetList = ref(false)

const handleShowPetList = () => {
  showPetList.value = true
}

const handleHidePetList = () => {
  showPetList.value = false
}
</script>

<template>
    <Head :title="title" />
    
    <Transition name="slide-fade" mode="out-in">
      <div v-if="showPetList" key="pet-list" class="fixed inset-0 bg-white z-50 overflow-y-auto">
        <PetGrid @showPetList="handleShowPetList" @hidePetList="handleHidePetList" />
      </div>
      
      <div v-else key="dashboard" class="min-h-screen">
        <Header />
        <MVPSection />
        <PetGrid @showPetList="handleShowPetList" @hidePetList="handleHidePetList" />
        <Footer />
      </div>
    </Transition>
</template>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-100%);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
  opacity: 1;
  transform: translateX(0);
}
</style>