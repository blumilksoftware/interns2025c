import { ref } from 'vue'

export function useFormState() {
  const form = ref({
    species: [],
    breed: [],
    sex: '',
    ageIndex: [],
    sizeIndex: [],
    weightState: [],
    color: [],
    healthStatus: [],
    vaccinated: false,
    sterilized: false,
    microchipped: false,
    dewormed: false,
    defleaTreated: false,
    attitudeToDogs: null,
    attitudeToCats: null,
    attitudeToChildren: null,
    attitudeToAdults: null,
    activityLevel: null,
    adoptionStatus: [],
    tags: [],
    location: '',
    radiusKm: null,
  })

  function resetForm() {
    form.value = {
      species: [],
      breed: [],
      sex: '',
      ageIndex: [],
      sizeIndex: [],
      weightState: [],
      color: [],
      healthStatus: [],
      vaccinated: false,
      sterilized: false,
      microchipped: false,
      dewormed: false,
      defleaTreated: false,
      attitudeToDogs: null,
      attitudeToCats: null,
      attitudeToChildren: null,
      attitudeToAdults: null,
      activityLevel: null,
      adoptionStatus: [],
      tags: [],
      location: '',
      radiusKm: null,
    }
  }

  function applyPreferences() {
    // placeholder
    // console.log('Applied preferences:', { ...form.value })
  }

  return { form, resetForm, applyPreferences }
}


