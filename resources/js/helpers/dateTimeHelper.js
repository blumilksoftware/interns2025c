export function formatDateTime(value) {
  if (!value) return '-'
  try {
    // Handle ISO 8601 format: 2025-09-04T10:42:41.000000Z
    const date = new Date(value)
    if (isNaN(date.getTime())) return value

    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
    })
  } catch {
    return value
  }
}

export function toDateTimeLocal(value) {
  if (!value) return value
  try {
    const date = new Date(value)
    if (isNaN(date.getTime())) return value

    // Convert to datetime-local format: YYYY-MM-DDTHH:mm
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')

    return `${year}-${month}-${day}T${hours}:${minutes}`
  } catch {
    return value
  }
}

export function toDateOnly(value) {
  if (!value) return value
  try {
    const date = new Date(value)
    if (isNaN(date.getTime())) return value

    return date.toISOString().split('T')[0]
  } catch {
    return value
  }
}

export function prepareFormDataForEditing(data, fields) {
  const preparedData = { ...data }

  for (const field of fields) {
    const value = preparedData[field.key]

    if (!value) continue

    switch (field.type) {
    case 'date':
      preparedData[field.key] = toDateOnly(value)
      break
    case 'datetime-local':
      preparedData[field.key] = toDateTimeLocal(value)
      break
    case 'checkbox':
      preparedData[field.key] = Boolean(value)
      break
    default:
      break
    }
  }

  return preparedData
}

export function isValidDateTime(value) {
  if (!value) return false
  try {
    const date = new Date(value)
    return !isNaN(date.getTime())
  } catch {
    return false
  }
}
