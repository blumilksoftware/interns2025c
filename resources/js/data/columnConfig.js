export const columnConfig = {
  pets: {
    id: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: false,
      renderer: 'id',
    },
    name: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Name',
    },
    type: {
      width: 'w-16 sm:w-20',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Dog', 'Cat', 'Bird', 'Fish', 'Rabbit', 'Hamster', 'Other'],
      label: 'Type',
    },
    breed: {
      width: 'w-28 sm:w-36',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Breed',
    },
    age: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: true,
      renderer: 'age',
      min: 0,
      max: 30,
      step: 1,
      label: 'Age',
    },
    status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Available', 'Adopted', 'Pending', 'In Treatment'],
      label: 'Status',
    },
    shelter: {
      width: 'w-32 sm:w-40',
      type: 'select',
      editable: true,
      renderer: 'text',
      options: ['Happy Paws', 'Cat Care', 'Animal Haven', 'Pet Paradise', 'Furry Friends', 'Safe Haven', 'Companion Care', 'Animal Rescue'],
      label: 'Shelter',
    },
    created_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Created At',
    },
  },

  users: {
    id: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: false,
      renderer: 'id',
    },
    name: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Name',
    },
    email: {
      width: 'w-36 sm:w-48',
      type: 'email',
      editable: true,
      renderer: 'email',
      label: 'Email',
    },
    role: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Admin', 'Shelter Caretaker', 'User', 'Moderator', 'Veterinarian'],
      label: 'Role',
    },
    status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Active', 'Inactive', 'Suspended', 'Pending'],
      label: 'Status',
    },
    last_login: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Last Login',
    },
    created_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Created At',
    },
  },

  shelters: {
    id: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: false,
      renderer: 'id',
    },
    name: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Name',
    },
    location: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Location',
    },
    capacity: {
      width: 'w-16 sm:w-20',
      type: 'number',
      editable: true,
      renderer: 'number',
      min: 1,
      max: 1000,
      step: 1,
      label: 'Capacity',
    },
    current_occupancy: {
      width: 'w-20 sm:w-28',
      type: 'number',
      editable: true,
      renderer: 'number',
      min: 0,
      max: 1000,
      step: 1,
      label: 'Current Occupancy',
    },
    rating: {
      width: 'w-16 sm:w-20',
      type: 'number',
      editable: true,
      renderer: 'rating',
      min: 0,
      max: 5,
      step: 0.1,
      label: 'Rating',
    },
    status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Active', 'Inactive', 'Under Maintenance', 'Full'],
      label: 'Status',
    },
    created_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Created At',
    },
  },

  logs: {
    id: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: false,
      renderer: 'id',
    },
    action: {
      width: 'w-24 sm:w-32',
      type: 'select',
      editable: true,
      renderer: 'text',
      options: ['User Login', 'User Logout', 'Pet Added', 'Pet Adopted', 'Shelter Updated', 'User Created', 'Data Export', 'System Backup', 'Error Logged', 'Failed Login'],
      label: 'Action',
    },
    user_email: {
      width: 'w-36 sm:w-48',
      type: 'email',
      editable: true,
      renderer: 'email',
      label: 'User Email',
    },
    ip_address: {
      width: 'w-28 sm:w-36',
      type: 'text',
      editable: true,
      renderer: 'ip',
      label: 'IP Address',
    },
    status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['Success', 'Failed', 'Error', 'Warning'],
      label: 'Status',
    },
    timestamp: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: true,
      renderer: 'date',
      label: 'Timestamp',
    },
  },
}

const autoFieldTypes = {
  text: ['name', 'breed', 'location', 'action', 'ip_address', 'description', 'notes', 'address', 'phone', 'website'],
  email: ['email', 'user_email', 'contact_email', 'admin_email'],
  number: ['age', 'capacity', 'current_occupancy', 'rating', 'price', 'weight', 'height', 'count', 'quantity', 'score'],
  datetime: ['timestamp', 'created_at', 'updated_at', 'last_login', 'birth_date', 'adoption_date', 'expiry_date'],
  select: ['status', 'type', 'role', 'shelter', 'category', 'priority', 'gender', 'size', 'color'],
}

export function getColumnConfig(dataSetType, fieldName) {
  return columnConfig[dataSetType]?.[fieldName] || null
}

export function getColumnType(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  if (config) return config.type
  
  for (const [type, fields] of Object.entries(autoFieldTypes)) {
    if (fields.includes(fieldName) || fields.some(f => fieldName.includes(f))) {
      return type === 'datetime' ? 'datetime-local' : type
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
      step: fieldName === 'rating' ? 0.1 : 1,
    }
  }
  
  return {}
}

export function getColumnLabel(dataSetType, fieldName) {
  const config = getColumnConfig(dataSetType, fieldName)
  if (config?.label) return config.label
  
  const labelMap = {
    user_email: 'User Email',
    ip_address: 'IP Address',
    current_occupancy: 'Current Occupancy',
    last_login: 'Last Login',
    created_at: 'Created At',
    updated_at: 'Updated At',
  }
  
  if (labelMap[fieldName]) {
    return labelMap[fieldName]
  }
  
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
