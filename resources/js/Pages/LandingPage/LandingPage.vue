<script setup>
import { useI18n } from 'vue-i18n'
import Header from '../../Components/Header.vue'
import ImageSection from './ImageSection.vue'
import BackgroundGradient from '../../Components/BackgroudGradient.vue'
import ButtonSection from './ButtonSection.vue'
import Footer from '../../Components/Footer.vue'
import { onMounted } from 'vue'

const { t } = useI18n()

onMounted(() => {
  document.title = t('title.landingPage')
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
