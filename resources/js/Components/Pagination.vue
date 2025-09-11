<script setup>
import { useI18n } from 'vue-i18n'
import { ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon } from '@heroicons/vue/20/solid'

const { t } = useI18n()

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
  totalItems: {
    type: Number,
    required: true,
  },
  itemsPerPage: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['page-change'])

const goToPage = (page) => {
  if (page >= 1 && page <= props.totalPages) {
    emit('page-change', page)
  }
}

const goToFirstPage = () => emit('page-change', 1)
const goToLastPage = () => emit('page-change', props.totalPages)
const goToPreviousPage = () => {
  if (props.currentPage > 1) {
    emit('page-change', props.currentPage - 1)
  }
}
const goToNextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('page-change', props.currentPage + 1)
  }
}
</script>

<template>
  <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <div class="flex items-center justify-between">
      <div class="flex-1 flex justify-between sm:hidden">
        <button 
          :disabled="currentPage === 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          @click="goToPreviousPage"
        >
          {{ t('admin.pagination.previous') }}
        </button>
        <button 
          :disabled="currentPage === totalPages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          @click="goToNextPage"
        >
          {{ t('admin.pagination.next') }}
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p v-if="totalItems > 0" class="text-sm text-gray-700">
            {{ t('admin.pagination.showing') }} <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span> {{ t('admin.pagination.to') }} <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, totalItems) }}</span> {{ t('admin.pagination.of') }} <span class="font-medium">{{ totalItems }}</span> {{ t('admin.pagination.results') }}
          </p>
          <p v-else class="text-sm text-gray-700">
            <span class="font-medium">{{ t('admin.pagination.emptyTable') }}</span> - {{ t('admin.pagination.noDataAvailable') }}
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button 
              :disabled="currentPage === 1"
              class="relative inline-flex items-center p-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              @click="goToFirstPage"
            >
              <span class="sr-only">{{ t('admin.pagination.first') }}</span>
              <ChevronDoubleLeftIcon class="size-5" />
            </button>
            
            <button 
              :disabled="currentPage === 1"
              class="relative inline-flex items-center p-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              @click="goToPreviousPage"
            >
              <span class="sr-only">{{ t('admin.pagination.previous') }}</span>
              <ChevronLeftIcon class="size-5" />
            </button>
            
            <template v-for="page in totalPages" :key="page">
              <button 
                v-if="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                :class="[
                  page === currentPage 
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' 
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
                @click="goToPage(page)"
              >
                {{ page }}
              </button>
              <span 
                v-else-if="page === currentPage - 2 || page === currentPage + 2"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
              >
                ...
              </span>
            </template>
            
            <button 
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center p-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              @click="goToNextPage"
            >
              <span class="sr-only">{{ t('admin.pagination.next') }}</span>
              <ChevronRightIcon class="size-5" />
            </button>
              
            <button 
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center p-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              @click="goToLastPage"
            >
              <span class="sr-only">{{ t('admin.pagination.last') }}</span>
              <ChevronDoubleRightIcon class="size-5" />
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template> 
