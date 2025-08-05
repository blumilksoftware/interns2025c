<script setup>
import PawPrints from '../../Components/PawPrints.vue'
</script>

<template>
  <div class="relative isolate overflow-hidden py-20 bg-[#fefae0]/50 backdrop-blur-sm" style="mask: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);">
    <PawPrints />
    
    <div class="h-1/2 px-6 py-12 sm:px-6 sm:py-16 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-4xl font-semibold tracking-tight text-balance text-black sm:text-5xl">Give a loving home to a furry friend today.</h2>
        <p class="mx-auto mt-6 max-w-xl text-lg/8 text-pretty text-black">Every animal deserves a second chance. Find your perfect companion and make a difference in their life.</p>
      </div>
    </div>

    <div class="h-1/2 px-6 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 h-full items-center">
        <div class="flex justify-center items-center">
          <button type="button" class="w-full sm:w-auto rounded-full bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors duration-200">
            Adopt Now
          </button>
        </div>
        <div class="flex justify-center items-center">
          <button type="button" class="w-full sm:w-auto rounded-full bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors duration-200">
            Donate
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
