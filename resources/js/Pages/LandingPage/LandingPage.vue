<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '@/Components/Header.vue'
import ImageSection from './ImageSection.vue'
import ButtonSection from './ButtonSection.vue'
import BackgroundGradient from '@/Components/BackgroundGradient.vue'
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
</script>

<template>
  <Head :title="t('titles.landingPage')" />
  <BackgroundGradient />
  <Header />
  <main role="main">
    <ImageSection :pets="props.pets" />
    <ButtonSection />
  </main>
  <Footer />
</template>
