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
import { routes } from '../routes'

const { t } = useI18n()
const mobileMenuOpen = ref(false)

</script>

<template>
  <header>
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1">
        <Link :href="routes.home()" class="-m-1.5 p-1.5">
          <span class="sr-only">{{ t('navigation.goToHomepage') }}</span>
          <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="Interns2025c app logo" height="32" loading="lazy" decoding="async">
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
        <NavLink v-if="$page.props.auth.user" href="/profile" class="text-sm/6 font-semibold text-gray-900">Profile</NavLink>
        <NavLink v-else href="/login" class="text-sm/6 font-semibold text-gray-900">Log in <span aria-hidden="true">&rarr;</span></NavLink>
      </div>
    </nav>
    <Dialog class="lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-50">
        <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div class="flex items-center justify-between sticky top-0 bg-white pb-4">
            <Link :href="routes.home()" class="-m-1.5 p-1.5">
              <span class="sr-only">Interns2025c</span>
              <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="App logo" height="32" loading="lazy" decoding="async">
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
