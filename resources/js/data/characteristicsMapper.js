export const characteristicsMapper = {
  age: {
    icon: 'age',
    label: 'dashboard.mvp.age',
  },
  breed: {
    icon: 'breed',
    label: 'dashboard.mvp.breed',
  },
  gender: {
    icon: 'gender', // This will be dynamically determined
    label: 'dashboard.mvp.gender',
  },
}

export const getCharacteristicInfo = (key) => {
  return characteristicsMapper[key] || { icon: 'default', label: key }
}

export const getPetCharacteristics = (pet) => {
  const characteristics = []
  
  if (pet?.age) {
    characteristics.push({
      key: 'age',
      label: characteristicsMapper.age.label,
      value: pet.age,
      icon: characteristicsMapper.age.icon,
    })
  }
  
  if (pet?.breed) {
    characteristics.push({
      key: 'breed',
      label: characteristicsMapper.breed.label,
      value: pet.breed,
      icon: characteristicsMapper.breed.icon,
    })
  }
  
  if (pet?.gender || pet?.sex) {
    const isMale = (pet.gender === 'male') || (String(pet.sex).toLowerCase() === 'male' || String(pet.sex).toLowerCase() === 'm')
    characteristics.push({
      key: 'gender',
      label: characteristicsMapper.gender.label,
      value: isMale ? 'dashboard.mvp.male' : 'dashboard.mvp.female',
      icon: isMale ? 'male' : 'female',
    })
  }
  
  return characteristics
}
