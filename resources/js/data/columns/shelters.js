import { commonFields } from './types.js'

export const sheltersConfig = {
  id: commonFields.id,
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
  created_at: commonFields.createdAt,
  updated_at: commonFields.updatedAt,
}

export const sheltersFieldOrder = [
  'name', 'phone', 'email', 'url', 'description', 
  'address', 'city', 'postal_code',
]
