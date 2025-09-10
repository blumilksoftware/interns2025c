export const medicalInfoMapper = {
  vaccinated: {
    icon: 'vaccinated',
    bgColor: 'bg-green-100',
    label: 'dashboard.mvp.healthStatus.vaccinated',
    description: 'dashboard.mvp.healthStatus.vaccinatedDesc',
  },
  dewormed: {
    icon: 'dewormed',
    bgColor: 'bg-amber-100',
    label: 'dashboard.mvp.healthStatus.dewormed',
    description: 'dashboard.mvp.healthStatus.dewormedDesc',
  },
  microchipped: {
    icon: 'microchipped',
    bgColor: 'bg-cyan-100',
    label: 'dashboard.mvp.healthStatus.chipped',
    description: 'dashboard.mvp.healthStatus.chippedDesc',
  },
  sterilized: {
    icon: 'sterilized',
    bgColor: 'bg-fuchsia-100',
    label: 'dashboard.mvp.healthStatus.sterilized',
    description: 'dashboard.mvp.healthStatus.sterilizedDesc',
  },
}

export const getMedicalInfo = (type) => {
  return medicalInfoMapper[type] || null
}

export const getAvailableMedicalInfo = (pet) => {
  const available = []
  
  if (pet?.vaccinated) available.push('vaccinated')
  if (pet?.dewormed) available.push('dewormed')
  if (pet?.microchipped) available.push('microchipped')
  if (pet?.sterilized) available.push('sterilized')
  
  return available.map(type => ({
    type,
    ...medicalInfoMapper[type],
  }))
}

export const healthStatusMapper = {
  healthy: {
    icon: 'healthy',
    bgColor: 'bg-green-100',
    label: 'dashboard.mvp.healthStatus.healthy',
    description: 'dashboard.mvp.healthStatus.healthState',
  },
  sick: {
    icon: 'sick',
    bgColor: 'bg-red-100',
    label: 'dashboard.mvp.healthStatus.sick',
    description: 'dashboard.mvp.healthStatus.healthState',
  },
  recovering: {
    icon: 'recovering',
    bgColor: 'bg-yellow-100',
    label: 'dashboard.mvp.healthStatus.recovering',
    description: 'dashboard.mvp.healthStatus.healthState',
  },
  critical: {
    icon: 'critical',
    bgColor: 'bg-red-100',
    label: 'dashboard.mvp.healthStatus.critical',
    description: 'dashboard.mvp.healthStatus.healthState',
  },
  unknown: {
    icon: 'unknown',
    bgColor: 'bg-gray-100',
    label: 'dashboard.mvp.healthStatus.unknown',
    description: 'dashboard.mvp.healthStatus.healthState',
  },
}

export const getHealthStatusInfo = (status) => {
  return healthStatusMapper[status] || healthStatusMapper.unknown
}
