<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '@/Components/Header.vue'
import ImageSection from './ImageSection.vue'
import BackgroundGradient from '@/Components/BackgroundGradient.vue'
import ButtonSection from './ButtonSection.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  pets: {
    type: [Array, Object],
    default: () => [],
  },
})

const buttonSection = ref(null)
const showScrollArrow = ref(true)

const scrollToButtonSection = () => {
  buttonSection.value?.$el?.scrollIntoView({ 
    behavior: 'smooth',
    block: 'start'
  })
}

const handleScroll = () => {
  if (!buttonSection.value?.$el) return
  
  const buttonSectionTop = buttonSection.value.$el.offsetTop
  const scrollPosition = window.scrollY + window.innerHeight
  
  showScrollArrow.value = scrollPosition < buttonSectionTop - 100
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

</script>

<template>
  <Head :title="t('titles.landingPage')" />
  <BackgroundGradient />
  <Header />
  <main role="main">
  <ImageSection :pets="props.pets" />
  
  <ButtonSection ref="buttonSection" />
  
  <div 
    v-show="showScrollArrow"
    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 transition-opacity duration-300"
  >
  <button 
  @click="scrollToButtonSection"
  class="group flex flex-col items-center text-purple-600 hover:text-purple-700 transition-colors duration-200 bg-white/80 backdrop-blur-sm rounded-full px-4 py-3 shadow-lg hover:shadow-xl border border-purple-200 hover:border-purple-300"
>
    <span class="text-sm font-medium mb-2">{{ t('landing.scrollToAdopt') }}</span>
  <svg 
    class="w-6 h-6 animate-bounce group-hover:animate-none" 
    fill="none" 
    stroke="currentColor" 
    viewBox="0 0 24 24"
  >
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
  </svg>
    </button>
  </div>
  </main>
  <Footer />
</template>
