<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import {
  Dialog,
  DialogPanel,
  PopoverGroup,
} from '@headlessui/vue'
import {
  Bars3Icon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import NavLink from './NavLink.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { routes } from '@/routes'
import { logo } from '@/helpers/mappers/logo'

const { t } = useI18n()
const mobileMenuOpen = ref(false)

</script>

<template>
  <header>
    <nav class="mx-auto flex min-w-full items-center justify-between px-12" aria-label="Global">
      <div class="flex lg:flex-1">
        <Link :href="routes.home()" class="-m-1.5 p-1.5">
          <span class="sr-only">{{ t('navigation.goToHomepage') }}</span>
          <img class="w-1/3" :src="logo.Logo" alt="LapGo app logo" loading="lazy" decoding="async">
        </Link>
      </div>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
          <span class="sr-only">{{ t('navigation.openMainMenu') }}</span>
          <Bars3Icon class="size-6" aria-hidden="true" />
        </button>
      </div>
      <PopoverGroup class="hidden lg:flex lg:gap-x-12">
        <NavLink :href="routes.dashboard()">{{ t('landing.navigation.pets') }}</NavLink>
        <NavLink href="#">{{ t('landing.navigation.about') }}</NavLink>
        <NavLink href="#">{{ t('landing.navigation.contact') }}</NavLink>
      </PopoverGroup>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <template v-if="$page.props.auth.user">
          <Dropdown align="right" width="48">
            <template #trigger>
              <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                <img class="size-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
              </button>

              <span v-else class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-md text-gray-900 bg-white/80 hover:bg-white transition ease-in-out duration-150 shadow-sm">
                  {{ $page.props.auth.user.name }}
                  <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                  </svg>
                </button>
              </span>
            </template>

            <template #content>
              <div class="block px-4 py-2 text-xs text-gray-400">
                {{ t('navigation.manageAccount') }}
              </div>

              <DropdownLink :href="routes.profile.show()">
                {{ t('navigation.profile') }}
              </DropdownLink>

              <div class="border-t border-gray-200" />

              <form method="POST" :action="routes.logout()" @submit.prevent="$inertia.post(routes.logout())">
                <DropdownLink as="button">
                  {{ t('navigation.logOut') }}
                </DropdownLink>
              </form>
            </template>
          </Dropdown>
        </template>
        <NavLink v-else :href="routes.login()" class="text-sm/6 font-semibold text-gray-900">{{ t('landing.navigation.login') }} <span aria-hidden="true">&rarr;</span></NavLink>
      </div>
    </nav>
    <Dialog class="lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-50">
        <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div class="flex justify-between sticky top-0 bg-white pb-4">
            <Link :href="routes.home()" class="-m-1.5 p-1.5">
              <span class="sr-only">ŁapGo</span>
              <img class="w-1/4" :src="logo.Logo" alt="App logo" loading="lazy" decoding="async">
            </Link>
            <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700 z-10" @click="mobileMenuOpen = false">
              <span class="sr-only">{{ t('navigation.closeMenu') }}</span>
              <XMarkIcon class="size-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/10">
              <div class="space-y-2 py-6 flex flex-col">
                <NavLink :href="routes.dashboard()">{{ t('landing.navigation.pets') }}</NavLink>
                <NavLink href="#">{{ t('landing.navigation.about') }}</NavLink>
                <NavLink href="#">{{ t('landing.navigation.contact') }}</NavLink>
              </div>
              <div class="py-6">
                <NavLink v-if="$page.props.auth.user" :href="routes.profile.show()">{{ t('navigation.profile') }}</NavLink>
                <NavLink v-else :href="routes.login()">{{ t('landing.navigation.login') }}</NavLink>
              </div>
            </div>
          </div>
        </DialogPanel>
      </div>
    </Dialog>
  </header>
</template>
  
<style scoped>
</style>
