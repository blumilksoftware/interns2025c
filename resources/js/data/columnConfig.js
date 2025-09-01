export const columnConfig = {
  pets: {
    id: {
      width: 'w-12 sm:w-16',
      type: 'number',
      editable: false,
      renderer: 'id',
      label: 'ID',
    },
    name: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Name',
      required: true,
    },
    adoption_url: {
      width: 'w-32 sm:w-40',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Adoption URL',
    },
    species: {
      width: 'w-16 sm:w-20',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['dog', 'cat', 'other'],
      label: 'Species',
      required: true,
    },
    breed: {
      width: 'w-28 sm:w-36',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Breed',
    },
    sex: {
      width: 'w-16 sm:w-20',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['male', 'female', 'unknown'],
      label: 'Sex',
      required: true,
    },
    age: {
      width: 'w-12 sm:w-16',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Age',
    },
    size: {
      width: 'w-16 sm:w-20',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['small', 'medium', 'large', 'giant'],
      label: 'Size',
    },
    weight: {
      width: 'w-16 sm:w-20',
      type: 'number',
      editable: true,
      renderer: 'text',
      label: 'Weight (kg)',
      min: 0,
      max: 100,
      step: 0.1,
    },
    color: {
      width: 'w-20 sm:w-24',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Color',
    },
    sterilized: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Sterilized',
    },
    description: {
      width: 'w-40 sm:w-48',
      type: 'textarea',
      editable: true,
      renderer: 'text',
      label: 'Description',
      required: true,
    },
    health_status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['healthy', 'sick', 'recovering', 'critical', 'unknown'],
      label: 'Health Status',
    },
    current_treatment: {
      width: 'w-32 sm:w-40',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Current Treatment',
    },
    vaccinated: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Vaccinated',
    },
    has_chip: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Has Chip',
    },
    chip_number: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Chip Number',
    },
    dewormed: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Dewormed',
    },
    deflea_treated: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Deflea Treated',
    },
    medical_tests: {
      width: 'w-32 sm:w-40',
      type: 'textarea',
      editable: true,
      renderer: 'text',
      label: 'Medical Tests',
    },
    food_type: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Food Type',
    },
    attitude_to_people: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
      label: 'Attitude to People',
    },
    attitude_to_dogs: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
      label: 'Attitude to Dogs',
    },
    attitude_to_cats: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
      label: 'Attitude to Cats',
    },
    attitude_to_children: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
      label: 'Attitude to Children',
    },
    activity_level: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
      label: 'Activity Level',
    },
    behavioral_notes: {
      width: 'w-40 sm:w-48',
      type: 'textarea',
      editable: true,
      renderer: 'text',
      label: 'Behavioral Notes',
    },
    admission_date: {
      width: 'w-24 sm:w-32',
      type: 'date',
      editable: true,
      renderer: 'date',
      label: 'Admission Date',
    },
    found_location: {
      width: 'w-32 sm:w-40',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Found Location',
    },
    adoption_status: {
      width: 'w-20 sm:w-24',
      type: 'select',
      editable: true,
      renderer: 'status',
      options: ['adopted', 'waiting for adoption', 'quarantined', 'in temporary home'],
      label: 'Adoption Status',
    },
    is_accepted: {
      width: 'w-16 sm:w-20',
      type: 'checkbox',
      editable: true,
      renderer: 'boolean',
      label: 'Is Accepted',
    },
    shelter_id: {
      width: 'w-20 sm:w-24',
      type: 'number',
      editable: true,
      renderer: 'text',
      label: 'Shelter ID',
      min: 1,
      required: true,
    },
    created_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Created At',
    },
    updated_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Updated At',
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
      options: ['admin', 'shelter employee', 'user'],
      label: 'Role',
      required: true,
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
      label: 'ID',
    },
    name: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Name',
      required: true,
    },
    phone: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Phone',
    },
    email: {
      width: 'w-32 sm:w-40',
      type: 'email',
      editable: true,
      renderer: 'email',
      label: 'Email',
    },
    description: {
      width: 'w-40 sm:w-48',
      type: 'textarea',
      editable: true,
      renderer: 'text',
      label: 'Description',
    },
    url: {
      width: 'w-32 sm:w-40',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'URL',
    },
    address: {
      width: 'w-32 sm:w-40',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Address',
    },
    city: {
      width: 'w-24 sm:w-32',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'City',
    },
    postal_code: {
      width: 'w-20 sm:w-24',
      type: 'text',
      editable: true,
      renderer: 'text',
      label: 'Postal Code',
    },
    created_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Created At',
    },
    updated_at: {
      width: 'w-24 sm:w-32',
      type: 'datetime-local',
      editable: false,
      renderer: 'date',
      label: 'Updated At',
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
    email: {
      width: 'w-36 sm:w-48',
      type: 'email',
      editable: true,
      renderer: 'email',
      label: 'Email',
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
  text: ['name', 'breed', 'location', 'action', 'ip_address', 'description', 'address', 'phone', 'url', 'adoption_url', 'color', 'current_treatment', 'chip_number', 'food_type', 'found_location', 'city', 'postal_code', 'age'],
  email: ['email'],
  number: ['weight', 'shelter_id'],
  date: ['admission_date'],
  datetime: ['timestamp', 'created_at', 'updated_at', 'last_login', 'adoption_date'],
  select: ['status', 'type', 'role', 'shelter', 'category', 'gender', 'size', 'color', 'species', 'sex', 'health_status', 'attitude_to_people', 'attitude_to_dogs', 'attitude_to_cats', 'attitude_to_children', 'activity_level', 'adoption_status'],
  textarea: ['description', 'medical_tests', 'behavioral_notes'],
  checkbox: ['sterilized', 'vaccinated', 'has_chip', 'dewormed', 'deflea_treated', 'is_accepted'],
}

export const fieldOrder = {
  pets: [
    // Basic Information
    'name', 'species', 'breed', 'sex', 'age', 'size', 'weight', 'color', 'description',
    // Health & Medical
    'health_status', 'current_treatment', 'sterilized', 'vaccinated', 
    'has_chip', 'chip_number', 'dewormed', 'deflea_treated', 'medical_tests',
    // Behavior & Care
    'food_type', 'attitude_to_people', 'attitude_to_dogs', 'attitude_to_cats', 
    'attitude_to_children', 'activity_level', 'behavioral_notes',
    // Adoption & Location
    'adoption_url', 'admission_date', 'found_location', 'adoption_status', 
    // Administration
    'is_accepted', 'shelter_id',
  ],
  shelters: [
    'name', 'phone', 'email', 'url', 'description', 
    'address', 'city', 'postal_code',
  ],
  users: [
    'name', 'email', 'role', 'status',
  ],
  logs: [
    'action', 'email', 'ip_address', 'status', 'timestamp',
  ],
}

export function getFieldOrder(dataSetType) {
  return fieldOrder[dataSetType] || []
}

export function sortFieldsByOrder(dataSetType, fields) {
  const order = getFieldOrder(dataSetType)
  const orderedFields = []
  const unorderedFields = []
  
  for (const fieldName of order) {
    if (fields.includes(fieldName)) {
      orderedFields.push(fieldName)
    }
  }
  
  for (const field of fields) {
    if (!order.includes(field)) {
      unorderedFields.push(field)
    }
  }
  
  return [...orderedFields, ...unorderedFields]
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
      step: fieldName === 'rating' ? 0.1 : 1,
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
