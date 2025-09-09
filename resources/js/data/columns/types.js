export const autoFieldTypes = {
  text: ['name', 'breed', 'location', 'action', 'ip_address', 'description', 'address', 'phone', 'url', 'adoption_url', 'color', 'current_treatment', 'chip_number', 'food_type', 'found_location', 'city', 'postal_code', 'age'],
  email: ['email'],
  number: ['weight', 'shelter_id'],
  date: ['admission_date'],
  datetime: ['timestamp', 'created_at', 'updated_at', 'last_login', 'adoption_date'],
  select: ['status', 'type', 'role', 'shelter', 'category', 'gender', 'size', 'color', 'species', 'sex', 'health_status', 'attitude_to_people', 'attitude_to_dogs', 'attitude_to_cats', 'attitude_to_children', 'activity_level', 'adoption_status'],
  textarea: ['description', 'medical_tests', 'behavioral_notes'],
  checkbox: ['sterilized', 'vaccinated', 'has_chip', 'dewormed', 'deflea_treated', 'is_accepted'],
}

export const commonOptions = {
  species: ['dog', 'cat', 'other'],
  sex: ['male', 'female', 'unknown'],
  size: ['small', 'medium', 'large', 'giant'],
  healthStatus: ['healthy', 'sick', 'recovering', 'critical', 'unknown'],
  attitudeLevels: ['very low', 'low', 'medium', 'high', 'very high', 'unknown'],
  adoptionStatus: ['adopted', 'waiting for adoption', 'quarantined', 'in temporary home'],
  userRoles: ['admin', 'shelter employee', 'user'],
  logStatus: ['success', 'error', 'pending', 'failed'],
  logActions: ['create', 'update', 'delete', 'login', 'logout', 'view', 'export'],
}

export const commonFields = {
  id: {
    width: 'w-12 sm:w-16',
    type: 'number',
    editable: false,
    renderer: 'id',
    label: 'ID',
  },
  createdAt: {
    width: 'w-24 sm:w-32',
    type: 'datetime-local',
    editable: true,
    renderer: 'date',
    label: 'Created At',
  },
  updatedAt: {
    width: 'w-24 sm:w-32',
    type: 'datetime-local',
    editable: true,
    renderer: 'date',
    label: 'Updated At',
  },
}
