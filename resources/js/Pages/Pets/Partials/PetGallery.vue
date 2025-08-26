<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  pet: {
    type: Object,
    required: true,
  },
})

const imageUrls = computed(() => {
  const p = props.pet
  if (!p) return []
  if (Array.isArray(p.photos) && p.photos.length) return p.photos
  if (p.imageUrl) return [p.imageUrl]
  return []
})

const currentImageIndex = ref(0)
const hasMultipleImages = computed(() => imageUrls.value.length > 1)
const isDragging = ref(false)
const startX = ref(0)
const currentX = ref(0)
const dragThreshold = 50
const dragOffset = ref(0)
const hasDragged = ref(false)

const carouselTransform = computed(() => {
  if (!hasMultipleImages.value) return 'none'
  const baseTransform = `translateX(calc(-${currentImageIndex.value} * 50vw + 25vw))`
  if (isDragging.value) {
    return `${baseTransform} translateX(${dragOffset.value}px)`
  }
  return baseTransform
})

const mobileTransform = computed(() => {
  const base = `translateX(${-currentImageIndex.value * 100}%)`
  if (isDragging.value) {
    return `${base} translateX(${dragOffset.value}px)`
  }
  return base
})

const prevImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--
  }
}

const nextImage = () => {
  if (currentImageIndex.value < imageUrls.value.length - 1) {
    currentImageIndex.value++
  }
}

const handleDragStart = (e) => {
  isDragging.value = true
  hasDragged.value = false
  const clientX = e.touches ? e.touches[0].clientX : e.clientX
  startX.value = clientX
  currentX.value = clientX
  dragOffset.value = 0
  if (e && typeof e.preventDefault === 'function') {
    e.preventDefault()
  }
  if (!e.touches) {
    window.addEventListener('mousemove', handleMouseMove)
    window.addEventListener('mouseup', handleMouseUp)
  }
}

const handleDragMove = (e) => {
  if (!isDragging.value) return
  const clientX = e.touches ? e.touches[0].clientX : e.clientX
  currentX.value = clientX
  dragOffset.value = clientX - startX.value
  
  if (Math.abs(dragOffset.value) > 10) {
    hasDragged.value = true
  }
}

const handleDragEnd = () => {
  if (!isDragging.value) return
  const deltaX = currentX.value - startX.value
  if (Math.abs(deltaX) > dragThreshold) {
    if (deltaX > 0 && currentImageIndex.value > 0) {
      prevImage()
    } else if (deltaX < 0 && currentImageIndex.value < imageUrls.value.length - 1) {
      nextImage()
    }
  }
  isDragging.value = false
  dragOffset.value = 0
  
  setTimeout(() => {
    hasDragged.value = false
  }, 350)
}

const handleTouchStart = (e) => {
  handleDragStart(e)
  e.preventDefault()
}

const handleTouchMove = (e) => {
  handleDragMove(e)
  e.preventDefault()
}

const handleTouchEnd = (e) => {
  handleDragEnd()
  e.preventDefault()
}

const handleMouseMove = (e) => handleDragMove(e)
const handleMouseUp = () => {
  handleDragEnd()
  window.removeEventListener('mousemove', handleMouseMove)
  window.removeEventListener('mouseup', handleMouseUp)
}

const isFullscreen = ref(false)

const openFullscreen = (index) => {
  if (hasDragged.value || Math.abs(dragOffset.value) > 5) return
  currentImageIndex.value = index
  isFullscreen.value = true
}

const closeFullscreen = () => {
  isFullscreen.value = false
}

const handleKeydown = (e) => {
  if (!isFullscreen.value) return
  if (e.key === 'Escape') {
    isFullscreen.value = false
  } else if (e.key === 'ArrowRight') {
    nextImage()
  } else if (e.key === 'ArrowLeft') {
    prevImage()
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeydown)
})
</script>

<template>
  <div 
    class="relative w-full overflow-hidden bg-gray-300/60 p-4 sm:px-6 md:px-4 lg:px-12 rounded-lg dark:bg-neutral-900 cursor-grab active:cursor-grabbing select-none"
    @mousedown="handleDragStart"
    @mousemove="handleDragMove"
    @mouseup="handleDragEnd"
    @mouseleave="handleDragEnd"
    @pointerdown="handleDragStart"
    @pointermove="handleDragMove"
    @pointerup="handleDragEnd"
    @touchstart="handleTouchStart"
    @touchmove="handleTouchMove"
    @touchend="handleTouchEnd"
  >
    <div v-if="imageUrls.length > 0" class="relative hidden sm:flex h-80 md:h-128 justify-center">
      <div 
        class="flex h-full transition-transform duration-300 ease-out" 
        :class="{ 'ml-[50vw]': hasMultipleImages, 'duration-0': isDragging }"
        :style="{ transform: carouselTransform, 'will-change': 'transform', 'touch-action': 'pan-y' }"
      >
        <div 
          v-for="(url, index) in imageUrls" 
          :key="index"
          class="w-[50vw] shrink-0 px-2 md:px-[2vw] lg:px-[5vw]"
        >
          <div class="flex justify-center h-full bg-transparent dark:bg-neutral-900">
            <img
              :src="url"
              :alt="`${props.pet?.name || 'Pet'} - ${index + 1}`"
              class="max-w-full max-h-full object-contain transition-transform  duration-300 rounded-lg cursor-zoom-in"
              draggable="false"
              @click="openFullscreen(index)"
              @dragstart.prevent
            >
          </div>
        </div>
      </div>
    </div>

    <div v-if="imageUrls.length > 0" class="relative sm:hidden">
      <div class="relative w-full h-72 overflow-hidden">
        <div
          class="flex h-full transition-transform duration-300 ease-out"
          :class="{ 'duration-0': isDragging }"
          :style="{ transform: mobileTransform }"
        >
          <div v-for="(url, index) in imageUrls" :key="`m-${index}`" class="shrink-0 size-full flex items-center justify-center">
            <img
              :src="url"
              :alt="`${props.pet?.name || 'Pet'} - ${index + 1}`"
              class="size-full object-contain rounded-lg select-none"
              draggable="false"
              @click="openFullscreen(index)"
            >
          </div>
        </div>
      </div>
    </div>

    <button 
      v-if="imageUrls.length > 1"
      type="button" 
      :disabled="currentImageIndex === 0"
      class="absolute inset-y-0 start-0 inline-flex justify-center items-center w-11.5 h-full text-gray-800 hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-s-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed"
      @click="prevImage"
    >
      <span class="text-2xl" aria-hidden="true">
        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m15 18-6-6 6-6" />
        </svg>
      </span>
      <span class="sr-only">{{ t('pets.gallery.previous') }}</span>
    </button>
    <button 
      v-if="imageUrls.length > 1"
      type="button" 
      :disabled="currentImageIndex >= imageUrls.length - 1"
      class="absolute inset-y-0 end-0 inline-flex justify-center items-center w-11.5 h-full text-gray-800 hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-e-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 disabled:opacity-50 disabled:cursor-not-allowed"
      @click="nextImage"
    >
      <span class="sr-only">{{ t('pets.gallery.next') }}</span>
      <span class="text-2xl" aria-hidden="true">
        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6" />
        </svg>
      </span>
    </button>

    <vue-easy-lightbox
      :visible="isFullscreen"
      :imgs="imageUrls"
      :index="currentImageIndex"
      @hide="closeFullscreen"
    />
  </div>
</template>

<style scoped>
</style>
