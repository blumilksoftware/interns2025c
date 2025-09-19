export const formatAge = (age: number | string | null | undefined): string => {
  let months = Number(age)
  if (!Number.isFinite(months) || months < 0) {
    const parsed = parsePolishAgeToMonths(age)
    if (parsed === null) return String(age ?? '')
    months = parsed
  }

  const formatYearsPl = (years: number) => {
    if (years === 1) return '1 rok'
    if (years % 10 >= 2 && years % 10 <= 4 && (years % 100 < 10 || years % 100 >= 20)) return `${years} lata`
    return `${years} lat`
  }

  if (months < 12) {
    return `${months} mies.`
  }

  const years = Math.floor(months / 12)
  const rem = months % 12
  if (rem === 0) return formatYearsPl(years)
  return `${formatYearsPl(years)} ${rem} mies.`
}

export type DogAgeCategory = 'young' | 'adult' | 'senior' | 'unknown'

export const classifyDogAgeCategory = (
  age: number | string | null | undefined,
  options?: { adultFromMonths?: number; seniorFromMonths?: number }
): DogAgeCategory => {
  const months = Number(age)
  if (!Number.isFinite(months) || months < 0) return 'unknown'

  const adultFrom = options?.adultFromMonths ?? 12
  const seniorFrom = options?.seniorFromMonths ?? 96

  if (months < adultFrom) return 'young'
  if (months >= seniorFrom) return 'senior'
  return 'adult'
}

export const classifyDogAgeLabelPl = (
  age: number | string | null | undefined,
  options?: { adultFromMonths?: number; seniorFromMonths?: number }
): string => {
  const category = classifyDogAgeCategory(age, options)
  if (category === 'young') return 'młody'
  if (category === 'adult') return 'dorosły'
  if (category === 'senior') return 'stary'
  return 'nieznany'
}

export const parsePolishAgeToMonths = (
  ageText: string | number | null | undefined
): number | null => {
  if (ageText === null || ageText === undefined) return null

  if (typeof ageText === 'number') {
    return Number.isFinite(ageText) && ageText >= 0 ? Math.floor(ageText) : null
  }

  const trimmed = String(ageText).trim()
  if (trimmed === '') return null

  const direct = Number(trimmed)
  if (Number.isFinite(direct) && direct >= 0) return Math.floor(direct)

  const normalized = trimmed
    .toLowerCase()
    .replaceAll(',', ' ')
    .replaceAll('\t', ' ')
    .replace(/\s+/g, ' ')
    .trim()

  const yearsMatch = normalized.match(/(\d+)\s*(?:rok|lata|lat|r\.?)(?:\b|\s)/i)
  const monthsMatch = normalized.match(/(\d+)\s*(?:mies(?:\.|iąc(?:e|y)?|iące|ięcy)?|m\.?)(?:\b|\s|$)/i)

  let years = 0
  let months = 0

  if (yearsMatch) years = Number(yearsMatch[1])
  if (monthsMatch) months = Number(monthsMatch[1])

  if (!yearsMatch && !monthsMatch) return null

  return years * 12 + months
}

export const classifyDogAgeFromPolishText = (
  ageText: string | number | null | undefined,
  options?: { adultFromMonths?: number; seniorFromMonths?: number }
): DogAgeCategory => {
  const months = parsePolishAgeToMonths(ageText)
  if (months === null) return 'unknown'
  return classifyDogAgeCategory(months, options)
}
