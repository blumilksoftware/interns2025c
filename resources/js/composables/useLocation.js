import { ref, computed } from 'vue'

export function useLocation(form) {
  const locationOpen = ref(false)
  const recentLocations = ref([])

  function loadRecentLocations() {
    if (typeof localStorage !== 'undefined') {
      try {
        const raw = localStorage.getItem('recentLocations')
        if (raw) recentLocations.value = JSON.parse(raw)
      } catch (error) {
        return[]
      }
    }
  }
  function saveRecentLocations() {
    if (typeof localStorage !== 'undefined') {
      try {
        localStorage.setItem('recentLocations', JSON.stringify(recentLocations.value.slice(0, 10)))
      } catch (error) {
        return[]
      }
    }
  }

  function normalized(searchString) { return searchString.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') }
  const filteredLocations = computed(() => {
    const query = (form.value.location || '').trim()
    if (!query) return []
    const base = Array.from(new Set([...recentLocations.value]))
    return base.filter(location => normalized(location).includes(normalized(query))).slice(0, 20)
  })

  function selectLocation(location) {
    form.value.location = location
    if (!form.value.radiusKm || form.value.radiusKm <= 0) {
      form.value.radiusKm = 5
    }
    const locationIndex = recentLocations.value.indexOf(location)
    if (locationIndex !== -1) recentLocations.value.splice(locationIndex, 1)
    recentLocations.value.unshift(location)
    saveRecentLocations()
    locationOpen.value = false
  }

  function clearLocation() {
    form.value.location = ''
    locationOpen.value = false
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
    { value: 200, label: '200 km' },
  ]

  return { locationOpen, recentLocations, filteredLocations, selectLocation, clearLocation  , loadRecentLocations, radiusOptions }
}
