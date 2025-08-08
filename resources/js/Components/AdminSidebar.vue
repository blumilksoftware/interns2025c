<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronRightIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/20/solid'
import UserProfileModal from './UserProfileModal.vue'

const emit = defineEmits(['data-set-change'])

const navigation = [
  {
    name: 'CRUDs',
    current: false,
    children: [
      { name: 'Pets', key: 'pets', href: '#' },
      { name: 'Users', key: 'users', href: '#' },
      { name: 'Shelters', key: 'shelters', href: '#' },
      { name: 'Logs', key: 'logs', href: '#' },
    ],
  },
]

const goToHome = () => {
  router.visit('/')
}

const handleDataSetChange = (key) => {
  emit('data-set-change', key)
}

const isSidebarOpen = ref(false)
const isUserProfileOpen = ref(false)
const closeSidebar = () => { isSidebarOpen.value = false }
const openUserProfile = () => { isUserProfileOpen.value = true }
const closeUserProfile = () => { isUserProfileOpen.value = false }

function handleResize() {
  if (window.innerWidth >= 640) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <button class="xl:hidden fixed top-4 left-4 z-30 bg-gray-800/20 text-white p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400" @click="isSidebarOpen = true">
    <Bars3Icon class="size-6" />
  </button>
  <div class="hidden xl:flex min-w-[100px] max-w-xs shrink-0 flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
    <div class="flex h-16 shrink-0 justify-between items-center">
      <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="Interns2025c app logo" height="32" loading="lazy" decoding="async">
    </div>
    <nav class="flex flex-1 flex-col">
      <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <li v-for="item in navigation" :key="item.name">
              <a v-if="!item.children" :href="item.href" :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-10 text-sm/6 font-semibold text-gray-700']">{{ item.name }}</a>
              <Disclosure v-else v-slot="{ open }" as="div">
                <DisclosureButton :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-gray-700']">
                  <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'size-5 shrink-0']" aria-hidden="true" />
                  {{ item.name }}
                </DisclosureButton>
                <DisclosurePanel as="ul" class="mt-1 px-2">
                  <li v-for="subItem in item.children" :key="subItem.name">
                    <button
                      :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block w-full text-left rounded-md py-2 pr-2 pl-9 text-sm/6 text-gray-700']"
                      @click="handleDataSetChange(subItem.key)"
                    >
                      {{ subItem.name }}
                    </button>
                  </li>
                </DisclosurePanel>
              </Disclosure>
            </li>
          </ul>
        </li>
        <li class="-mx-6 mt-auto">
          <button
            class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50 w-full text-left"
            @click="openUserProfile"
          >
            <img class="size-8 rounded-full bg-gray-50" src="/Images/cat-dog.png" alt="Your profile" width="32" height="32" loading="lazy" decoding="async">
            <span class="sr-only">Go to your profile</span>
            <span aria-hidden="true">Tomasz Rebizant</span>
          </button>
        </li>
      </ul>
    </nav>
  </div>

  <transition name="fade">
    <div v-if="isSidebarOpen" class="fixed inset-0 z-40 flex xl:hidden">
      <div class="fixed inset-0 bg-black/20" @click="closeSidebar" />
      <div class="relative w-64 bg-white h-full shadow-xl flex flex-col gap-y-5 px-6 py-4">
        <div class="flex h-16 shrink-0 justify-between items-center">
          <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="Interns2025c app logo" height="32" loading="lazy" decoding="async">
          <button class="text-gray-700 hover:text-gray-900 p-2 rounded focus:outline-none" @click="closeSidebar">
            <XMarkIcon class="size-6" />
          </button>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in navigation" :key="item.name">
                  <a v-if="!item.children" :href="item.href" :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-10 text-sm/6 font-semibold text-gray-700']">{{ item.name }}</a>
                  <Disclosure v-else v-slot="{ open }" as="div">
                    <DisclosureButton :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-gray-700']">
                      <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'size-5 shrink-0']" aria-hidden="true" />
                      {{ item.name }}
                    </DisclosureButton>
                    <DisclosurePanel as="ul" class="mt-1 px-2">
                      <li v-for="subItem in item.children" :key="subItem.name">
                        <button
                          :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block w-full text-left rounded-md py-2 pr-2 pl-9 text-sm/6 text-gray-700']"
                          @click="handleDataSetChange(subItem.key)"
                        >
                          {{ subItem.name }}
                        </button>
                      </li>
                    </DisclosurePanel>
                  </Disclosure>
                </li>
              </ul>
            </li>
            <li class="-mx-6 mt-auto">
              <button
                class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50 w-full text-left"
                @click="openUserProfile"
              >
                <img class="size-8 rounded-full bg-gray-50" src="/Images/cat-dog.png" alt="Your profile" width="32" height="32" loading="lazy" decoding="async">
                <span class="sr-only">Go to your profile</span>
                <span aria-hidden="true">Tomasz Rebizant</span>
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </transition>
  
  <UserProfileModal 
    :is-open="isUserProfileOpen"
    :user="{
      name: 'Tomasz Rebizant',
      email: 'tomasz.rebizant@example.com',
      phone: '+48 123 456 789',
      avatar: '/Images/cat-dog.png'
    }"
    @close="closeUserProfile"
  />
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style> 
