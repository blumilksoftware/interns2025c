import { parsePolishAgeToMonths, classifyDogAgeCategory } from '@/helpers/formatters/age'

export const weightConfig = { species: 3, breed: 3, sex: 2, color: 1, tags: 2, age: 2, size: 2, weight: 2, health: 2, activity: 2 }

export const ageIndexToCategory = (idx: number | string | null | undefined): string | null => {
  const n = typeof idx === 'string' ? Number(idx) : idx
  if (n === 0) return 'young'
  if (n === 1) return 'adult'
  if (n === 2) return 'senior'
  return null
}

export const petAgeCategory = (pet: any): string | null => {
  const months = parsePolishAgeToMonths(pet?.age)
  if (months === null || months <= 0) return null
  const cat = classifyDogAgeCategory(months)
  return cat === 'unknown' ? null : cat
}

export const sizeIndexToValue = (idx: number | string | null | undefined): string | null => {
  const n = typeof idx === 'string' ? Number(idx) : idx
  if (n === 0) return 'small'
  if (n === 1) return 'medium'
  if (n === 2) return 'large'
  return null
}

export const scorePet = (pet: any, filters: any): number => {
  const f = filters || {}
  let score = 0
  if (Array.isArray(f.species) && f.species.includes(String(pet.species))) score += weightConfig.species
  if (Array.isArray(f.breed) && f.breed.includes(pet.breed)) score += weightConfig.breed
  if (f.sex && String(f.sex) === String(pet.sex)) score += weightConfig.sex
  if (Array.isArray(f.color) && f.color.includes(pet.color)) score += weightConfig.color
  
  if (Array.isArray(f.healthStatus) && f.healthStatus.length) {
    const preferredHealth = f.healthStatus.map((v: string) => String(v).toLowerCase())
    const petHealth = String(pet.health_status || pet.healthStatus || '').toLowerCase()
    if (petHealth && preferredHealth.includes(petHealth)) score += weightConfig.health
  }

  if (Array.isArray(f.adoptionStatus) && f.adoptionStatus.length) {
    const preferredAdoption = f.adoptionStatus.map((v: string) => String(v).toLowerCase())
    const petAdoption = String(pet.adoption_status || pet.adoptionStatus || '').toLowerCase()
    if (petAdoption && preferredAdoption.includes(petAdoption)) score += 1
  }

  if (Array.isArray(f.sizeIndex) && f.sizeIndex.length) {
    const preferredSizes = f.sizeIndex.map(sizeIndexToValue).filter(Boolean)
    const petSize = String(pet.size || '').toLowerCase()
    if (petSize && preferredSizes.includes(petSize)) score += weightConfig.size
  }

  if (Array.isArray(f.weightState) && f.weightState.length) {
    const petWeight = Number(pet.weight)
    if (Number.isFinite(petWeight)) {
      const species = String(pet.species || '').toLowerCase()
      const indexToRange = (idx: number | string): [number, number] | null => {
        const n = typeof idx === 'string' ? Number(idx) : idx
        if (species === 'dog') {
          if (n === 0) return [0, 10]
          if (n === 1) return [10, 25]
          if (n === 2) return [25, Number.POSITIVE_INFINITY]
        } else if (species === 'cat') {
          if (n === 0) return [0, 4]
          if (n === 1) return [4, 6.5]
          if (n === 2) return [6.5, Number.POSITIVE_INFINITY]
        } else {
          if (n === 0) return [0, 7]
          if (n === 1) return [7, 20]
          if (n === 2) return [20, Number.POSITIVE_INFINITY]
        }
        return null
      }

      const matches = f.weightState.some((idx: number | string) => {
        const range = indexToRange(idx)
        return range ? (petWeight >= range[0] && petWeight < range[1]) : false
      })
      if (matches) score += weightConfig.weight
    }
  }

  if (Array.isArray(f.ageIndex) && f.ageIndex.length) {
    const preferredCats = f.ageIndex.map(ageIndexToCategory).filter(Boolean)
    const petCat = petAgeCategory(pet)
    if (petCat && preferredCats.includes(petCat)) score += weightConfig.age
  }

  if (Array.isArray(f.tags) && f.tags.length) {
    const petTags = Array.isArray(pet.tags) ? pet.tags : []
    const overlap = f.tags.filter((t: string) => petTags.includes(t))
    if (overlap.length) score += weightConfig.tags * Math.min(1, overlap.length / 3)
  }

  if (f.activityLevel !== null && f.activityLevel !== undefined && f.activityLevel !== '') {
    const idx = typeof f.activityLevel === 'string' ? Number(f.activityLevel) : f.activityLevel
    const idxToLevel = (val: number): string | null => {
      if (val === 0) return 'very low'
      if (val === 1) return 'low'
      if (val === 2) return 'medium'
      if (val === 3) return 'high'
      if (val === 4) return 'very high'
      return null
    }
    const desired = Number.isFinite(idx) ? idxToLevel(idx as number) : null
    const petActivity = String(pet.activity_level || pet.activityLevel || '').toLowerCase()
    if (desired && petActivity === desired) {
      score += weightConfig.activity
    }
  }

  const checks: Array<{ key: string; petKey: string }> = [
    { key: 'vaccinated', petKey: 'vaccinated' },
    { key: 'sterilized', petKey: 'sterilized' },
    { key: 'microchipped', petKey: 'has_chip' },
    { key: 'dewormed', petKey: 'dewormed' },
    { key: 'defleaTreated', petKey: 'deflea_treated' },
  ]
  checks.forEach(({ key, petKey }) => {
    if (f?.[key]) {
      const petValRaw = (pet as any)?.[petKey]
      const petVal = typeof petValRaw === 'string' ? petValRaw.toLowerCase() : petValRaw
      if (petVal === true || petVal === 1 || petVal === '1' || petVal === 'true') {
        score += 1
      }
    }
  })

  const attitudeMap = new Map<string, string>([
    ['very low', 'very low'],
    ['low', 'low'],
    ['medium', 'medium'],
    ['high', 'high'],
    ['very high', 'very high'],
  ])
  const attitudeKeys = [
    { prefKey: 'attitudeToDogs', petKey: 'attitude_to_dogs' },
    { prefKey: 'attitudeToCats', petKey: 'attitude_to_cats' },
    { prefKey: 'attitudeToChildren', petKey: 'attitude_to_children' },
    { prefKey: 'attitudeToAdults', petKey: 'attitude_to_people' },
  ]
  attitudeKeys.forEach(({ prefKey, petKey }) => {
    const pref = f?.[prefKey]
    if (!pref && pref !== 0) return
    const idxToLevel = (val: number | string): string | null => {
      const n = typeof val === 'string' ? Number(val) : val
      if (n === 0) return 'very low'
      if (n === 1) return 'low'
      if (n === 2) return 'medium'
      if (n === 3) return 'high'
      if (n === 4) return 'very high'
      return null
    }
    const desired = typeof pref === 'number' || typeof pref === 'string' ? idxToLevel(pref) : null
    const petVal = String((pet as any)?.[petKey] || '').toLowerCase()
    if (desired && attitudeMap.has(desired) && petVal === desired) {
      score += 1
    }
  })

  return score
}
