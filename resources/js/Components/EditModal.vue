<script setup>
import { ref, watch, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue'
import CommonIcons from '@/Components/Icons/CommonIcons.vue'
import { prepareFormDataForEditing } from '@/helpers/dateTimeHelper.js'
import { isNotEmpty } from '@/helpers/checkFunctions'
import {
  columnConfig,
  getColumnType,
  getColumnOptions,
  getColumnAttributes,
  getColumnLabel,
  isColumnEditable,
  isColumnRequired,
} from '@/data/columns/index'

const { t } = useI18n()

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  item: {
    type: Object,
    default: () => ({}),
  },
  dataSetType: {
    type: String,
    required: true,
  },
  isSaving: {
    type: Boolean,
    default: false,
  },
  isDeleting: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close', 'save', 'delete'])

const editData = ref({})
const errors = ref({})
const convertTagsToDisplayFormat = (tags) => {
  if (!Array.isArray(tags) || tags.length === 0) return ''
  
  const tagNames = tags.map(tag => {
    if (typeof tag === 'object' && tag.name) return tag.name
    return String(tag)
  })
  
  return tagNames.join(', ')
}

const convertTagNamesToArray = (tagNamesString) => {
  if (!tagNamesString || typeof tagNamesString !== 'string') return []
  
  return tagNamesString.split(',').map(name => name.trim()).filter(name => name.length > 0)
}

const convertArrayToDisplayFormat = (array) => {
  if (!Array.isArray(array) || array.length === 0) return ''
  
  if (typeof array[0] === 'object' && array[0].name) {
    return array.map(item => item.name).join(', ')
  }
  return array.join(', ')
}

watch(() => props.item, (newItem) => {
  if (newItem && Object.keys(newItem).length > 0) {
    editData.value = { ...newItem }
    const configuredFields = Object.keys(columnConfig[props.dataSetType] || {})
    for (const fieldKey of configuredFields) {
      if (!(fieldKey in editData.value)) {
        const fieldType = getColumnType(props.dataSetType, fieldKey)
        switch (fieldType) {
        case 'checkbox':
          editData.value[fieldKey] = false
          break
        case 'number':
          editData.value[fieldKey] = null
          break
        case 'array':
          editData.value[fieldKey] = Array.isArray(editData.value[fieldKey])
            ? editData.value[fieldKey]
            : []
          break
        default:
          editData.value[fieldKey] = null
        }
      }
    }
    editData.value = prepareFormDataForEditing(editData.value, editableFields.value)
    
    const allConfiguredFields = Object.keys(columnConfig[props.dataSetType] || {})
    for (const fieldKey of allConfiguredFields) {
      const fieldType = getColumnType(props.dataSetType, fieldKey)
      if (fieldType === 'array' && Array.isArray(editData.value[fieldKey])) {
        editData.value[fieldKey] = fieldKey === 'tags' 
          ? convertTagsToDisplayFormat(editData.value[fieldKey])
          : convertArrayToDisplayFormat(editData.value[fieldKey])
      }
      if (field.type === 'array') {
        arrayFieldsAsText.value[field.key] = Array.isArray(editData.value[field.key]) 
          ? editData.value[field.key].join(', ') 
          : (editData.value[field.key] || '')
      }
    }
  }
}, { immediate: true, deep: true })


const editableFields = computed(() => {
  if (!props.item || Object.keys(props.item).length === 0) return []

  const itemFields = Object.keys(props.item)
  const configuredFields = Object.keys(columnConfig[props.dataSetType] || {})

  const allPossibleFields = [...new Set([...itemFields, ...configuredFields])]

  const fields = allPossibleFields.filter(key => {
    if (key === 'id') return false

    if (key === 'is_accepted') {
      return false
    }

    const fieldType = getColumnType(props.dataSetType, key)
    const isDateTimeField = fieldType === 'date' || fieldType === 'datetime-local'
    const isSystemManagedField = key.endsWith('_at') || 
      key === 'timestamp' || 
      key === 'last_login' || 
      key === 'adoption_date' ||
      key === 'created_at' ||
      key === 'updated_at' ||
      key === 'verified_at' ||
      key === 'deleted_at'
      
    if (isDateTimeField && isSystemManagedField) {
      return false
    }

    return isColumnEditable(props.dataSetType, key)
  })

  const sortedFields = fields

  return sortedFields.map(field => ({
    key: field,
    label: getColumnLabel(props.dataSetType, field),
    type: getColumnType(props.dataSetType, field),
    options: getColumnOptions(props.dataSetType, field),
    attributes: getColumnAttributes(props.dataSetType, field),
    required: isColumnRequired(props.dataSetType, field),
  }))
})

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const validateForm = () => {
  errors.value = {}

  for (const field of editableFields.value) {
    const value = editData.value[field.key]

    if (field.required && !isNotEmpty(value)) {
      errors.value[field.key] = `${field.label} is required`
      continue
    }

    if (!isNotEmpty(value)) continue

    switch (field.type) {
    case 'email': {
      if (!emailRegex.test(value)) {
        errors.value[field.key] = 'Please enter a valid email address'
      }
      break
    }
    case 'number': {
      const num = parseFloat(value)
      if (isNaN(num)) {
        errors.value[field.key] = 'Please enter a valid number'
        break
      }
      if (field.attributes?.min !== undefined && num < field.attributes.min) {
        errors.value[field.key] = `Value must be at least ${field.attributes.min}`
      }
      if (field.attributes?.max !== undefined && num > field.attributes.max) {
        errors.value[field.key] = `Value must be at most ${field.attributes.max}`
      }
      break
    }
    }
  }

  return Object.keys(errors.value).length === 0
}


const closeModal = () => {
  errors.value = {}
  arrayFieldsAsText.value = {}
  emit('close')
}

const prepareDataForSave = () => {
  const cleanedData = { ...editData.value }

  for (const field of editableFields.value) {
    if (field.type === 'number' && isNotEmpty(cleanedData[field.key])) {
      cleanedData[field.key] = parseFloat(cleanedData[field.key])
    }

    if (field.type === 'array') {
      if (cleanedData[field.key] && typeof cleanedData[field.key] === 'string') {
        cleanedData[field.key] = field.key === 'tags'
          ? convertTagNamesToArray(cleanedData[field.key])
          : cleanedData[field.key].split(',').map(item => item.trim()).filter(item => item.length > 0)
      } else if (!Array.isArray(cleanedData[field.key])) {
        cleanedData[field.key] = []
      }
    }

    if (!field.required && cleanedData[field.key] === '') {
      cleanedData[field.key] = null
    }
  }

  return cleanedData
}

const saveChanges = () => {
  for (const field of editableFields.value) {
    if (field.type === 'array') {
      handleArrayInputBlur(field.key)
    }
  }
  
  if (validateForm()) {
    const cleanedData = prepareDataForSave()
    emit('save', cleanedData)
    closeModal()
  }
}

const showDeleteConfirm = ref(false)

const toggleDeleteConfirm = (value) => {
  showDeleteConfirm.value = value ?? !showDeleteConfirm.value
}

const deleteItem = () => {
  emit('delete', props.item)
  toggleDeleteConfirm(false)
  closeModal()
}

const cancelDelete = () => {
  toggleDeleteConfirm(false)
}


const getItemDisplayName = () => {
  return 'Item'
}

const formatArrayInput = (value) => {
  if (!value || typeof value !== 'string') return []
  
  // Handle empty string case
  const trimmedValue = value.trim()
  if (trimmedValue === '') return []
  
  const items = trimmedValue
    .split(/[,;]/) // Allow both commas and semicolons as separators
    .map(item => item.trim()) // Remove whitespace
    .filter(item => item.length > 0) // Remove empty items
  
  // Remove duplicates (case-insensitive)
  const seen = new Set()
  return items.filter(item => {
    const lowerItem = item.toLowerCase()
    if (seen.has(lowerItem)) {
      return false
    }
    seen.add(lowerItem)
    return true
  })
}

const handleArrayInputChange = (fieldKey, value) => {
  // Just store the text value, don't convert yet
  arrayFieldsAsText.value[fieldKey] = value
}

const handleArrayInputBlur = (fieldKey) => {
  // Convert to array when user finishes editing
  const textValue = arrayFieldsAsText.value[fieldKey] || ''
  const arrayValue = formatArrayInput(textValue)
  
  // Update the actual data
  editData.value[fieldKey] = arrayValue
  
  // Update the display text with cleaned format
  arrayFieldsAsText.value[fieldKey] = arrayValue.join(', ')
}

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    if (showDeleteConfirm.value) {
      toggleDeleteConfirm(false)
    } else {
      closeModal()
    }
  }
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-[9999] overflow-y-auto" @keydown="handleKeydown">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500/50 transition-opacity" @click="closeModal" />
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ t('admin.modal.edit', { type: getItemDisplayName() }) }}
              </h3>

              <form class="space-y-4" @submit.prevent="saveChanges">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div v-for="field in editableFields" :key="field.key" class="space-y-2" :class="{ 'md:col-span-2': field.type === 'textarea' }">
                    <label :for="field.key" class="block text-sm font-medium text-gray-700">
                      {{ field.label }}
                      <span v-if="field.required" class="text-red-500">*</span>
                    </label>

                    <div v-if="field.type === 'checkbox'" class="flex items-center">
                      <input
                        :id="field.key"
                        v-model="editData[field.key]"
                        type="checkbox"
                        class="size-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                      >
                      <label :for="field.key" class="ml-2 block text-sm text-gray-900">
                        {{ field.label }}
                      </label>
                    </div>

                    <input
                      v-else-if="field.type === 'text' || field.type === 'email' || field.type === 'number'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      :type="field.type"
                      :min="field.attributes?.min"
                      :max="field.attributes?.max"
                      :step="field.attributes?.step"
                      :required="field.required"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    >

                    <input
                      v-else-if="field.type === 'array'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      type="text"
                      :required="field.required"
                      :placeholder="field.key === 'tags' ? 'Enter any tag names separated by commas (new tags will be created automatically)' : 'Enter values separated by commas'"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    >
                    <input
                      v-else-if="field.type === 'date'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      type="date"
                      :required="field.required"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    >

                    <textarea
                      v-else-if="field.type === 'textarea'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      rows="3"
                      :required="field.required"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    />

                    <select
                      v-else-if="field.type === 'select'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      :required="field.required"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    >
                      <option value="">{{ t('admin.modal.select', { field: field.label.toLowerCase() }) }}</option>
                      <option v-for="option in field.options" :key="option" :value="option">
                        {{ option }}
                      </option>
                    </select>
                    <input
                      v-else-if="field.type === 'datetime-local'"
                      :id="field.key"
                      v-model="editData[field.key]"
                      type="datetime-local"
                      :required="field.required"
                      :class="[
                        'mt-1 block w-full rounded-md shadow-sm sm:text-sm',
                        errors[field.key]
                          ? 'border-red-300 focus:ring-red-500 focus:border-red-500'
                          : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500'
                      ]"
                    >

                    <p v-if="errors[field.key]" class="mt-1 text-sm text-red-600">
                      {{ errors[field.key] }}
                    </p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            :disabled="isSaving"
            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="saveChanges"
          >
            <CommonIcons v-if="isSaving" name="loading" class="animate-spin -ml-1 mr-3 size-4 text-white" />
            {{ isSaving ? t('admin.modal.saving') : t('admin.modal.saveChanges') }}
          </button>
          <button
          
            type="button"
            :disabled="isSaving"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="closeModal"
          >
            {{ t('admin.modal.cancel') }}
          </button>
          <button
            type="button"
            :disabled="isSaving || isDeleting"
            class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:mr-auto sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="toggleDeleteConfirm"
          >
            <CommonIcons name="delete" class="size-4 mr-2" />
            {{ t('admin.modal.delete') }}
          </button>
        </div>

        <DeleteConfirmationModal
          :is-open="showDeleteConfirm"
          :item-name="getItemDisplayName()"
          :data-set-type="dataSetType"
          :is-deleting="isDeleting"
          @confirm="deleteItem"
          @cancel="cancelDelete"
        />
      </div>
    </div>
  </div>
</template>
