<script setup lang="ts">
import { ref, onMounted } from 'vue'

type PawPrint = {
  x: number
  y: number
  size: number
  rotation: number
  opacity: number
  delay: number
}

const pawPrints = ref<PawPrint[]>([])

const props = defineProps<{ mode?: 'grid' | 'scatter' }>()

onMounted(() => {
  if (props.mode === 'grid') {
    const rows = 7
    const cols = 11
    const jitter = 3.5
    const baseSize = 0.95
    const items: PawPrint[] = []
    let left = true
    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        const x = (c + 0.5) * (100 / cols) + (Math.random() - 0.5) * jitter
        const y = (r + 0.5) * (100 / rows) + (Math.random() - 0.5) * jitter
        const rotation = (left ? -12 : 12) + (Math.random() - 0.5) * 10
        const size = baseSize + (Math.random() - 0.5) * 0.25
        items.push({
          x: Math.max(4, Math.min(96, x)),
          y: Math.max(4, Math.min(96, y)),
          size,
          rotation,
          opacity: 0.22 + Math.random() * 0.2,
          delay: (r * cols + c) * 0.015,
        })
        left = !left
      }
    }
    pawPrints.value = items
  } else {
    // scatter (default)
    const count = 90
    const minDist = 6.5
    const minDist2 = minDist * minDist
    const items: PawPrint[] = []
    let attempts = 0
    const maxAttempts = 20000
    while (items.length < count && attempts < maxAttempts) {
      attempts++
      const x = 5 + Math.random() * 90
      const y = 6 + Math.random() * 88
      let ok = true
      for (let i = 0; i < items.length; i++) {
        const dx = items[i].x - x
        const dy = items[i].y - y
        if (dx * dx + dy * dy < minDist2) { ok = false; break }
      }
      if (!ok) continue
      items.push({
        x,
        y,
        size: 0.9 + Math.random() * 0.35,
        rotation: (Math.random() - 0.5) * 180,
        opacity: 0.2 + Math.random() * 0.22,
        delay: items.length * 0.01,
      })
    }
    pawPrints.value = items
  }
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
      <svg width="60" height="60" viewBox="0 0 60 60" class="text-gray-300 dark:text-gray-500/50">
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
