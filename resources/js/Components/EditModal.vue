<script setup>
import { ref, watch, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue'
import {
  columnConfig,
  getColumnType,
  getColumnOptions,
  getColumnAttributes,
  getColumnLabel,
  isColumnEditable,
  isColumnRequired,
  sortFieldsByOrder,
} from '@/data/columnConfig.js'

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
        default:
          editData.value[fieldKey] = null
        }
      }
    }
    for (const field of editableFields.value) {
      if (field.type === 'date' && editData.value[field.key]) {
        const date = new Date(editData.value[field.key])
        if (!isNaN(date.getTime())) {
          editData.value[field.key] = date.toISOString().split('T')[0]
        }
      }
      if (field.type === 'checkbox') {
        editData.value[field.key] = Boolean(editData.value[field.key])
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

    if (key === 'is_accepted' && props.dataSetType !== 'incomingPetsRequests') {
      return false
    }
    return isColumnEditable(props.dataSetType, key)
  })

  const sortedFields = sortFieldsByOrder(props.dataSetType, fields)

  return sortedFields.map(field => ({
    key: field,
    label: getColumnLabel(props.dataSetType, field),
    type: getColumnType(props.dataSetType, field),
    options: getColumnOptions(props.dataSetType, field),
    attributes: getColumnAttributes(props.dataSetType, field),
    required: isColumnRequired(props.dataSetType, field),
  }))
})

const errors = ref({})

const validateForm = () => {
  errors.value = {}

  for (const field of editableFields.value) {
    if (field.required && (!editData.value[field.key] || editData.value[field.key] === '')) {
      errors.value[field.key] = `${field.label} is required`
    }

    if (field.type === 'email' && editData.value[field.key]) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(editData.value[field.key])) {
        errors.value[field.key] = 'Please enter a valid email address'
      }
    }

    if (field.type === 'number' && editData.value[field.key]) {
      const num = parseFloat(editData.value[field.key])
      if (field.attributes.min !== undefined && num < field.attributes.min) {
        errors.value[field.key] = `Value must be at least ${field.attributes.min}`
      }
      if (field.attributes.max !== undefined && num > field.attributes.max) {
        errors.value[field.key] = `Value must be at most ${field.attributes.max}`
      }
    }
  }

  return Object.keys(errors.value).length === 0
}

const closeModal = () => {
  errors.value = {}
  emit('close')
}

const prepareDataForSave = () => {
  const cleanedData = { ...editData.value }

  for (const field of editableFields.value) {
    if (field.type === 'number' && cleanedData[field.key] !== null && cleanedData[field.key] !== '') {
      cleanedData[field.key] = parseFloat(cleanedData[field.key])
    }

    if (!field.required && cleanedData[field.key] === '') {
      cleanedData[field.key] = null
    }
  }

  return cleanedData
}

const saveChanges = () => {
  if (validateForm()) {
    const cleanedData = prepareDataForSave()
    emit('save', cleanedData)
    closeModal()
  }
}

const showDeleteConfirm = ref(false)

const confirmDelete = () => {
  showDeleteConfirm.value = true
}

const cancelDelete = () => {
  showDeleteConfirm.value = false
}

const deleteItem = () => {
  emit('delete', props.item)
  showDeleteConfirm.value = false
  closeModal()
}

const getItemDisplayName = () => {
  return props.item.name || props.item.email || `#${props.item.id}` || 'Item'
}

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    if (showDeleteConfirm.value) {
      cancelDelete()
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
                {{ t('admin.modal.edit', { type: dataSetType.charAt(0).toUpperCase() + dataSetType.slice(1) }) }}
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
                      :min="field.attributes.min"
                      :max="field.attributes.max"
                      :step="field.attributes.step"
                      :required="field.required"
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
            <svg v-if="isSaving" class="animate-spin -ml-1 mr-3 size-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
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
            @click="confirmDelete"
          >
            <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
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
