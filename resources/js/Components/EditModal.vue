<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  item: {
    type: Object,
    default: () => ({})
  },
  dataSetType: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['close', 'save'])

const editData = ref({})

watch(() => props.item, (newItem) => {
  if (newItem && Object.keys(newItem).length > 0) {
    editData.value = { ...newItem }
  }
}, { immediate: true, deep: true })

const editableFields = computed(() => {
  if (!props.item || Object.keys(props.item).length === 0) return []
  
  const fields = Object.keys(props.item).filter(key => key !== 'id' && key !== 'created_at')
  
  return fields.map(field => ({
    key: field,
    label: field.charAt(0).toUpperCase() + field.slice(1).replace('_', ' '),
    type: getFieldType(field),
    options: getFieldOptions(field)
  }))
})

function getFieldType(field) {
  switch (field) {
    case 'status':
      return 'select'
    case 'age':
    case 'capacity':
    case 'current_occupancy':
      return 'number'
    case 'rating':
      return 'number'
    case 'email':
      return 'email'
    case 'timestamp':
    case 'last_login':
    case 'created_at':
      return 'datetime-local'
    default:
      return 'text'
  }
}

function getFieldOptions(field) {
  switch (field) {
    case 'status':
      return ['Available', 'Adopted', 'Pending', 'Active', 'Inactive', 'Success', 'Failed', 'Error']
    case 'type':
      return ['Dog', 'Cat', 'Bird', 'Fish', 'Other']
    case 'role':
      return ['Admin', 'User', 'Moderator']
    default:
      return []
  }
}

const closeModal = () => {
  emit('close')
}

const saveChanges = () => {
  emit('save', editData.value)
  closeModal()
}

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    closeModal()
  }
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-[9999] overflow-y-auto" @keydown="handleKeydown">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500/50 bg-opacity-75 transition-opacity" @click="closeModal"></div>
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Edit {{ dataSetType.charAt(0).toUpperCase() + dataSetType.slice(1) }}
              </h3>
              
              <form @submit.prevent="saveChanges" class="space-y-4">
                <div v-for="field in editableFields" :key="field.key" class="space-y-2">
                  <label :for="field.key" class="block text-sm font-medium text-gray-700">
                    {{ field.label }}
                  </label>
                  
                  <input
                    v-if="field.type === 'text' || field.type === 'email' || field.type === 'number'"
                    :id="field.key"
                    v-model="editData[field.key]"
                    :type="field.type"
                    :step="field.type === 'number' ? '1' : undefined"
                    :min="field.key === 'age' || field.key === 'capacity' || field.key === 'current_occupancy' ? '0' : undefined"
                    :max="field.key === 'rating' ? '5' : undefined"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  />
                  
                  <select
                    v-else-if="field.type === 'select'"
                    :id="field.key"
                    v-model="editData[field.key]"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                    <option v-for="option in field.options" :key="option" :value="option">
                      {{ option }}
                    </option>
                  </select>
                  
                  <input
                    v-else-if="field.type === 'datetime-local'"
                    :id="field.key"
                    v-model="editData[field.key]"
                    type="datetime-local"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  />
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="saveChanges"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Save Changes
          </button>
          <button
            type="button"
            @click="closeModal"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template> 