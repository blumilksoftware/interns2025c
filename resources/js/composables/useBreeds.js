import { computed } from 'vue'
import { dogs, cats } from '@/data/petsData.js'

export function useBreeds(form) {
  const dogBreeds = Array.from(new Set(dogs.map(d => d.breed))).sort()
  const catBreeds = Array.from(new Set(cats.map(c => c.breed))).sort()
  const dogBreedsAvailable = computed(() => (Array.isArray(form.value.species) && form.value.species.includes('dog')) ? dogBreeds : [])
  const catBreedsAvailable = computed(() => (Array.isArray(form.value.species) && form.value.species.includes('cat')) ? catBreeds : [])
  const breedOptions = computed(() => {
    const selected = form.value.species
    if (!Array.isArray(selected) || selected.length === 0) return []
    let breeds = []
    if (selected.includes('dog')) breeds = breeds.concat(dogBreeds)
    if (selected.includes('cat')) breeds = breeds.concat(catBreeds)
    return Array.from(new Set(breeds)).sort()
  })
  return { dogBreedsAvailable, catBreedsAvailable, breedOptions }
}
