<script setup>
import { ref, onMounted } from 'vue'

const pawPrints = ref([])

const generatePawPrint = () => {
  return {
    x: Math.random() * 100, 
    y: Math.random() * 100,
    size: Math.random() * 0.8 + 0.8,
    rotation: Math.random() * 360,
    opacity: Math.random() * 0.1 + 0.3,
    delay: Math.random() * 2,
  }
}

onMounted(() => {
  const count = Math.floor(Math.random() * 11) + 40
  pawPrints.value = Array.from({ length: count }, generatePawPrint)
})
</script>

<template>
  <div class="absolute inset-0 pointer-events-none overflow-hidden -z-10">
    <div 
      v-for="(paw, index) in pawPrints" 
      :key="index"
      class="absolute paw-print"
      :style="{
        left: paw.x + '%',
        top: paw.y + '%',
        transform: `translate(-50%, -50%) rotate(${paw.rotation}deg) scale(${paw.size})`,
        opacity: paw.opacity,
        animationDelay: paw.delay + 's'
      }"
    >
      <svg width="60" height="60" viewBox="0 0 60 60" class="text-gray-300">
        <g fill="currentColor">
          <circle cx="30" cy="37" r="12" />
          <circle cx="18" cy="22" r="6" />
          <circle cx="30" cy="18" r="6" />
          <circle cx="42" cy="22" r="6" />
        </g>
      </svg>
    </div>
  </div>
</template>

<style scoped>
.paw-print {
  animation: fadeIn 0.5s ease-in-out forwards;
  opacity: 0;
}

@keyframes fadeIn {
  to {
    opacity: 1;
  }
}
</style>
