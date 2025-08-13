<script setup>
import Header from '../Components/Header.vue'
import ImageSection from './LandingPage/ImageSection.vue'
import BackgroundGradient from './BackgroudGradient.vue'
import ButtonSection from './LandingPage/ButtonSection.vue'
import Footer from '../Components/Footer.vue'
import { onMounted } from 'vue'

onMounted(() => {
  document.title = 'Landing Page - interns2025c'
})
</script>

<template>
  <BackgroundGradient />
  <Header />
  <ImageSection />
  <ButtonSection />
  <Footer />
</template>

<style>
</style>
