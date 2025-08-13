<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  pets: {
    type: Array,
    required: true
  }
})
</script>

<template>
  <div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ title }}</h2>
    <ul role="list" class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
      <li v-for="pet in pets" :key="pet.id" class="col-span-1 flex flex-col rounded-xl bg-white text-center shadow-lg ring-2 ring-gray-100 hover:shadow-xl hover:ring-blue-200 transition-all duration-300 overflow-hidden relative">
        <div class="relative aspect-square">
          <img class="w-full h-full object-cover" :src="pet.imageUrl" :alt="`${pet.name} - ${pet.breed}`" />
          <div class="absolute bottom-3 right-3 w-8 h-8 flex items-center justify-center text-white text-2xl font-bold drop-shadow-lg bg-white/70  rounded-full pointer-events-none">
            <span v-if="pet.gender === 'male'" class="text-blue-400">♂</span>
            <span v-else class="text-pink-400">♀</span>
          </div>
        </div>
        <div class="flex flex-1 flex-col p-4 text-center">
          <div class="flex flex-col items-center mb-2">
            <h3 class="text-lg font-bold text-gray-900">{{ pet.name }}</h3>
            <span class="text-sm text-gray-500">{{ pet.breed }}</span>
          </div>
          
          <div class="flex items-center justify-center gap-2 mb-2">
            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">{{ pet.age }}</span>
            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">{{ pet.status }}</span>
          </div>
          
          <p class="text-xs text-gray-600 line-clamp-2 leading-tight">{{ pet.description }}</p>
        </div>
      </li>
    </ul>
  </div>
</template>

<style scoped>
</style> 