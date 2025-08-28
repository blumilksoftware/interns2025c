export const statusMapper = {
  available: {
    bgColor: 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-200 dark:border-emerald-800',
    dotColor: 'bg-emerald-500',
  },
  unavailable: {
    bgColor: 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900/20 dark:text-gray-200 dark:border-gray-700',
    dotColor: 'bg-gray-400',
  },
}

export const getStatusInfo = (status, availableStatuses) => {
  const isAvailable = availableStatuses.includes(status)
  return isAvailable ? statusMapper.available : statusMapper.unavailable
}
