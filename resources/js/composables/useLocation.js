import { ref, computed } from 'vue'

export function useLocation(form) {
  const locationOpen = ref(false)
  const popularLocations = [
    'Cała Polska',
    'Warszawa', 'Kraków', 'Wrocław', 'Gdańsk', 'Poznań', 'Łódź',
    'Katowice', 'Lublin', 'Szczecin', 'Białystok','Legnica','Zielona Góra','Złotoryja',
  ]
  const recentLocations = ref([])

  function loadRecentLocations() {
    if (typeof localStorage !== 'undefined') {
      try {
        const raw = localStorage.getItem('recentLocations')
        if (raw) recentLocations.value = JSON.parse(raw)
      } catch (error) {
        console.warn('Failed to load recent locations from localStorage:', error)
      }
    }
  }
  function saveRecentLocations() {
    if (typeof localStorage !== 'undefined') {
      try {
        localStorage.setItem('recentLocations', JSON.stringify(recentLocations.value.slice(0, 10)))
      } catch (error) {
        console.warn('Failed to save recent locations to localStorage:', error)
      }
    }
  }

  function normalized(s) { return s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') }
  const filteredLocations = computed(() => {
    const q = (form.value.location || '').trim()
    if (!q) return []
    const base = Array.from(new Set([...popularLocations, ...recentLocations.value]))
    return base.filter(l => normalized(l).includes(normalized(q))).slice(0, 20)
  })

  function selectLocation(loc) {
    form.value.location = loc
    const idx = recentLocations.value.indexOf(loc)
    if (idx !== -1) recentLocations.value.splice(idx, 1)
    recentLocations.value.unshift(loc)
    saveRecentLocations()
    locationOpen.value = false
  }

  function clearLocation() {
    form.value.location = ''
    locationOpen.value = false
  }

  function handleLocationChange() {
    // no-op here; a placeholder for external UI update
  }

  const radiusOptions = [
    { value: 5, label: '5 km' },
    { value: 10, label: '10 km' },
    { value: 15, label: '15 km' },
    { value: 20, label: '20 km' },
    { value: 25, label: '25 km' },
    { value: 30, label: '30 km' },
    { value: 35, label: '35 km' },
    { value: 40, label: '40 km' },
    { value: 45, label: '45 km' },
    { value: 50, label: '50 km' },
    { value: 75, label: '75 km' },
    { value: 100, label: '100 km' },
  ]

  return { locationOpen, popularLocations, recentLocations, filteredLocations, selectLocation, clearLocation, handleLocationChange, loadRecentLocations, radiusOptions }
}


