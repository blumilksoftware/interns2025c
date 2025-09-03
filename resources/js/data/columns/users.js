import { commonFields, commonOptions } from './types.js'

export const usersConfig = {
  id: commonFields.id,
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
    options: commonOptions.userRoles,
    label: 'Role',
    required: true,
  },
  status: {
    width: 'w-20 sm:w-24',
    type: 'select',
    editable: true,
    renderer: 'status',
    options: commonOptions.userStatus,
    label: 'Status',
  },
  last_login: {
    width: 'w-24 sm:w-32',
    type: 'datetime-local',
    editable: false,
    renderer: 'date',
    label: 'Last Login',
  },
  created_at: commonFields.createdAt,
}

export const usersFieldOrder = [
  'name', 'email', 'role', 'status',
]
