import { defineStore } from 'pinia'

const defaultForm = () => ({
  species: [],
  breed: [],
  sex: '',
  color: [],
  ageIndex: [],
  sizeIndex: [],
  weightState: [],
  location: '',
  radiusKm: null,
  tags: [],
})

export const usePreferencesStore = defineStore('preferences', {
  state: () => ({
    form: defaultForm(),
  }),
  getters: {
    selectedCount: (state) => {
      const f = state.form
      let n = 0
      if (Array.isArray(f.species) && f.species.length) n++
      if (Array.isArray(f.breed) && f.breed.length) n++
      if (f.sex) n++
      if (Array.isArray(f.color) && f.color.length) n++
      if (Array.isArray(f.ageIndex) && f.ageIndex.length) n++
      if (Array.isArray(f.sizeIndex) && f.sizeIndex.length) n++
      if (Array.isArray(f.weightState) && f.weightState.length) n++
      if (f.location) n++
      if (Array.isArray(f.tags) && f.tags.length) n++
      return n
    },
    isEmpty: (state) => state.form && !Object.values(state.form).some((v) => (Array.isArray(v) ? v.length : !!v)),
  },
  actions: {
    setForm(partial) {
      Object.assign(this.form, partial)
      this.save()
    },
    reset() {
      this.form = defaultForm()
      this.save()
    },
    load() {
      try {
        const raw = localStorage.getItem('preferencesForm')
        if (raw) this.form = Object.assign(defaultForm(), JSON.parse(raw))
      } catch {}
    },
    save() {
      try { localStorage.setItem('preferencesForm', JSON.stringify(this.form)) } catch {}
    },
    apply() {
      // Placeholder: hook for triggering search or analytics
    },
  },
})

