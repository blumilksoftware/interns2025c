<script setup>
import Header from '@/Components/Header.vue'
import ImageSection from './LandingPage/ImageSection.vue'
import BackgroundGradient from '@/Components/BackgroundGradient.vue'
import ButtonSection from './LandingPage/ButtonSection.vue'
import Footer from '@/Components/Footer.vue'
import { useI18n } from 'vue-i18n'
import { Head } from '@inertiajs/vue3'
const { t } = useI18n()

const title = t('title.userProfile')
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
