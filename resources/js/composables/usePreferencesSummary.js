import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { speciesOptions, sexOptions, colorOptions, healthOptions, adoptionOptions } from '@/helpers/preferencesConfig'

export function usePreferencesSummary(form, selectorConfigs, tagOptions) {
  const { t } = useI18n()

  const fieldConfigs = {
    species: { options: speciesOptions, labelKey: 'preferences.labels.species', isArray: true },
    breed: { options: null, labelKey: 'preferences.labels.breed', isArray: true, customMapper: (val) => ({ value: val, label: val }) },
    sex: { options: sexOptions, labelKey: 'preferences.labels.sex', isArray: false },
    color: { options: colorOptions, labelKey: 'preferences.labels.color', isArray: true },
    ageIndex: { options: selectorConfigs.age.options, labelKey: 'preferences.labels.age', isArray: true },
    sizeIndex: { options: selectorConfigs.size.options, labelKey: 'preferences.labels.size', isArray: true },
    weightState: { options: selectorConfigs.weight.options, labelKey: 'preferences.labels.weightKg', isArray: true },
    healthStatus: { options: healthOptions, labelKey: 'preferences.labels.healthStatus', isArray: true },
    adoptionStatus: { options: adoptionOptions, labelKey: 'preferences.labels.adoptionStatus', isArray: true },
    attitudeToDogs: { options: selectorConfigs.attitudes.options, labelKey: 'preferences.labels.attitudeToDogs', isArray: false },
    attitudeToCats: { options: selectorConfigs.attitudes.options, labelKey: 'preferences.labels.attitudeToCats', isArray: false },
    attitudeToChildren: { options: selectorConfigs.attitudes.options, labelKey: 'preferences.labels.attitudeToChildren', isArray: false },
    attitudeToAdults: { options: selectorConfigs.attitudes.options, labelKey: 'preferences.labels.attitudeToAdults', isArray: false },
    activityLevel: { options: selectorConfigs.activity.options, labelKey: 'preferences.labels.activityLevel', isArray: false },
  }

  const sectionToFilterMap = {
    species: 'species',
    breed: 'breed', 
    sex: 'sex',
    color: 'color',
    ageIndex: 'age',
    sizeIndex: 'size',
    weightState: 'weight',
    location: 'location',
    healthStatus: 'health',
    adoptionStatus: 'adoption',
    attitudeToDogs: 'attitude-dogs',
    attitudeToCats: 'attitude-cats',
    attitudeToChildren: 'attitude-children',
    attitudeToAdults: 'attitude-adults',
    activityLevel: 'activity',
    tags: 'tags',
    healthChecks: 'health-checks',
  }

  function mapByOptionsWithValues(values, options, tFn) {
    if (values === null || values === undefined || values === '') return []
    const arr = Array.isArray(values) ? values : [values]
    const labelMap = new Map(options.map(o => [o.value, o.labelKey ? tFn(o.labelKey) : (o.label || String(o.value))]))
    return arr
      .filter(val => val !== null && val !== undefined && String(val) !== '')
      .map(val => ({ value: val, label: labelMap.get(val) ?? String(val) }))
  }

  const summary = computed(() => {
    const formData = form.value || {}
    const sections = []

    Object.entries(fieldConfigs).forEach(([fieldKey, config]) => {
      const value = formData[fieldKey]
      if (!value || (Array.isArray(value) && value.length === 0)) return

      let items = []
      
      if (config.customMapper) {
        items = Array.isArray(value) ? value.map(config.customMapper) : [config.customMapper(value)]
      } else if (config.options) {
        items = mapByOptionsWithValues(value, config.options, (key) => t(key))
      }
      
      if (items.length > 0) {
        sections.push({ 
          key: fieldKey, 
          title: t(config.labelKey), 
          items, 
        })
      }
    })

    if (formData.location) {
      const radius = formData.radiusKm ? `±${formData.radiusKm} km` : ''
      sections.push({ 
        key: 'location', 
        title: t('preferences.labels.location'), 
        items: [{ value: 'location', label: radius ? `${formData.location} (${radius})` : formData.location }], 
      })
    }

    const checks = []
    const healthCheckFields = ['vaccinated', 'sterilized', 'microchipped', 'dewormed', 'defleaTreated']
    healthCheckFields.forEach(field => {
      if (formData[field]) {
        checks.push({ value: field, label: t(`preferences.checks.${field}`) })
      }
    })
    if (checks.length) {
      sections.push({ key: 'healthChecks', title: t('preferences.labels.healthChecks'), items: checks })
    }

    if (Array.isArray(formData.tags) && formData.tags.length) {
      const tagMap = new Map((Array.isArray(tagOptions) ? tagOptions : []).map(o => [o.value, o.label]))
      const tagItems = formData.tags.map(v => ({ value: v, label: tagMap.get(v) || String(v) }))
      sections.push({ key: 'tags', title: t('preferences.labels.tags'), items: tagItems })
    }

    return sections
  })

  function clearItem(sectionKey, value, moveFilterById) {
    const formData = form.value
    
    if (sectionKey === 'location') {
      formData.location = ''
    } else if (sectionKey === 'healthChecks') {
      if (value) formData[value] = false
    } else if (sectionKey === 'tags') {
      formData.tags = (formData.tags || []).filter(v => v !== value)
    } else {
      const config = fieldConfigs[sectionKey]
      if (config) {
        if (config.isArray) {
          formData[sectionKey] = (formData[sectionKey] || []).filter(v => v !== value)
        } else {
          formData[sectionKey] = formData[sectionKey] === value ? null : formData[sectionKey]
        }
      }
    }
    
    const filterKey = sectionToFilterMap[sectionKey]
    if (filterKey && moveFilterById) {
      moveFilterById(filterKey)
    }
  }

  return {
    summary,
    clearItem,
    fieldConfigs,
    sectionToFilterMap,
  }
}
