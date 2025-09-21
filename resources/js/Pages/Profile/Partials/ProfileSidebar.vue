<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {
  Bars3Icon,
  HomeIcon,
  HeartIcon,
  UserIcon,
  XMarkIcon,
  ArrowLeftIcon,
  PencilIcon,
  ShieldCheckIcon,
} from '@heroicons/vue/24/outline'
import { logo } from '@/helpers/mappers/logo'
import { router } from '@inertiajs/vue3'
import { routes } from '@/routes'
import { IconShield } from '@tabler/icons-vue'
const { t } = useI18n()

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['view-change'])

const logout = () => {
  router.post(routes.logout())
}

const viewChange = (view) => {
  emit('view-change', view)
}

const selectNavigation = (clickedItem) => {
  // ustawienie current dla wszystkich elementów
  navigation.value.forEach(item => {
    item.current = item === clickedItem
  })

  // wywołanie akcji
  if (clickedItem.action) {
    clickedItem.action()
  }
}

const navigation = ref([
  {
    name: t('profile.editProfile'),
    href: '#',
    icon: PencilIcon,
    current: true,
    anchorClass: 'group cursor-pointer flex w-full gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-amber-800 hover:bg-amber-100 hover:text-amber-800',
    iconClass: 'size-6 shrink-0 text-amber-800 group-hover:text-amber-800',
    action: () => viewChange('editProfile'),
  },
  {
    name: t('profile.security'),
    href: '#',
    icon: IconShield,
    current: false,
    anchorClass: 'group cursor-pointer flex w-full gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-amber-800 hover:bg-amber-100 hover:text-amber-800',
    iconClass: 'size-6 shrink-0 text-amber-800 group-hover:text-amber-800',
    action: () => viewChange('security'),
  },
  {
    name: t('profile.favoritePets'),
    href: '#',
    icon: HeartIcon,
    current: false,
    anchorClass: 'group cursor-pointer flex w-full gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-amber-800 hover:bg-amber-100 hover:text-amber-800',
    iconClass: 'size-6 shrink-0 text-amber-800 group-hover:text-amber-800',
    action: () => viewChange('favoritePets'),
  },
])

onMounted(() => {
  if (props.user.role === 'shelterEmployee') {
    navigation.value.push({
      name: t('profile.myShelter'),
      href: '#',
      icon: HomeIcon,
      current: false,
      anchorClass: 'group cursor-pointer flex w-full gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-amber-800 hover:bg-amber-100 hover:text-amber-800',
      iconClass: 'size-6 shrink-0 text-amber-800 group-hover:text-amber-800',
      action: () => viewChange('myShelter'),
    })
  }
})

const sidebarOpen = ref(false)

</script>

<template>
  <div class="-px-10 max-w-55 bg-amber-100/30 border-r-3 border-t-3 border-amber-100">
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="" leave="transition-opacity ease-linear duration-300" leave-from="" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-900/80" />
        </TransitionChild>

        <div class="fixed inset-0 flex">
          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="" leave="ease-in-out duration-300" leave-from="" leave-to="opacity-0">
                <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <XMarkIcon class="size-6 text-white" aria-hidden="true" />
                  </button>
                </div>
              </TransitionChild>

              <div class="relative flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-2">
                <div class="relative flex h-16 shrink-0 items-center">
                  <img class="w-auto" :src="logo.Logo" alt="Your Company">
                </div>
                <nav class="relative flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <li v-for="item in navigation" :key="item.name">
                          <component
                            :is="item.action ? 'button' : 'a'"
                            :href="!item.action ? item.href : undefined"
                            type="button"
                            :class="[item.anchorClass, item.current ? 'bg-amber-100' : 'bg-none']"
                            @click="selectNavigation(item)"
                          >
                            <component :is="item.icon" :class="item.iconClass" aria-hidden="true" />
                            {{ item.name }}
                          </component>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </nav>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
    <div class="flex flex-col gap-y-5">
      <nav class="flex flex-col gap-6 p-5 h-[calc(100vh-100px)]">
        <ul role="list" class="flex flex-col gap-y-7">
          <li>
            <img class="bg-black w-40 rounded-md" src="https://lipsum.app/random/300x300" alt="Your profile">
          </li>
          <li>
            <h4 class="heading-xl mb-0">{{ user.name }}</h4>
            <p class="text-sm text-gray-500 mb-4"> {{ user.email }}</p>
            <p class="text-sm text-gray-500 flex flex-row items-center gap-x-2">
              <UserIcon class="size-6 shrink-0 text-black " aria-hidden="true" />
              {{ t('enum.role.' + user.role) }}
            </p>
          </li>
        </ul>
        <ul role="list" class="flex flex-col justify-between h-full ">
          <li>
            <ul role="list" class="-mx-2 space-y-1">
              <li v-for="item in navigation" :key="item.name">
                <component
                  :is="item.action ? 'button' : 'a'"
                  :href="!item.action ? item.href : undefined"
                  type="button"
                  :class="[item.anchorClass, item.current ? 'bg-amber-100' : 'bg-none']"
                  @click="selectNavigation(item)"
                >
                  <component :is="item.icon" :class="item.iconClass" aria-hidden="true" />
                  {{ item.name }}
                </component>
              </li>
            </ul>
          </li>
          <li>
            <ul role="list" class="-mx-2 space-y-1">
              <li>
                <button type="button" class="group cursor-pointer flex w-full gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-red-600 hover:text-red-700 hover:bg-red-100" @click="logout">
                  <ArrowLeftIcon class="size-6 shrink-0 text-red-500 group-hover:text-red-600" aria-hidden="true" />
                  {{ t('navigation.logOut') }}
                </button>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white p-4 shadow-xs sm:px-6 lg:hidden">
      <button type="button" class="-m-2.5 p-2.5 text-gray-700 hover:text-gray-900 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <Bars3Icon class="size-6" aria-hidden="true" />
      </button>
      <div class="flex-1 text-sm/6 font-semibold text-gray-900">Dashboard</div>
      <a href="#">
        <span class="sr-only">Your profile</span>
        <img class="size-8 rounded-full bg-gray-50 outline -outline-offset-1 outline-black/5" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
      </a>
    </div>

    <main class="lg:pl-72">
      <div class="px-4 sm:px-6 lg:px-8">
        <!-- Your content -->
      </div>
    </main>
  </div>
</template>
