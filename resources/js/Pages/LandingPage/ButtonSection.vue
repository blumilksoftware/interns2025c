<script setup>
import { useI18n } from 'vue-i18n'
import PawPrints from '@/Components/PawPrints.vue'

const { t } = useI18n()
</script>

<template>
  <div class="relative isolate overflow-hidden py-20 bg-soft-cream/50 backdrop-blur-sm " style="mask: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);">
    <PawPrints />
    
    <div class="h-1/2 px-6 py-36 sm:px-6 sm:py-16 lg:px-8 text-section">
      <div class="mx-auto bg-soft-cream rounded-full py-6  max-w-2xl text-center responsive-mask">
        <h2 class="text-4xl font-semibold tracking-tight text-balance text-black sm:text-5xl">{{ t('landing.hero.title') }}</h2>
        <p class="mx-auto mt-6 max-w-xl text-lg/8 text-pretty text-black">{{ t('landing.hero.subtitle') }}</p>
      </div>
    </div>

    <div class="h-1/2 px-6 sm:px-6 lg:px-8">
      <div class="flex justify-center items-center h-full">
        <div class="flex gap-4">
          <button type="button" class="rounded-full bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors duration-200">
            {{ t('landing.hero.adoptButton') }}
          </button>
          <button type="button" class="rounded-full bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors duration-200">
            {{ t('landing.hero.donateButton') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.responsive-mask
{
  mask: radial-gradient(circle at center, rgba(0, 0, 0, 0.97) 80%, rgba(0, 0, 0, 0) 90%),
  linear-gradient(to bottom, transparent 0%, black 7%, black 90%, transparent 100%);
  mask-composite: intersect;
}
@media (max-width: 700px) {
  .text-section {
    background-color: #fffcef;
    mask: linear-gradient(to bottom, transparent 0%, black 20%, black 85%, transparent 100%);
    -webkit-mask: linear-gradient(to bottom, transparent 0%, black 20%, black 85%, transparent 100%);

  }
  .responsive-mask {
    border-radius: 0;
    background-color:transparent;
      mask: unset;
      -webkit-mask: unset;
  }
}
</style>
