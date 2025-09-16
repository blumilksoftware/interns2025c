import { autoFieldTypes } from './types.js'
import { petsConfig } from './pets.js'
import { incomingPetsRequestsConfig } from './incomingPetsRequests.js'
import { usersConfig } from './users.js'
import { sheltersConfig } from './shelters.js'
import { logsConfig } from './logs.js'

export const columnConfig = {
  pets: petsConfig,
  incomingPetsRequests: incomingPetsRequestsConfig,
  users: usersConfig,
  shelters: sheltersConfig,
  logs: logsConfig,
}

export function getColumnConfig(dataSetType, fieldName) {
  return columnConfig[dataSetType]?.[fieldName] || null
}

export function getColumnType(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  if (config) return config.type
  
  for (const [type, fields] of Object.entries(autoFieldTypes)) {
    if (fields.includes(fieldName) || fields.some(f => fieldName.includes(f))) {
      if (type === 'datetime') return 'datetime-local'
      return type
    }
  }
  return 'text'
}

export function getColumnOptions(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  return config?.options || []
}

export function getColumnAttributes(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  if (config) {
    return {
      min: config.min,
      max: config.max,
      step: config.step,
    }
  }
  
  if (getColumnType(dataSetType, fieldName) === 'number') {
    return {
      min: 0,
      step: 1,
    }
  }
  
  return {}
}

export function getColumnLabel(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  if (config?.label) return config.label
  
  return fieldName.charAt(0).toUpperCase() + fieldName.slice(1).replace(/_/g, ' ')
}

export function isColumnEditable(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  return config?.editable !== false
}

export function getColumnRenderer(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  return config?.renderer || 'text'
}

export function isColumnRequired(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  return config?.required === true
}
