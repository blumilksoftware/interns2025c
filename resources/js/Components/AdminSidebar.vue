<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronRightIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/20/solid'

const navigation = [
  {
    name: 'CRUDs',
    current: false,
    children: [
      { name: 'Pets', href: '#' },
      { name: 'Users', href: '#' },
      { name: 'Shelters', href: '#' },
      { name: 'Logs', href: '#' }
    ],
  },
]

const goToHome = () => {
  router.visit('/')
}

const isSidebarOpen = ref(false)
const closeSidebar = () => { isSidebarOpen.value = false }

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
  <button @click="isSidebarOpen = true" class="sm:hidden fixed top-4 left-4 z-30 bg-gray-800 text-white p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400">
    <Bars3Icon class="h-6 w-6" />
  </button>
  
  <div class="hidden sm:flex min-w-[150px] w-100 flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
    <div class="flex h-16 shrink-0 justify-between items-center">
      <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
      <button 
        @click="goToHome"
        class="flex items-center gap-1 px-2 py-1 text-xs font-medium bg-gray-800 text-white hover:text-gray-800 hover:bg-gray-300 rounded transition-colors duration-200"
      >
        <span>←</span>
        <span class="hidden sm:inline">Home</span>
      </button>
    </div>
    <nav class="flex flex-1 flex-col">
      <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <li v-for="item in navigation" :key="item.name">
              <a v-if="!item.children" :href="item.href" :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-10 text-sm/6 font-semibold text-gray-700']">{{ item.name }}</a>
              <Disclosure as="div" v-else v-slot="{ open }">
                <DisclosureButton :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-gray-700']">
                  <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'size-5 shrink-0']" aria-hidden="true" />
                  {{ item.name }}
                </DisclosureButton>
                <DisclosurePanel as="ul" class="mt-1 px-2">
                  <li v-for="subItem in item.children" :key="subItem.name">
                    <DisclosureButton as="a" :href="subItem.href" :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-9 text-sm/6 text-gray-700']">{{ subItem.name }}</DisclosureButton>
                  </li>
                </DisclosurePanel>
              </Disclosure>
            </li>
          </ul>
        </li>
        <li class="-mx-6 mt-auto">
          <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50">
            <img class="size-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
            <span class="sr-only">Your profile</span>
            <span aria-hidden="true">Tomasz Rebizant</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
  
  <transition name="fade">
    <div v-if="isSidebarOpen" class="fixed inset-0 z-40 flex">
      <div class="fixed inset-0 bg-black/20" @click="closeSidebar"></div>
      <div class="relative w-64 bg-white h-full shadow-xl flex flex-col gap-y-5 px-6 py-4">
        <div class="flex h-16 shrink-0 justify-between items-center">
          <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
          <button @click="closeSidebar" class="text-gray-700 hover:text-gray-900 p-2 rounded focus:outline-none">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in navigation" :key="item.name">
                  <a v-if="!item.children" :href="item.href" :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-10 text-sm/6 font-semibold text-gray-700']">{{ item.name }}</a>
                  <Disclosure as="div" v-else v-slot="{ open }">
                    <DisclosureButton :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-gray-700']">
                      <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'size-5 shrink-0']" aria-hidden="true" />
                      {{ item.name }}
                    </DisclosureButton>
                    <DisclosurePanel as="ul" class="mt-1 px-2">
                      <li v-for="subItem in item.children" :key="subItem.name">
                        <DisclosureButton as="a" :href="subItem.href" :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pr-2 pl-9 text-sm/6 text-gray-700']">{{ subItem.name }}</DisclosureButton>
                      </li>
                    </DisclosurePanel>
                  </Disclosure>
                </li>
              </ul>
            </li>
            <li class="-mx-6 mt-auto">
              <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50">
                <img class="size-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                <span class="sr-only">Your profile</span>
                <span aria-hidden="true">Tomasz Rebizant</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style> 