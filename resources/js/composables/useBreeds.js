import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useBreeds(form) {
  const page = usePage()
  const breeds = computed(() => page?.props?.breeds || { dog: [], cat: [], other: [] })

  const dogBreedsAvailable = computed(() => (Array.isArray(form.value.species) && form.value.species.includes('dog')) ? (breeds.value.dog || []) : [])
  const catBreedsAvailable = computed(() => (Array.isArray(form.value.species) && form.value.species.includes('cat')) ? (breeds.value.cat || []) : [])

  const breedOptions = computed(() => {
    const selected = form.value.species
    if (!Array.isArray(selected) || selected.length === 0) return []
    let list = []
    if (selected.includes('dog')) list = list.concat(breeds.value.dog || [])
    if (selected.includes('cat')) list = list.concat(breeds.value.cat || [])
    if (selected.includes('other')) list = list.concat(breeds.value.other || [])
    return Array.from(new Set(list)).sort()
  })

  return { dogBreedsAvailable, catBreedsAvailable, breedOptions }
}
