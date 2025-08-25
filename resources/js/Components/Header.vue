<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
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

const mobileMenuOpen = ref(false)

const goToAdmin = () => {
  router.visit('/admin')
}
</script>

<template>
  <header>
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="#" class="-m-1.5 p-1.5">
          <span class="sr-only">Go to homepage</span>
          <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="LapGo app logo" height="32" loading="lazy" decoding="async">
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <Bars3Icon class="size-6" aria-hidden="true" />
        </button>
      </div>
      <PopoverGroup class="hidden lg:flex lg:gap-x-12">
        <NavLink href="#">Adopt Me!</NavLink>
        <NavLink href="#">About Adoption</NavLink>
        <NavLink href="#">Contact Us</NavLink>
      </PopoverGroup>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <NavLink v-if="$page.props.auth.user" href="/user/profile" class="text-sm/6 font-semibold text-gray-900">Profile</NavLink>
        <NavLink v-else href="/login" class="text-sm/6 font-semibold text-gray-900">Log in <span aria-hidden="true">&rarr;</span></NavLink>
      </div>
    </nav>
    <Dialog class="lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-50">
        <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div class="flex items-center justify-between sticky top-0 bg-white pb-4">
            <a href="#" class="-m-1.5 p-1.5">
              <span class="sr-only">LapGo</span>
              <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="App logo" height="32" loading="lazy" decoding="async">
            </a>
            <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700 z-10" @click="mobileMenuOpen = false">
              <span class="sr-only">Close menu</span>
              <XMarkIcon class="size-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/10">
              <div class="space-y-2 py-6 flex flex-col">
                <NavLink href="#">Adopt Me!</NavLink>
                <NavLink href="#">About Adoption</NavLink>
                <NavLink href="#">Contact Us</NavLink>
              </div>
              <div class="py-6">
                <NavLink v-if="$page.props.auth.user" href="/user/profile">Profile</NavLink>
                <NavLink v-else href="/login">Log in</NavLink>
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
