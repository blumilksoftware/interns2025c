export const columnWidths = {
  id: 'w-12 sm:w-16',
  email: 'w-36 sm:w-48',
  
  date: 'w-24 sm:w-32',
  created: 'w-24 sm:w-32',
  updated: 'w-24 sm:w-32',
  login: 'w-24 sm:w-32',
  timestamp: 'w-24 sm:w-32',
  
  status: 'w-20 sm:w-24',
  type: 'w-16 sm:w-20',
  
  action: 'w-24 sm:w-32',
  ip: 'w-28 sm:w-36',
  
  agent: 'w-36 sm:w-48',
  details: 'w-48 sm:w-64',
  
  name: 'w-24 sm:w-32',
  breed: 'w-28 sm:w-36',
  
  shelter: 'w-32 sm:w-40',
  location: 'w-24 sm:w-32',
  
  capacity: 'w-16 sm:w-20',
  occupancy: 'w-20 sm:w-28',
  
  rating: 'w-16 sm:w-20',
  age: 'w-12 sm:w-16',
  
  role: 'w-20 sm:w-24',
}

 
export function getWidthByPattern(key) {
  const keyLower = key.toLowerCase()
  for (const [pattern, width] of Object.entries(columnWidths)) {
    if (keyLower.includes(pattern)) {
      return width
    }
  }
  return null
} 
