<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  itemName: {
    type: String,
    default: '',
  },
  dataSetType: {
    type: String,
    required: true,
  },
  isDeleting: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['confirm', 'cancel'])

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    emit('cancel')
  }
}

const confirmDelete = () => {
  emit('confirm')
}

const cancelDelete = () => {
  emit('cancel')
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-[10000] overflow-y-auto" @keydown="handleKeydown">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="cancelDelete" />
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
              <svg class="size-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ t('admin.modal.confirmDelete') }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  {{ t('admin.modal.confirmDeleteMessage', { name: itemName }) }} 
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            :disabled="isDeleting"
            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="confirmDelete"
          >
            <svg v-if="isDeleting" class="animate-spin -ml-1 mr-3 size-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            {{ isDeleting ? t('admin.modal.deleting') : t('admin.modal.confirmDeleteButton') }}
          </button>
          <button
            type="button"
            :disabled="isDeleting"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="cancelDelete"
          >
            {{ t('admin.modal.cancel') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
