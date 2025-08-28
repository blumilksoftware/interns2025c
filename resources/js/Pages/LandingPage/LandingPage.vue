<script setup>
import Header from '@/Components/Header.vue'
import ImageSection from './ImageSection.vue'
import BackgroundGradient from '@/Components/BackgroudGradient.vue'
import ButtonSection from './ButtonSection.vue'
import Footer from '@/Components/Footer.vue'
import { Head } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'


const { t } = useI18n()

</script>

<template>
  <Head :title="t('titles.landingPage')" />
  <BackgroundGradient />
  <Header />
  <ImageSection />
  <ButtonSection />
  <Footer />
</template>
