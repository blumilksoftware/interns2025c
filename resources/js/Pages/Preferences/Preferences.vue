<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'
import { getPetTags } from '@/helpers/mappers'
import { dogs, cats } from '@/data/petsData.js'
import PawPrints from '@/Components/PawPrints.vue'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import { selectorConfigs } from '@/helpers/selectors'
import { speciesOptions as speciesCfg, sexOptions as sexCfg, colorOptions as colorCfg, healthOptions as healthCfg, adoptionOptions as adoptionCfg } from '@/helpers/preferencesConfig'

const { t } = useI18n()

const speciesOptions = speciesCfg

const sexOptions = sexCfg

const allTags = getPetTags()
const tagOptions = computed(() =>
  Object.entries(allTags).map(([key, meta]) => ({ value: key, label: `${meta.emoji} ${meta.name}` }))
)

const speciesOpen = ref(false)
const speciesSummary = computed(() => {
  const selected = form.value.species
  if (!Array.isArray(selected) || selected.length === 0) return t('preferences.placeholders.any')
  const map = new Map(speciesOptions.map(o => [o.value, t(o.labelKey)]))
  return selected.map(v => map.get(v) || v).join(', ')
})

const breedOpen = ref(false)
const breedSummary = computed(() => {
  const selected = form.value.breed
  if (!Array.isArray(selected) || selected.length === 0) return t('preferences.placeholders.any')
  return selected.join(', ')
})

const sexOpen = ref(false)
const sexSummary = computed(() => {
  const v = form.value.sex
  if (!v) return t('preferences.placeholders.any')
  const opt = sexOptions.find(o => o.value === v)
  return opt ? t(opt.labelKey) : v
})

const locationOpen = ref(false)
const popularLocations = [
  'Warszawa', 'Kraków', 'Wrocław', 'Gdańsk', 'Poznań', 'Łódź',
  'Katowice', 'Lublin', 'Szczecin', 'Białystok','Legnica','Zielona Góra','Złotoryja',
]
const recentLocations = ref([])

const normalized = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
const filteredLocations = computed(() => {
  const q = (form.value.location || '').trim()
  if (!q) return []
  const base = Array.from(new Set([...popularLocations, ...recentLocations.value]))
  return base.filter(l => normalized(l).includes(normalized(q))).slice(0, 20)
})

function loadRecentLocations() {
  try {
    const raw = localStorage.getItem('recentLocations')
    if (raw) recentLocations.value = JSON.parse(raw)
  } catch {}
}

function saveRecentLocations() {
  try {
    localStorage.setItem('recentLocations', JSON.stringify(recentLocations.value.slice(0, 10)))
  } catch {}
}

function selectLocation(loc) {
  form.value.location = loc
  const idx = recentLocations.value.indexOf(loc)
  if (idx !== -1) recentLocations.value.splice(idx, 1)
  recentLocations.value.unshift(loc)
  saveRecentLocations()
  locationOpen.value = false
  moveFilterById('location')
}

function clearLocation() {
  form.value.location = ''
  locationOpen.value = false
  const topContainer = document.querySelector('.top-filters-container')
  if (topContainer) {
    const locationWrapper = topContainer.querySelector('[data-filter-id="location"]')
    if (locationWrapper) {
      locationWrapper.remove()
      if (topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) {
            topContainer.parentNode.removeChild(topContainer)
          }
        }, 300)
      }
    }
  }
}

function handleLocationChange() {
  if (form.value.location.trim()) {
    moveFilterById('location')
  } else {
    const topContainer = document.querySelector('.top-filters-container')
    if (topContainer) {
      const locationWrapper = topContainer.querySelector('[data-filter-id="location"]')
      if (locationWrapper) {
        locationWrapper.remove()
        if (topContainer.querySelectorAll('.filter-wrapper').length === 0) {
          topContainer.style.transform = 'translateY(-100%)'
          document.body.style.paddingTop = '0px'
          setTimeout(() => {
            if (topContainer.parentNode) {
              topContainer.parentNode.removeChild(topContainer)
            }
          }, 300)
        }
      }
    }
  }
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

const form = ref({
  species: [],
  breed: [],
  sex: '',
  ageIndex: [],
  sizeIndex: [],
  weightState: [],
  color: '',
  healthStatus: '',
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
  adoptionStatus: '',
  tags: [],
  location: '',
  radiusKm: null,
})

const showScrollToTop = ref(false)

function moveToTop(element) {
  if (!element) return
  
  const targetElement = element.closest('.filter-item') || element
  const filterId = targetElement.getAttribute('data-filter-id')
  
  if (!filterId) return
  
  let topContainer = document.querySelector('.top-filters-container')
  if (!topContainer) {
    topContainer = document.createElement('div')
    topContainer.className = 'top-filters-container'
    topContainer.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
      color: white;
      padding: 1.5rem;
      z-index: 9999;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(20px);
      transform: translateY(-100%);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    `
    document.body.appendChild(topContainer)
    
    const removeAllButton = document.createElement('button')
    removeAllButton.innerHTML = 'Usuń wszystkie'
    removeAllButton.className = 'remove-all-btn'
    removeAllButton.style.cssText = `
      position: absolute;
      top: 1rem;
      right: 1rem;
      padding: 0.5rem 1rem;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s ease;
      z-index: 10;
      backdrop-filter: blur(10px);
    `
    
    removeAllButton.addEventListener('click', () => {
      const allWrappers = topContainer.querySelectorAll('.filter-wrapper')
      allWrappers.forEach(wrapper => {
        const clonedElement = wrapper.querySelector('.moved-to-top')
        if (clonedElement) {
          const originalId = clonedElement.getAttribute('data-original-id')
          const originalElement = document.querySelector(`[data-filter-id="${originalId}"]`)
          if (originalElement) {
            originalElement.style.transform = 'scale(1)'
            originalElement.style.opacity = '1'
            originalElement.style.boxShadow = ''
          }
        }
        wrapper.remove()
      })
      
      topContainer.style.transform = 'translateY(-100%)'
      document.body.style.paddingTop = '0px'
      
      setTimeout(() => {
        if (topContainer.parentNode) {
          topContainer.parentNode.removeChild(topContainer)
        }
      }, 300)
    })
    
    topContainer.appendChild(removeAllButton)
    
    requestAnimationFrame(() => {
      topContainer.style.transform = 'translateY(0)'
    })
    
    document.body.style.transition = 'padding-top 0.3s ease-out'
    document.body.style.paddingTop = '80px'
  }
  
  const existingWrapper = topContainer.querySelector(`[data-filter-id="${filterId}"]`)
  
  if (filterId === 'sex') {
    const currentSex = form.value.sex
    if (!currentSex) {
      if (existingWrapper) {
        existingWrapper.remove()
        if (topContainer.querySelectorAll('.filter-wrapper').length === 0) {
          topContainer.style.transform = 'translateY(-100%)'
          document.body.style.paddingTop = '0px'
          setTimeout(() => {
            if (topContainer.parentNode) {
              topContainer.parentNode.removeChild(topContainer)
            }
          }, 300)
        }
      }
      return
    }
  }
  
  if (existingWrapper) {
    updateExistingElement(existingWrapper, targetElement)
    return
  }
  
  const clonedElement = targetElement.cloneNode(true)
  clonedElement.classList.add('moved-to-top')
  clonedElement.setAttribute('data-original-id', filterId)
  
  const editableElements = clonedElement.querySelectorAll('input, select, textarea, button:not(.remove-btn)')
  
  editableElements.forEach(el => {
    if (el.type === 'checkbox' || el.type === 'radio') {
      const wrapper = el.closest('.tile-checkbox') || el.closest('.checkbox-wrapper')
      if (wrapper) {
        const label = wrapper.querySelector('label, span, .label-container')
        if (label) {
          const textContent = label.textContent || label.innerText
          wrapper.innerHTML = `<span class="filter-label">${textContent}</span>`
        }
      }
    } else if (el.type === 'text' || el.type === 'search' || el.tagName === 'SELECT') {
      let value = ''
      if (el.tagName === 'SELECT') {
        if (el.value) {
          value = el.options[el.selectedIndex]?.textContent || 'Wybrano'
        } else {
          value = 'Dowolne'
        }
      } else {
        value = el.value || 'Wybrano'
      }
      el.parentNode.innerHTML = `<span class="filter-value">${value}</span>`
    }
  })
  
  clonedElement.style.pointerEvents = 'none'
  clonedElement.style.cssText = `
    background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
    border: 2px solid rgba(255,255,255,0.25);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    backdrop-filter: blur(15px);
    transition: all 0.3s ease;
    transform: scale(0.9);
    opacity: 0;
    min-width: 120px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
  `
  
  const glowEffect = document.createElement('div')
  glowEffect.style.cssText = `
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
    pointer-events: none;
  `
  clonedElement.appendChild(glowEffect)
  
  clonedElement.addEventListener('mouseenter', () => {
    clonedElement.style.transform = 'scale(1.05)'
    clonedElement.style.background = 'linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.12))'
    clonedElement.style.borderColor = 'rgba(255,255,255,0.4)'
    clonedElement.style.boxShadow = '0 6px 20px rgba(0,0,0,0.15)'
    glowEffect.style.transform = 'translateX(100%)'
  })
  
  clonedElement.addEventListener('mouseleave', () => {
    clonedElement.style.transform = 'scale(1)'
    clonedElement.style.background = 'linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08))'
    clonedElement.style.borderColor = 'rgba(255,255,255,0.25)'
    clonedElement.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)'
    glowEffect.style.transform = 'translateX(-100%)'
  })
  
  const removeButton = document.createElement('button')
  removeButton.innerHTML = '×'
  removeButton.className = 'remove-btn'
  removeButton.style.cssText = `
    position: absolute;
    top: -10px;
    right: -10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
    z-index: 10;
    backdrop-filter: blur(10px);
  `
  
  removeButton.addEventListener('mouseenter', () => {
    removeButton.style.transform = 'scale(1.1) rotate(90deg)'
    removeButton.style.background = 'linear-gradient(135deg, #dc2626, #b91c1c)'
    removeButton.style.borderColor = 'rgba(255,255,255,0.5)'
    removeButton.style.boxShadow = '0 4px 12px rgba(0,0,0,0.4)'
  })
  
  removeButton.addEventListener('mouseleave', () => {
    removeButton.style.transform = 'scale(1) rotate(0deg)'
    removeButton.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)'
    removeButton.style.borderColor = 'rgba(255,255,255,0.3)'
    removeButton.style.boxShadow = '0 3px 8px rgba(0,0,0,0.3)'
  })
  
  const wrapper = document.createElement('div')
  wrapper.style.position = 'relative'
  wrapper.className = 'filter-wrapper'
  wrapper.setAttribute('data-filter-id', filterId)
  wrapper.appendChild(clonedElement)
  wrapper.appendChild(removeButton)
  
  topContainer.appendChild(wrapper)
  
  updateExistingElement(wrapper, targetElement)
  
  requestAnimationFrame(() => {
    clonedElement.style.transform = 'scale(1)'
    clonedElement.style.opacity = '1'
    
    setTimeout(() => {
      clonedElement.style.transform = 'scale(1.1)'
      setTimeout(() => {
        clonedElement.style.transform = 'scale(1)'
      }, 150)
    }, 100)
  })
  
  removeButton.addEventListener('click', () => {
    clonedElement.style.transform = 'scale(0.9)'
    clonedElement.style.opacity = '0'
    
    setTimeout(() => {
      wrapper.remove()

      if (topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        
        setTimeout(() => {
          if (topContainer.parentNode) {
            topContainer.parentNode.removeChild(topContainer)
          }
        }, 300)
      }
    }, 200)
  })
  
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

function updateExistingElement(existingWrapper, targetElement) {
  const filterId = targetElement.getAttribute('data-filter-id')
  
  if (filterId === 'species') {
    const selected = form.value.species
    if (!Array.isArray(selected) || selected.length === 0) {
      existingWrapper.remove()
      
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        
        setTimeout(() => {
          if (topContainer.parentNode) {
            topContainer.parentNode.removeChild(topContainer)
          }
        }, 300)
      }
      return
    }
    
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const map = new Map(speciesOptions.map(o => [o.value, t(o.labelKey)]))
      const displayText = selected.map(v => map.get(v) || v).join(', ')
      clonedElement.innerHTML = `<span class=\"filter-label\">Gatunek: ${displayText}</span>`
    }
    
    const topContainer = document.querySelector('.top-filters-container')
    const breedWrapper = topContainer && topContainer.querySelector('[data-filter-id="breed"]')
    const breedOriginal = document.querySelector('[data-filter-id="breed"]')
    if (breedWrapper && breedOriginal) {
      updateExistingElement(breedWrapper, breedOriginal)
    }
  } else if (filterId === 'breed') {
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (!clonedElement) return
    
    const selectedSpecies = form.value.species
    const selectedBreeds = Array.isArray(form.value.breed) ? form.value.breed : []
    
    if (selectedBreeds.length === 0) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    
    const formatList = (arr) => arr.length ? arr.join(', ') : '-'
    if (Array.isArray(selectedSpecies) && selectedSpecies.includes('dog') && selectedSpecies.includes('cat')) {
      const dogSelected = selectedBreeds.filter(b => dogBreeds.includes(b))
      const catSelected = selectedBreeds.filter(b => catBreeds.includes(b))
      const dogText = `Pies: ${formatList(dogSelected)}`
      const catText = `Kot: ${formatList(catSelected)}`
      clonedElement.innerHTML = `<span class="filter-label">${dogText} | ${catText}</span>`
    } else {
      clonedElement.innerHTML = `<span class=\"filter-label\">Rasa: ${formatList(selectedBreeds)}</span>`
    }
  } else if (filterId === 'sex') {
    const selectedSex = form.value.sex
    if (!selectedSex) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const opt = sexOptions.find(o => o.value === selectedSex)
      const label = opt ? t(opt.labelKey) : selectedSex
      clonedElement.innerHTML = `<span class=\"filter-label\">Płeć: ${label}</span>`
    }
  } else if (filterId === 'age') {
    const selectedAges = Array.isArray(form.value.ageIndex) ? form.value.ageIndex : []
    if (selectedAges.length === 0) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const labels = selectedAges.map(v => {
        const opt = selectorConfigs.age.options.find(o => o.value === v)
        return opt ? (opt.labelKey ? t(opt.labelKey) : (opt.label || String(v))) : String(v)
      })
      const chips = labels.map(l => `<span style="display:inline-block;margin:2px 6px 2px 0;padding:2px 8px;border-radius:9999px;background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.4);font-weight:600;">${l}</span>`).join(' ')
      clonedElement.innerHTML = `<span class=\"filter-label\">Wiek: </span>${chips}`
    }
  } else if (filterId === 'location') {
    const selectedLocation = form.value.location
    if (!selectedLocation || !selectedLocation.trim()) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      clonedElement.innerHTML = `<span class=\"filter-label\">Lokalizacja: ${selectedLocation}</span>`
    }
  } else if (filterId === 'radius') {
    const selectedRadius = form.value.radiusKm
    if (selectedRadius === null || selectedRadius === undefined) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const radiusOption = radiusOptions.find(opt => opt.value === selectedRadius)
      const label = radiusOption ? radiusOption.label : `${selectedRadius} km`
      clonedElement.innerHTML = `<span class=\"filter-label\">Promień: ${label}</span>`
    }
  } else if (filterId === 'size') {
    const selectedSizes = Array.isArray(form.value.sizeIndex) ? form.value.sizeIndex : []
    if (selectedSizes.length === 0) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const labels = selectedSizes.map(v => {
        const opt = selectorConfigs.size.options.find(o => o.value === v)
        return opt ? (opt.labelKey ? t(opt.labelKey) : (opt.label || String(v))) : String(v)
      })
      const chips = labels.map(l => `<span style="display:inline-block;margin:2px 6px 2px 0;padding:2px 8px;border-radius:9999px;background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.4);font-weight:600;">${l}</span>`).join(' ')
      clonedElement.innerHTML = `<span class=\"filter-label\">Wielkość: </span>${chips}`
    }
  } else if (filterId === 'weight') {
    const selectedWeights = Array.isArray(form.value.weightState) ? form.value.weightState : []
    if (selectedWeights.length === 0) {
      existingWrapper.remove()
      const topContainer = document.querySelector('.top-filters-container')
      if (topContainer && topContainer.querySelectorAll('.filter-wrapper').length === 0) {
        topContainer.style.transform = 'translateY(-100%)'
        document.body.style.paddingTop = '0px'
        setTimeout(() => {
          if (topContainer.parentNode) topContainer.parentNode.removeChild(topContainer)
        }, 300)
      }
      return
    }
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
      const labels = selectedWeights.map(v => {
        const opt = selectorConfigs.weight.options.find(o => o.value === v)
        return opt ? (opt.labelKey ? t(opt.labelKey) : (opt.label || String(v))) : String(v)
      })
      const chips = labels.map(l => `<span style="display:inline-block;margin:2px 6px 2px 0;padding:2px 8px;border-radius:9999px;background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.4);font-weight:600;">${l}</span>`).join(' ')
      clonedElement.innerHTML = `<span class=\"filter-label\">Waga: </span>${chips}`
    }
  } else {
    const clonedElement = existingWrapper.querySelector('.moved-to-top')
    if (clonedElement) {
    }
  }
}

function handleScroll() {
  showScrollToTop.value = window.scrollY > 300
}

function moveFilterById(id) {
  const element = document.querySelector(`[data-filter-id="${id}"]`)
  if (element) {
    moveToTop(element)
  }
}

function toggleTag(tag, event) {
  const idx = form.value.tags.indexOf(tag)
  if (idx === -1) form.value.tags.push(tag)
  else form.value.tags.splice(idx, 1)
  moveFilterById('tags')
}

function resetForm() {
  form.value = {
    species: [],
    breed: [],
    sex: '',
    ageIndex: [],
    sizeIndex: [],
    weightState: [],
    color: '',
    healthStatus: '',
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
    adoptionStatus: '',
    tags: [],
    location: '',
    radiusKm: null,
  }
}

function applyPreferences() {
  console.log('Applied preferences:', { ...form.value })
}

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

watch(() => form.value.species, async () => { 
  form.value.breed = []
  await nextTick()
  moveFilterById('species')
}, { deep: true })

const colorOptions = colorCfg

const healthOptions = healthCfg

const adoptionOptions = adoptionCfg

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  
  loadRecentLocations()
  
  document.addEventListener('click', (event) => {
    const speciesContainer = document.querySelector('[data-filter-id="species"]')
    if (speciesContainer && !speciesContainer.contains(event.target)) {
      speciesOpen.value = false
    }
    const sexContainer = document.querySelector('[data-filter-id="sex"]')
    if (sexContainer && !sexContainer.contains(event.target)) {
      sexOpen.value = false
    }
    const breedContainer = document.querySelector('[data-filter-id="breed"]')
    if (breedContainer && !breedContainer.contains(event.target)) {
      breedOpen.value = false
    }
    const locationContainer = document.querySelector('[data-filter-id="location"]')
    if (locationContainer && !locationContainer.contains(event.target)) {
      locationOpen.value = false
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

</script>

<template>
    <Head :title="t('titles.preferences')" />
  <div class="min-h-screen bg-gradient-to-br from-orange-200/40 via-pink-100/40 to-blue-200/40 dark:from-purple-900 dark:via-blue-900 dark:to-indigo-900 relative">
    <PawPrints mode="scatter" />
    
    <button 
      v-show="showScrollToTop"
      @click="() => window.scrollTo({ top: 0, behavior: 'smooth' })"
      class="fixed bottom-6 right-6 z-50 p-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      title="Powrót do góry"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
      </svg>
    </button>
    
    <div class="mx-auto max-w-5xl px-6 lg:px-8 py-10">
    <div class="mb-6">
      <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ t('preferences.title') }}</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-300">{{ t('preferences.subtitle') }}</p>
    </div>

    <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-xl p-6 space-y-6 shadow-lg preferences-form-container">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="filter-item" data-filter-id="species" :style="{ zIndex: speciesOpen ? 1000 : 'auto' }">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.species') }}</label>
          <div class="relative z-30">
            <button type="button" @click="speciesOpen = !speciesOpen" class="w-full text-left text-black dark:text-gray-100 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <span>{{ speciesSummary }}</span>
              <div class="flex items-center gap-2">
                <span v-if="form.species.length > 0" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">{{ form.species.length }}</span>
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                </svg>
              </div>
            </button>
            <div v-if="speciesOpen" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2">
              <div class="max-h-60 overflow-auto">
                <label v-for="opt in speciesOptions" :key="opt.value" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                  <input type="checkbox" :value="opt.value" v-model="form.species" @change="moveFilterById('species')" class="rounded border-gray-300 dark:border-gray-700" />
                  <span>{{ t(opt.labelKey) }}</span>
                </label>
              </div>
              <div class="mt-2 flex justify-end gap-2">
                <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.species = []; moveFilterById('species')">{{ t('preferences.placeholders.any') }}</button>
                <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="speciesOpen = false">OK</button>
              </div>
            </div>
          </div>
        </div>

        <div class="filter-item" data-filter-id="breed" :style="{ zIndex: breedOpen ? 1000 : 'auto' }">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.breed') }}</label>
          <div class="relative">
            <button type="button" @click="breedOpen = !breedOpen" :disabled="!Array.isArray(form.species) || form.species.length === 0 || breedOptions.length === 0" class="w-full text-left text-black dark:text-gray-100 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
              <span>{{ breedSummary }}</span>
              <div class="flex items-center gap-2">
                <span v-if="Array.isArray(form.breed) && form.breed.length > 0" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">{{ form.breed.length }}</span>
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                </svg>
              </div>
            </button>
            <div v-if="breedOpen" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2">
              <div class="max-h-64 overflow-auto space-y-2">
                <div v-if="dogBreedsAvailable.length > 0">
                  <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Psy</div>
                  <label v-for="b in dogBreedsAvailable" :key="'dog-'+b" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                    <input type="checkbox" :value="b" v-model="form.breed" @change="moveFilterById('breed')" class="rounded border-gray-300 dark:border-gray-700" />
                    <span>{{ b }}</span>
                  </label>
                </div>
                <div v-if="catBreedsAvailable.length > 0">
                  <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Koty</div>
                  <label v-for="b in catBreedsAvailable" :key="'cat-'+b" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                    <input type="checkbox" :value="b" v-model="form.breed" @change="moveFilterById('breed')" class="rounded border-gray-300 dark:border-gray-700" />
                    <span>{{ b }}</span>
                  </label>
                </div>
              </div>
              <div class="mt-2 flex justify-end gap-2">
                <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.breed = []; moveFilterById('breed')">{{ t('preferences.placeholders.any') }}</button>
                <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="breedOpen = false">OK</button>
              </div>
            </div>
          </div>
        </div>

        <div class="filter-item" data-filter-id="sex" :style="{ zIndex: sexOpen ? 1000 : 'auto' }">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.sex') }}</label>
          <div class="relative z-30">
            <button type="button" @click="sexOpen = !sexOpen" class="w-full text-left text-black dark:text-gray-100 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <span>{{ sexSummary }}</span>
              <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
              </svg>
            </button>
            <div v-if="sexOpen" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2">
              <div class="max-h-60 overflow-auto">
                <label v-for="opt in sexOptions" :key="opt.value" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                  <input type="radio" name="sex" :value="opt.value" v-model="form.sex" @change="moveFilterById('sex')" class="border-gray-300 dark:border-gray-700" />
                  <span>{{ t(opt.labelKey) }}</span>
                </label>
              </div>
              <div class="mt-2 flex justify-end gap-2">
                <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.sex = ''; moveFilterById('sex')">{{ t('preferences.placeholders.any') }}</button>
                <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="sexOpen = false">OK</button>
              </div>
            </div>
          </div>
        </div>

        <div class="filter-item" data-filter-id="age">
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.age') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.ageIndex = []; moveFilterById('age')">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.age.columns" :options="selectorConfigs.age.options" :model-value="form.ageIndex" :multiple="true" @update:modelValue="val => { form.ageIndex = val; moveFilterById('age') }">
            <template #label="{ option }">
              <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
            </template>
          </ChoiceTiles>
        </div>
        
        <div class="filter-item" data-filter-id="location" :style="{ zIndex: locationOpen ? 1000 : 'auto' }">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.location') }}</label>
          <div class="relative z-30">
            <input v-model="form.location" @focus="locationOpen = true" @input="locationOpen = true; handleLocationChange()" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" :placeholder="t('preferences.placeholders.cityOrZip')">
            <div v-if="locationOpen" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2">
              <div class="max-h-64 overflow-auto">
                <template v-if="form.location && filteredLocations.length > 0">
                  <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Wyniki</div>
                  <button type="button" v-for="loc in filteredLocations" :key="'f-'+loc" @click="selectLocation(loc)" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                    {{ loc }}
                  </button>
                </template>
                <template v-else-if="form.location">
                  <div class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">Brak wyników. Użyj wpisanej lokalizacji.</div>
                </template>
                <div v-if="recentLocations.length > 0" class="mt-2">
                  <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ostatnio wybierane</div>
                  <button type="button" v-for="loc in recentLocations" :key="'r-'+loc" @click="selectLocation(loc)" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                    {{ loc }}
                  </button>
                </div>
                <div class="mt-2">
                  <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Popularne lokalizacje</div>
                  <button type="button" v-for="loc in popularLocations" :key="'p-'+loc" @click="selectLocation(loc)" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                    {{ loc }}
                  </button>
                </div>
              </div>
              <div class="mt-2 flex justify-between gap-2">
                <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="clearLocation()">Wyczyść</button>
                <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="selectLocation(form.location)">Użyj tego</button>
              </div>
            </div>
          </div>
        </div>

        <div class="filter-item" data-filter-id="radius">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.radiusKm') }}</label>
          <select v-model="form.radiusKm" @change="moveFilterById('radius')" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option :value="null">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in radiusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>

                <div class="filter-item" data-filter-id="size">
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.size') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.sizeIndex = []; moveFilterById('size')">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.size.columns" :options="selectorConfigs.size.options" :model-value="form.sizeIndex" :multiple="true" @update:modelValue="val => { form.sizeIndex = val; moveFilterById('size') }">
            <template #label="{ option }">
              <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
            </template>
          </ChoiceTiles>
        </div>
        
        <div class="filter-item" data-filter-id="weight">
          <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.weightKg') }}</label>
            <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.weightState = []; moveFilterById('weight')">{{ t('preferences.placeholders.any') }}</button>
          </div>
          <ChoiceTiles :columns="selectorConfigs.weight.columns" :options="selectorConfigs.weight.options" :model-value="form.weightState" :multiple="true" @update:modelValue="val => { form.weightState = val; moveFilterById('weight') }" />
        </div>

        <div class="filter-item" data-filter-id="color">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.color') }}</label>
          <select v-model="form.color" @change="moveFilterById('color')" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in colorOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>

        <div class="filter-item" data-filter-id="health">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.healthStatus') }}</label>
          <select v-model="form.healthStatus" @change="moveFilterById('health')" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in healthOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>

        <div class="filter-item" data-filter-id="adoption">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ t('preferences.labels.adoptionStatus') }}</label>
          <select v-model="form.adoptionStatus" @change="moveFilterById('adoption')" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">{{ t('preferences.placeholders.any') }}</option>
            <option v-for="opt in adoptionOptions" :key="opt.value" :value="opt.value">{{ t(opt.labelKey) }}</option>
          </select>
        </div>


      </div>

      <details class="disclosure rounded-lg border border-gray-200 dark:border-gray-700 p-0 filter-item" data-filter-id="health-checks">
        <summary class="disclosure-summary cursor-pointer">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.75H6.5a.75.75 0 000 1.5h2.75v2.75a.75.75 0 001.5 0V11h2.75a.75.75 0 000-1.5H10.75V6.75z" clip-rule="evenodd" />
            </svg>
            <span class="disclosure-title">{{ t('preferences.labels.healthChecks') }}</span>
          </div>
          <div class="flex items-center gap-3">
            <span class="disclosure-hint hidden sm:inline text-xs text-gray-500 dark:text-gray-400">kliknij, aby rozwinąć</span>
            <span v-if="(form.vaccinated?1:0)+(form.sterilized?1:0)+(form.microchipped?1:0)+(form.dewormed?1:0)+(form.defleaTreated?1:0) > 0" class="badge-selected">{{ (form.vaccinated?1:0)+(form.sterilized?1:0)+(form.microchipped?1:0)+(form.dewormed?1:0)+(form.defleaTreated?1:0) }}</span>
            <svg class="chevron w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
          </div>
        </summary>
        <div class="px-4 pb-4 pt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.vaccinated" @change="moveFilterById('health-checks')" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.vaccinated') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.sterilized" @change="moveFilterById('health-checks')" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.sterilized') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.microchipped" @change="moveFilterById('health-checks')" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.microchipped') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.dewormed" @change="moveFilterById('health-checks')" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.dewormed') }}
          </label>
          <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
            <input type="checkbox" v-model="form.defleaTreated" @change="moveFilterById('health-checks')" class="rounded border-gray-300 dark:border-gray-700"> {{ t('preferences.checks.defleaTreated') }}
          </label>
        </div>
      </details>

      <div class="bg-white/70 dark:bg-gray-800/20 backdrop-blur-md border border-gray-200/60 dark:border-gray-700/60 rounded-xl p-4 filter-item" data-filter-id="attitudes">
        <div class="flex items-center justify-between mb-4">
          <label class="block text-base font-medium text-gray-800 dark:text-gray-100">Stosunki</label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:auto-rows-fr gap-6">
          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-dogs">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToDogs') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToDogs = null; moveFilterById('attitude-dogs')">{{ t('preferences.placeholders.any') }}</button>
            </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToDogs" @update:modelValue="val => { form.attitudeToDogs = val; moveFilterById('attitude-dogs') }" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-cats">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToCats') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToCats = null; moveFilterById('attitude-cats')">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToCats" @update:modelValue="val => { form.attitudeToCats = val; moveFilterById('attitude-cats') }" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-children">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToChildren') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToChildren = null; moveFilterById('attitude-children')">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToChildren" @update:modelValue="val => { form.attitudeToChildren = val; moveFilterById('attitude-children') }" />
          </div>

          <div class="h-full border border-gray-200/70 dark:border-gray-700/60 rounded-lg p-3 bg-white/60 dark:bg-gray-800/20 filter-item" data-filter-id="attitude-adults">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ t('preferences.labels.attitudeToAdults') }}</span>
              <button type="button" class="text-[11px] px-2 py-0.5 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.attitudeToAdults = null; moveFilterById('attitude-adults')">{{ t('preferences.placeholders.any') }}</button>
        </div>
            <ChoiceTiles :columns="selectorConfigs.attitudes.columns" :options="selectorConfigs.attitudes.options" :model-value="form.attitudeToAdults" @update:modelValue="val => { form.attitudeToAdults = val; moveFilterById('attitude-adults') }" />
          </div>
        </div>
      </div>

      <div class="filter-item" data-filter-id="activity">
        <div class="flex items-center justify-between mb-3">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.activityLevel') }}</label>
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.activityLevel = null; moveFilterById('activity')">{{ t('preferences.placeholders.any') }}</button>
        </div>
        <ChoiceTiles :columns="selectorConfigs.activity.columns" :options="selectorConfigs.activity.options" :model-value="form.activityLevel" @update:modelValue="val => { form.activityLevel = val; moveFilterById('activity') }" />
      </div>

      <div class="filter-item" data-filter-id="tags">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ t('preferences.labels.tags') }}</label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="tag in tagOptions"
            :key="tag.value"
            type="button"
            @click="toggleTag(tag.value, $event)"
            class="px-3 py-1 rounded-full border text-sm transition-colors"
            :class="form.tags.includes(tag.value) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700'"
          >
            {{ tag.label }}
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" @click="applyPreferences" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          {{ t('preferences.actions.apply') }}
        </button>
        <button type="button" @click="resetForm" class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-4 py-2 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
          {{ t('preferences.actions.reset') }}
        </button>
      </div>
    </div>
    </div>
  </div>
</template>

<style scoped>
.filter-item {
  transition: all 0.2s ease;
}

.filter-item:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

button {
  transition: all 0.15s ease;
}

button:hover {
  transform: translateY(-1px);
}

input, select {
  transition: all 0.15s ease;
}

input:focus, select:focus {
  transform: scale(1.01);
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}

input[type="checkbox"] {
  transition: all 0.15s ease;
}

input[type="checkbox"]:checked {
  transform: scale(1.05);
}

button[type="button"] {
  transition: all 0.15s ease;
}

button[type="button"]:hover {
  transform: translateY(-1px);
}

.tile-checkbox {
  transition: all 0.2s ease;
}

.tile-checkbox:hover {
  transform: translateY(-2px);
}

.disclosure {
  transition: all 0.2s ease;
}

.filter-label {
  color: white;
  font-weight: 600;
  font-size: 0.875rem;
  text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.filter-value {
  color: white;
  font-weight: 500;
  font-size: 0.875rem;
  text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

:deep(.top-filters-container) {
  font-family: inherit;
}

:deep(.top-filters-container .moved-to-top) {
  font-family: inherit;
}

:deep(.top-filters-container .moved-to-top *) {
  color: white !important;
  text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

body {
  transition: padding-top 0.3s ease-out;
}
</style>
