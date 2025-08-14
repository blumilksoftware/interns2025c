<script setup>
import Header from '../../Components/Header.vue'
import ImageSection from './ImageSection.vue'
import BackgroundGradient from '../../Components/BackgroudGradient.vue'
import ButtonSection from './ButtonSection.vue'
import Footer from '../../Components/Footer.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
  title: {
    type: String,
    default: 'Home - interns2025c'
  }
})

</script>

<template>
  <Head :title="title" />
  <BackgroundGradient />
  <Header />
  <ImageSection />
  <ButtonSection />
  <Footer />
</template>

<style>
</style>
