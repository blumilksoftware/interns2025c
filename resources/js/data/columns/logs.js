import { commonFields } from './types.js'

export const logsConfig = {
  id: commonFields.id,
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
}

export const logsFieldOrder = [
  'action', 'email', 'ip_address', 'status', 'timestamp',
]
