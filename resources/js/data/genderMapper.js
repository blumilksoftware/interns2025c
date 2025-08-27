export const genderMapper = {
  male: {
    symbol: '♂',
    color: 'text-blue-400',
    label: 'Male'
  },
  female: {
    symbol: '♀',
    color: 'text-pink-400',
    label: 'Female'
  },
  unknown: {
    symbol: '?',
    color: 'text-gray-400',
    label: 'Unknown'
  }
}

export const getGenderInfo = (gender) => {
  return genderMapper[gender] || genderMapper.unknown
}
