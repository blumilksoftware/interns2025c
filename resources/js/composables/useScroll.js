import { ref, onMounted, onUnmounted } from 'vue'

export function useScroll() {
  const showScrollToTop = ref(false)
  function handleScroll() { showScrollToTop.value = window.scrollY > 300 }
  onMounted(() => window.addEventListener('scroll', handleScroll))
  onUnmounted(() => window.removeEventListener('scroll', handleScroll))
  return { showScrollToTop }
}


