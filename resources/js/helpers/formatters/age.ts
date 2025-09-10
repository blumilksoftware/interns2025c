export const formatAge = (age: number | string | null | undefined): string => {
  const months = Number(age)
  if (!Number.isFinite(months) || months < 0) return String(age ?? '')

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


