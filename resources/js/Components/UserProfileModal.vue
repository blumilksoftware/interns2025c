<script setup>
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  user: {
    type: Object,
    default: () => ({
      name: 'Tomasz Rebizant',
      email: 'tomasz.rebizant@example.com',
      phone: '+48 123 456 789',
      avatar: '/Images/cat-dog.png',
    }),
  },
})

const emit = defineEmits(['close'])

const closeModal = () => {
  emit('close')
}

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    closeModal()
  }
}

const changePassword = () => {
  // TODO: Implement password change functionality
  alert('Password change functionality will be implemented here')
}

const changePicture = () => {
  // TODO: Implement picture change functionality
  alert('Picture change functionality will be implemented here')
}

const logout = () => {
  router.visit('/')
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-[9999] overflow-y-auto" @keydown="handleKeydown">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500/50 transition-opacity" @click="closeModal" />
      <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ t('admin.profile.title') }}
                </h3>
                <button
                  class="text-gray-400 hover:text-gray-600 focus:outline-none"
                  @click="closeModal"
                >
                  <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <div class="flex flex-col items-center sm:items-start space-y-4">
                <!-- User Avatar -->
                <div class="shrink-0 relative">
                  <img 
                    :src="user.avatar" 
                    :alt="user.name"
                    class="size-24 rounded-full object-cover border-4 border-gray-200"
                    width="96"
                    height="96"
                    loading="lazy"
                    decoding="async"
                  >
                  <button
                    class="absolute -bottom-1 -right-1 bg-blue-600 text-white rounded-full p-1.5 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    :title="t('admin.profile.changePicture')"
                    @click="changePicture"
                  >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </button>
                </div>
                
                <!-- User Information -->
                <div class="flex-1 min-w-0 space-y-3">
                  <div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ user.name }}</h4>
                    <p class="text-sm text-gray-500">{{ t('admin.profile.administrator') }}</p>
                  </div>
                  
                  <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                      <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      <span class="text-sm text-gray-700">{{ user.email }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                      <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span class="text-sm text-gray-700">{{ user.phone }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-col space-y-2">
          <button
            type="button"
            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
            @click="changePassword"
          >
            <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            {{ t('admin.profile.changePassword') }}
          </button>
          
          <button
            type="button"
            class="w-full inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
            @click="logout"
          >
            <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            {{ t('admin.profile.logout') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template> 
