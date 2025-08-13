<script setup>
import { useI18n } from 'vue-i18n'
import PetStrip from '../../Components/PetStrip.vue'
import { bestMatches, dogs, cats } from '../../data/petsData.js'

const { t } = useI18n()
</script>

<template>
  <div class="mx-auto max-w-6xl px-6 lg:px-8">
    <PetStrip title="Reszta najlepszych dopasowań" :pets="bestMatches" />
    <PetStrip title="Psy" :pets="dogs" />
    <PetStrip title="Koty" :pets="cats" />
  </div>
</template>

<style scoped>
</style>