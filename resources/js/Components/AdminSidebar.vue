<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePage } from '@inertiajs/vue3'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronRightIcon, XMarkIcon } from '@heroicons/vue/20/solid'
import UserProfileModal from './UserProfileModal.vue'

const { t } = useI18n()
const emit = defineEmits(['data-set-change', 'close'])

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  incomingPetsRequestsCount: {
    type: Object,
    default: () => ({}),
  },
})  

const page = usePage()
const user = computed(() => page.props.auth?.user?.data)

const crudsNavigation = [
  {
    name: t('admin.sidebar.cruds'),
    current: false,
    children: [
      { name: t('admin.sidebar.pets'), key: 'pets', href: '#' },
      { name: t('admin.sidebar.users'), key: 'users', href: '#' },
      { name: t('admin.sidebar.shelters'), key: 'shelters', href: '#' },
      { name: t('admin.sidebar.logs'), key: 'logs', href: '#' },
    ],
  },
]

const incomingRequestsNavigation = [
  {
    name: t('admin.sidebar.incomingRequests'),
    current: false,
    children: [
      { name: t('admin.sidebar.petsRequests'), key: 'incomingPetsRequests', href: '#' },
    ],
  },
]

const getIncomingPetsRequestsCount = computed(() => {
  return props.incomingPetsRequestsCount.incomingPetsRequests
})

const handleDataSetChange = (key) => {
  emit('data-set-change', key)
}

const isUserProfileOpen = ref(false)
const openUserProfile = () => { isUserProfileOpen.value = true }
const closeUserProfile = () => { isUserProfileOpen.value = false }

</script>

<template>
  <div class="hidden xl:flex xl:sticky xl:top-0 xl:h-screen min-w-[100px] max-w-xs shrink-0 flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
    <div class="flex h-16 shrink-0 justify-between items-center">
      <button
        class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50 w-full text-left"
        @click="openUserProfile"
      >
        <img class="size-8 rounded-full" src="/Images/cat-dog.png" alt="Your profile" width="32" height="32" loading="lazy" decoding="async">
        <span class="sr-only">{{ t('admin.sidebar.goToProfile') }}</span>
        
        <span aria-hidden="true">{{ user?.name }}</span>
      </button>
    </div>
    <nav class="flex flex-1 flex-col ">
      <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
          <ul role="list" class="-mx-2 space-y-1">
            <li v-for="item in crudsNavigation" :key="item.name">
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
          </ul> <!-- crudsNavigation -->
          <ul role="list" class="-mx-2 space-y-1">
            <li v-for="item in incomingRequestsNavigation" :key="item.name">
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
                      <span v-if="getIncomingPetsRequestsCount > 0" class="text-xs font-bold text-white bg-red-500  rounded-full px-2 py-1 ml-2">
                        {{ getIncomingPetsRequestsCount }}
                      </span>
                    </button>
                  </li>
                </DisclosurePanel>
              </Disclosure>
            </li>
          </ul> <!-- incomingRequestsNavigation -->
        </li>
      </ul>
    </nav>
  </div>

  <transition name="fade">
    <div v-if="props.isOpen" class="fixed inset-0 z-40 flex xl:hidden">
      <div class="fixed inset-0 bg-black/20" @click="$emit('close')" />
      <div class="relative w-64 bg-white h-full shadow-xl flex flex-col gap-y-5 px-6 py-4">
        <div class="flex h-16 shrink-0 justify-between items-center">
          <button
            class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50 w-full text-left"
            @click="openUserProfile"
          >
            <img class="size-8 rounded-full bg-gray-50" src="/Images/cat-dog.png" alt="Your profile" width="32" height="32" loading="lazy" decoding="async">
            <span class="sr-only">{{ t('admin.sidebar.goToProfile') }}</span>
            <span aria-hidden="true">{{ user.name }}</span>
          </button>
          <button class="text-gray-700 hover:text-gray-900 p-2 rounded focus:outline-none" @click="$emit('close')">
            <XMarkIcon class="size-6" />
          </button>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7"> 
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in crudsNavigation" :key="item.name">
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
              </ul><!-- crudsNavigation -->
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in incomingRequestsNavigation" :key="item.name">
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
                          <span v-if="getIncomingPetsRequestsCount > 0" class="text-xs font-bold text-white bg-red-500  rounded-full px-2 py-1 ml-2">
                            {{ getIncomingPetsRequestsCount }}
                          </span>
                        </button>
                      </li>
                    </DisclosurePanel>
                  </Disclosure>
                </li>
              </ul> <!-- incomingRequestsNavigation -->
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </transition>
  
  <UserProfileModal 
    :is-open="isUserProfileOpen"
    :user="user"
    @close="closeUserProfile"
  />
</template>
